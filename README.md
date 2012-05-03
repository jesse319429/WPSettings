WPSettings
=============

A set of classes to create a WordPress settings page for a Theme or a plugin. (You only need the wpsettings.php file. The readme, license and other files are not required to download and use or distribute).

Flattr me here: http://www.feedmeastraycat.net/projects/wordpress-snippets/wpsettings/

See more WP Snippets by me here: http://www.feedmeastraycat.net/projects/wordpress-snippets/
	
I do more WP stuff together with Odd Alice: http://oddalice.com/

How to
------------

### Important note about namespaces
To enable WPSettings to work on a WordPress site, where multiple plugins or themes uses WPSettings (even though it might not be that common), a namespace has been added to WPSettings. 
The namespace will always look like this: \FeedMeAStrayCat\WPSettings_1_6_4 (for that specific version).
 	
Use it together with if (!class_exists('\FeedMeAStrayCat\WPSettings_1_6_4\WPSettings')) to only include your WPSettings file with the correct version if it hasn't already been included.
 	
In you code you can use the namespace to call the function/class directly: 

	$wp_settings_page = new \FeedMeAStrayCat\WPSettings_1_6_4\WPSettingsPage(...)
 	
Or you use the "use" statement:

	use \FeedMeAStrayCat\WPSettings_1_6_4\WPSettingsPage;
	if (!class_exists('\FeedMeAStrayCat\WPSettings_1_6_4\WPSettings'))
		require_once('/path/to/wpsettings.php');
	}
	$wp_settings_page = new WPSettingsPage(...)
 		
Just remember to add use statements to all classes that you call directly.

### A simple example

	use \FeedMeAStrayCat\WPSettings_1_6_4\WPSettingsPage;
	if (!class_exists('\FeedMeAStrayCat\WPSettings_1_6_4\WPSettings')) {
		require_once('/path/to/wpsettings.php');
	}

	add_action('admin_menu', 'my_admin_menu');
	add_action('admin_init', 'my_admin_init');
	
	// This will contain the global WPSettingsPage object
	global $wp_settings_page;
	$wp_settings_page = null;
	
	function my_admin_menu() {
		global $wp_settings_page;
		
		// Create a settings page
		$wp_settings_page = new WPSettingsPage('My page title', 'My settings page title', 'My Menu Title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', 'icon-url.png', $position=100);
		// Set a id and add a css class so we can change the icon
		$wp_settings_page->setIcon('my-icon-id', array('my-icon-class'));
	}
	
	function my_admin_init() {
		global $wp_settings_page;
		
		// Adds a config section
		$section = $wp_settings_page->addSettingsSection('first_section', 'The first section', 'This is the first section');

		// Adds a text input
		$section->addField('test_value', 'Test value', 'text', 'my_options[test]', 'Default value', 'Prefixed help text');

		// Adds three checkboxes
		$section->addField('test_checkboxes', 'Select cake', 'checkbox', array('my_options[cake_1]', 'my_options[cake_2]', 'my_options[cake_3]'), array(false, false, false), array('Cake 1', 'Cake 2', 'Cake 3'));

		// Adds a dropdown without a option group
		$dropdown = $section->addField('test_select', 'Select day', 'dropdown', 'my_options[day]', 'mon');
		$dropdown->addOption('mon', 'Monday');
		$dropdown->addOption('tues', 'Tuesday');

		// Adds a dropdown with two groups
		$dropdown = $section->addField('test_select2', 'Select day again', 'dropdown', 'my_options[day2]', 6);
		// Uncomment this option to get a groupless option in the beginning
		//$dropdown->addOption(0, 'Choose a Day');
		$optgroup = $dropdown->addOptionGroup('Weekday');
		$dropdown->addOption(1, 'Monday', $optgroup);
		$dropdown->addOption(2, 'Tuesday', $optgroup);
		$optgroup = $dropdown->addOptionGroup('Weekend');
		$dropdown->addOption(6, 'Saturday', $optgroup);
		$dropdown->addOption(7, 'Sunday', $optgroup);

		// Adds three "radio" options
		$radio = $section->addField('test_radio', 'Select month', 'radio', 'my_options[month]', 'jan');
		$radio->addOption('jan', 'January');
		$radio->addOption('feb', 'February');
		$radio->addOption('mar', 'March');
		
		// Activate settings
		$wp_settings_page-> activateSettings();
	}
	
	function my_admin_page_output() {
		global $wp_settings_page;
		
		$wp_settings_page->output();
	}

