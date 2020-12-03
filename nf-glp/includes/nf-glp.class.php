<?php

class NF_GLP {
	
	protected $repository;
	protected $key_name;
	
	/**
	 * Initialize the class.
	 *
	 * @param string $owner The deposit owner name.
	 */
	public function __construct() {
		
		// Initialize core variables
		$this->repository = 'greaterlouisvilleproject/glp-downloadable';
		$this->key_name = 'glp-downloadable';
		
		// Inject key checking into form rendering
		add_filter('ninja_forms_render_options', array($this, 'ninja_forms_pre_population_callback'), 10, 2);
		
	}
	
	/**
	 * Modify appropriate form elements.
	 *
	 * @param mixed $options The array of attribute arrays for each input in the form.
	 * @param mixed $settings The array of form meta-options.
	 * @return mixed $options An array of post-processed attributes.
	 */
	public function ninja_forms_pre_population_callback($options, $settings) {

		// Specifically target the field with the key modifier
		if($settings['key'] == $this->key_name) {
			
			$options = [];
			
			// Allow downloading of all available options
			array_push($options, [
				'label' => 'All Available Data',
				'value' => 'https://github.com/greaterlouisvilleproject/glp-downloadable/archive/main.zip'
			]);
			
			// Set the label and value to each respective file found by cURL
			foreach($this->get_repo_files() as $file) {
				array_push($options, [
					'label' => $file,
					'value' => 'https://github.com/greaterlouisvilleproject/glp-downloadable/raw/main/'.$file
				]);
			}
		}

		return $options;
	}
	
	/**
	 * Get the list of downloadable files from the GitHub API (built from glppublish)
	 *
	 * @return mixed $data A 1-dimensional array of full file names.
	 */
	private function get_repo_files() {
		
		$data = [];
		
		try {
			
			// Get file metadata from GitHub's API
			$ch = curl_init('https://api.github.com/repos/'.$this->repository.'/contents/');
			curl_setopt_array($ch, [
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER         => false,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_MAXREDIRS      => 3,
				CURLOPT_ENCODING       => "",
				CURLOPT_USERAGENT      => "curl/7.54.1",
				CURLOPT_AUTOREFERER    => true,
				CURLOPT_CONNECTTIMEOUT => 10,
				CURLOPT_TIMEOUT        => 10,
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
			]);
			$raw = @json_decode(curl_exec($ch), true);
			curl_close($ch);

			// Add in all appropriate root-level files
			foreach($raw as $i => $meta) {
				if($meta['type'] == 'file') {
					if(in_array(pathinfo($meta['name'])['extension'], ['csv', 'xls', 'xlsx'])) {
						array_push($data, $meta['name']);
					}
				}
			}
			
		// Warn administrative users of any issues at this point
		} catch(Exception $e) {
			
			if(!function_exists('write_log')) {

				function write_log($log) {
					if(true === WP_DEBUG) {
						if(is_array($log) || is_object($log)) {
							error_log(print_r($log, true));
						} else {
							error_log($log);
						}
					}
				}

			}
			write_log('There was an unspecified error getting downloadable content from GitHub! Users may not be able to download data.');
			
		}
		
		return $data;
	}
	
}