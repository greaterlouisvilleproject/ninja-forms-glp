<?php
/**
 * @wordpress-plugin
 * Plugin Name:		Ninja Forms - GLP Addon
 * Plugin URI:		https://github.com/greaterlouisvilleproject/glp-data-gateway
 * Description:		A custom Ninja Forms add-on to allow end users to download GLP-refined data.
 * Version:			1.0
 * Author:			UofL Intern Team
 * License:			GPL-3.0+
 * License URI:		http://www.gnu.org/licenses/gpl-3.0.txt
 */

/*
Developer Note -- There's a really dumb issue when using a namespace that contains "ninja-forms", hence the semi-ambiguous naming scheme
*/

// Abort if this file is called directly.
if(!defined('WPINC')) {
	die();
}

// Activation Preprocessing
register_activation_hook(__FILE__, 'verify_ninja_forms');

function verify_ninja_forms() {

	// Abort if Ninja Forms isn't available
	if(!is_plugin_active('ninja-forms/ninja-forms.php') && current_user_can('activate_plugins')) {
		wp_die('Sorry, but the Ninja Forms GLP Addon plugin requires the Ninja Forms plugin to be installed and active.<br><a href="'.admin_url('plugins.php').'">&laquo; Return to Plugins</a>');
	}

}

// Include the main library
require plugin_dir_path(__FILE__).'includes/nf-glp.class.php';


// Run a new instance of the plugin
$plugin = new NF_GLP();

?>