### Subpages
You can add subpages by calling the function addSubPage() on a WPSettingsPage object. All the regular WPSettings features works on a sub page. The sub page is put as a sub menu page link in the WP menu.

	use \FeedMeAStrayCat\WPSettings_1_6_4\WPSettingsPage;
	if (!class_exists('\FeedMeAStrayCat\WPSettings_1_6_4\WPSettings')) {
		require_once('/path/to/wpsettings.php');
	}
	
	add_action('admin_menu', 'my_admin_menu');
	add_action('admin_init', 'my_admin_init');
	
	// This will contain the global WPSettingsPage object
	global $wp_settings_page, $wp_settings_sub_page;
	$wp_settings_page = null;
	$wp_settings_sub_page = null;
	
	function my_admin_menu() {
		global $wp_settings_page, $wp_settings_sub_page;
		
		// Create a settings page
		$wp_settings_page = new WPSettingsPage('My page title', 'Subtitle', 'My menu title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', 'icon-url.png', $position=100);
		
		// Create a sub page
		$wp_settings_sub_page = $wp_settings_page->addSubPage('My subpage', 'Subtitle', 'My menu subtitle', 'manage_options', 'my_unique_subpage_slug', 'my_admin_subpage_output');
	}
	
	function my_admin_init() {
		global $wp_settings_page, $wp_settings_sub_page;
		
		// Create sections and so on ...
	}
	
	function my_admin_page_output() {
		global $wp_settings_page;
		
		$wp_settings_page->output();
	}
	
	function my_admin_subpage_output() {
		global $wp_settings_page, $wp_settings_sub_page;
		
		// You can do
		$wp_settings_page->output('my_unique_subpage_slug');
		// Or you can do
		// $wp_settings_sub_page->output();
	}

### Filters
Through WPSettingsField->addFilter() you can add filters that uses the built in WP filtes api. Send in which type of filter you want to use, which must be one of the WPSettingsField::FILTER_ constants, the callback function and a priority integer.

	use \FeedMeAStrayCat\WPSettings_1_6_4\WPSettingsPage;
	use \FeedMeAStrayCat\WPSettings_1_6_4\WPSettingsField;
	if (!class_exists('\FeedMeAStrayCat\WPSettings_1_6_4\WPSettings')) {
		require_once('/path/to/wpsettings.php');
	}
	
	add_action('admin_menu', 'my_admin_menu');
	add_action('admin_init', 'my_admin_init');
	
	// This will contain the global WPSettingsPage object
	global $wp_settings_page;
	$wp_settings_page = null;
	
	function my_admin_menu() {
		global $wp_settings_page;
		
		// Create a settings page
		$wp_settings_page = new WPSettingsPage('My page title', 'Subtitle', 'My menu title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', 'icon-url.png', $position=100);
	}
	
	function my_admin_init() {
		global $wp_settings_page;

		// Adds a config section
		$section = $wp_settings_page->addSettingsSection('first_section', 'The first section', 'This is the first section');
		
		// Adds a text input
		$field = $section->addField('test_value', 'Test value', 'text', 'my_options[test]', 'Default value', 'Prefixed help text');
		// Add a filter for when the text input is updated (1 is it's priority)
		// The filters are called using WP built in filter API
		$field->addFilter(WPSettingsField::FILTER_UPDATE, 'update_text_value', 1);
		
		// Activate settings
		$wp_settings_page->activateSettings();
	}
	
	function my_admin_page_output() {
		global $wp_settings_page;
		
		$wp_settings_page->output();
	}
	
	function update_text_value($field_obj, $input_value) {
		// Do stuff or things...
		// Optional, return altered input value.
		// Return null to leave it as it is
		return $input_value;
	}

