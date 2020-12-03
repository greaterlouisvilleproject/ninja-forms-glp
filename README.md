# ninja-forms-glp
A custom Ninja Forms add-on to allow end users to download GLP-refined data.

Forms with a select input using the "glp-downloadable" key will auto-generate results based on what's inside the [GLP Downloadable](https://github.com/greaterlouisvilleproject/glp-downloadable) repository. A download button can then be linked like so:

*Form Builder > (Your Form) > Emails & Actions > Success Message*
	<a href="{field:glp-downloadable}" target="_blank" class="button">Download</a>