### Output Sections
Output sections can be used to output custom HTML in the end of a settings page. Each output section is a callback function that will be called after the settings sections in the order they where added. If you want to input custom form elements, you need to store them by your self using the "wps_before_update" action.

	use \FeedMeAStrayCat\WPSettings_1_6_4\WPSettingsPage;
	if (!class_exists('\FeedMeAStrayCat\WPSettings_1_6_4\WPSettings')) {
		require_once('/path/to/wpsettings.php');
	}
	
	add_action('admin_menu', 'my_admin_menu');
	add_action('admin_init', 'my_admin_init');
	
	// This will contain the global WPSettingsPage object
	global $wp_settings_page;
	$wp_settings_page = null;
	
	function my_admin_menu() {
		global $wp_settings_page;
		
		// Create a settings page
		$wp_settings_page = new WPSettingsPage('My page title', 'Subtitle', 'My menu title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', 'icon-url.png', $position=100);
	}
	
	function my_admin_init() {
		global $wp_settings_page;
		
		// Adds a config section
		$section = $wp_settings_page->addSettingsSection('first_section', 'The first section', 'This is the first section');
		
		// Adds a text input
		$field = $section->addField('test_value', 'Test value', 'text', 'my_options[test]', 'Default value', 'Prefixed help text');
		
		// Adds custom html in a output sections
		$section = $wp_settings_page->addOutputSection('html_section', 'output_my_html_section', 'Optional Headline');
		
		// Activate settings
		$wp_settings_page->activateSettings();
	}
	
	function my_admin_page_output() {
		global $wp_settings_page;
		
		$wp_settings_page->output();
	}
	
	function output_my_html_section() {
		?>
		<p>Some custom HTML here...</p>
		<?php
	}


Field types
------------

These are the types that can be used in addField() (the third parameter)
	
* "text" - A standard text input type. Sanitized with $wpdb->escape()
* "url" - A URL text. Sanitized with esc_url()
* "int" - A integer. Sanitized with (int)
* "checkbox" - A checkbox, sanitizes to save 1 or 0
* "dropdown" - A select type dropdown. Sanitizes with standard $wpdb->escape()
* "radio" - A set of radio options. Sanitizes with the standard $wpdb->escape()
* "hex_color" - A HTML hex color value. Sanitize with allowed hex valued colors.


Filters
------------
	
These filters are available
	
* FILTER_UPDATE
 * Parameters: 2 
 * Parameter 1: WPSettingsField object
 * Parameter 2: Input value
 * Runs after sanitize, before value is stored in DB. The $field_id is the first parameter sent into addField(). This parameter must be 1 to 50 characters, a-z (case insensitive), 0-9 or "-" and "_". Note that this filter runs on all inputs in that field. If you send in multiple fields in an array (like in the example "Adds three checkboxes") the same filter will run on all.


Actions
------------
	
These are the custom actions that are thrown by WPSettings which can be used to hook in custom features.
	
* wps_before_update
 * Parameters: 0
 * Called after validation. Before update.


Requirements
------------
	
1. PHP 5.3 (changed from 5.0 to 5.3 in WPSettings 1.6.4)
2. WordPress 3.x (Tested in 3.2.1 and up, but will most likely work in 3.0 or even 2.7 when the Settings API was added)


Todos
------------
	
1. Add more types :)
2. Add html5 style input boxes (as well as some setting to create html or xhtml type inputs)
3. Add more filters and actions
		
		
Version history
------------

* 1.6.9
 * Removed redundant class_exist check. You should do a class exist like in the examples. :)
 * Changed bad nameing habit of mine where i use double underscore ("__") as a prefix to private methods/vars. Changed to single underscore ("_").
* 1.6.8
 * Added $placeholder as 7th optional parameter in $section->addField() which is used as placeholder-attribute in text fields (for example "text" and "url" field type).
* 1.6.7
 * Changed how "checkbox" is output by adding a hidden field that is changed to "1" or "0" depending on the checkbox. It's changed using jQuery (which is required and enqueued when a WPSettingsPage is constructed) and the .change() event. This way a checkbox is stored as "1" or "0". Not just "1" when it's clicked and not at all when it's "0".
* 1.6.6
 * Moved Field sanitize from WPSettingsPage->sanitize() to WPSettingsField->sanitize() and fixed how register_setting() is called in WPSettingsPage->activateSettings(). This fixes a bug that prevented settings name, that wasn't part of an array (ex my_settings[name]), to be stored correctly. Settings now registers ones per name (which must be unique) as well as one per "array name" ("my_settings" from example my_settings[name]). Array names registers with WPSettingsPage->sanitize() which loops through and calles WPSettingsField->sanitize() once per field. On the fields that has that specific array name.
 * Throws an exception when adding fields without a unique name.
 * Fixed namespace error when throwing Exceptions.
* 1.6.5
 * Removed width on container divs for output of dropdowns
* 1.6.4
 * Now requires PHP 5.3
 * WPSettings now is available from namespace FeedMeAStrayCat\WPSettings_X_X_X\ (see first note about namespaces).
 * Added footer text "Created with WPSettings X.X.X" to admin footer (on pages created with WPSettings). Can be disabled by setting WPSettings::$no_footer_text to true.
 * Removed WP_SETTINGS_VERSION (added in 1.6.2) since it's not needed now with the namespace.	
* 1.6.3
 * New type: "hex_color".
* 1.6.2
 * WPSettings now make sure a constant exists called WP_SETTINGS_VERSION. This will contain the version number of the current loaded WPSettings. If two versions are loaded. The first loaded version number will be in the const. If WPSettings is loaded, but no WP_SETTINGS_VERSION is found, it is set as 1.0. With this you can make sure that the latest is loaded, and output an error message if it's not. If multiple WPSettings are loaded it can still cause some problem, since you need to make sure that the first one loaded is the version you need. Not sure how to fix that. Now you can see which version is loaded anyway. :)
* 1.6.1
 * Added Output Sections (see how to).
 * Fixed a small error in the how to examples.
 * Added action "wps_before_update".
 * Bug fix on FILTER_UPDATE.
* 1.6
 * Added validations of the id and field id in addSettingsSection() and addField(). These ids must be 1 to 50 characters, a-z (case insensitive), 0-9 or "-" and "_". The functions will throw an exception if the id fails the validation. 
 * Added filters function (see how to).
 * Added filter FILTER_UPDATE.
 * Had misspelled activateSettings() as activeteSettings() ... Since start. :-| Both works now. Misspelled is deprecated and might be removed in future releases.
* 1.5.2
 * Wrap eeeverything within a class_exists() check to make sure the code isn't included twice through different files, and by that causes trouble.
* 1.5.1
 * Bugfix set default position to null instead of 100. If there is two pages on 100 only one will show. But if you just use null they will show in the bottom. After each other.
* 1.5
 * Added $wp_settings_page->setIcon($icon_id, $add_classes) which can be used to change the HTML id and class of the settings page icon. Togheter with some css it can be used to change the icon. (WPSettings currently doesn't create the css required). See the simple example.
* 1.4.1
 * Bugfix for subpage settings not beeing saved correct.
* 1.4
 * Added the possibility to add subpages to a settings page using $wp_settings_page->addSubPage(). See how to.
 * Changed a bit on page title and subtitle. On setting page it writes it "Title" if only title is given and "Title &mdash; Subtitle" if both is given.
 * Changed so a settings form is only outputed if at least one section has been added via addSettingsSection(). This way a settings page can be created and default content can be put into it.
* 1.3
 * Added type: radio (see how to)
 * Added update message to settings page
* 1.2 - Added type: selectbox (see how to)
* 1.1 - Added types: url, int, checkbox
* 1.0 - A first simple version to handle just text values. 