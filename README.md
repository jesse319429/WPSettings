WPSettings
==========

A set of classes to create a WordPress settings page for a Theme or a plugin. (You only need the wpsettings.php file. The readme, license and other files are not required to download and use or distribute).

Flattr me here: http://www.feedmeastraycat.net/projects/wordpress-snippets/wpsettings/

See more WP Snippets by me here: http://www.feedmeastraycat.net/projects/wordpress-snippets/
	
I do more WP stuff together with Odd Alice: http://oddalice.com/

How to
------

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

	use \FeedMeAStrayCat\WPSettings_1_7_0\WPSettingsPage;
	if (!class_exists('\FeedMeAStrayCat\WPSettings_1_7_0\WPSettings')) {
		require_once('/path/to/wpsettings.php');
	}
	
	add_action('admin_menu', 'my_admin_menu');
	
	// This will contain the global WPSettingsPage object
	global $wp_settings_page;
	$wp_settings_page = null;
	
	function my_admin_menu() {
		global $wp_settings_page;
		
		// Create a settings page
		$wp_settings_page = new WPSettingsPage('My page title', 'Subtitle', 'My Menu Title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', 'icon-url.png', $position=100);
		// Set a id and add a css class so we can change the icon
		$wp_settings_page->setIcon('my-icon-id', array('my-icon-class'));
		
		// Adds a config section
		$section = $wp_settings_page->addSettingsSection('first_section', 'The first section', 'This is the first section');
		
		// Adds a text input
		$section->addField('test_value', 'Test value', 'text', 'my_options[test]', 'Default value', 'Prefixed help text');
		
		// Adds a textarea
		$field = $section->addField('textarea_value', 'Textarea', 'textarea', 'my_options[textarea]', 'Default textarea value', 'Prefixed help text');
		$field->setSize(200, 80);
		
		// Adds a wysiwyg
		$wysiwyg = $section->addField('wysiwyg_value', 'Test wysiwyg', 'wysiwyg', 'my_options[wysiwyg]', 'my_options[wysiwyg]', 'Wysiwyg help text');
		$wysiwyg->setSettings(array('textarea_rows' => 5));
		
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
		$wp_settings_page->activateSettings();
	}
	
	function my_admin_page_output() {
		global $wp_settings_page;
		
		$wp_settings_page->output();
	}
	
### Subpages
You can add subpages by calling the function addSubPage() on a WPSettingsPage object. All the regular WPSettings features works on a sub page. The sub page is put as a sub menu page link in the WP menu.

	use \FeedMeAStrayCat\WPSettings_1_7_0\WPSettingsPage;
	if (!class_exists('\FeedMeAStrayCat\WPSettings_1_7_0\WPSettings')) {
		require_once('/path/to/wpsettings.php');
	}
	
	add_action('admin_menu', 'my_admin_menu');
	
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

	use \FeedMeAStrayCat\WPSettings_1_7_0\WPSettingsPage;
	use \FeedMeAStrayCat\WPSettings_1_7_0\WPSettingsField;
	if (!class_exists('\FeedMeAStrayCat\WPSettings_1_7_0\WPSettings')) {
		require_once('/path/to/wpsettings.php');
	}
	
	add_action('admin_menu', 'my_admin_menu');
	
	// This will contain the global WPSettingsPage object
	global $wp_settings_page;
	$wp_settings_page = null;
	
	function my_admin_menu() {
		global $wp_settings_page;
		
		// Create a settings page
		$wp_settings_page = new WPSettingsPage('My page title', 'Subtitle', 'My menu title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', 'icon-url.png', $position=100);
		
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

	use \FeedMeAStrayCat\WPSettings_1_7_0\WPSettingsPage;
	if (!class_exists('\FeedMeAStrayCat\WPSettings_1_7_0\WPSettings')) {
		require_once('/path/to/wpsettings.php');
	}
	
	add_action('admin_menu', 'my_admin_menu');
	
	// This will contain the global WPSettingsPage object
	global $wp_settings_page;
	$wp_settings_page = null;
	
	function my_admin_menu() {
		global $wp_settings_page;
		
		// Create a settings page
		$wp_settings_page = new WPSettingsPage('My page title', 'Subtitle', 'My menu title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', 'icon-url.png', $position=100);
		
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

### Adding custom style and attributes:
You can set custom style and attribute using $field->setAttribute() and $field->setStyle(). The value you set should be an array of associative arrays. Even if you only have one don't add multiple fields in one go as in the example below.
It can also be done one attribute or style at a time using $field->addAttribute() or $field->addStyle() where the first two parameters are the attribute/style name and value and the third parameter is the index for which field to set it to. 
Attributes can also be set directly into $section->addField() as the 9th parameter and styles as the 10th.

	use \FeedMeAStrayCat\WPSettings_1_9_0\WPSettingsPage;
	if (!class_exists('\FeedMeAStrayCat\WPSettings_1_9_0\WPSettings')) {
		require_once('/path/to/wpsettings.php');
	}
	
	add_action('admin_menu', 'my_admin_menu');
	
	// This will contain the global WPSettingsPage object
	global $wp_settings_page;
	$wp_settings_page = null;
	
	function my_admin_menu() {
		global $wp_settings_page;
		
		// Create a settings page
		$wp_settings_page = new WPSettingsPage('My page title', 'Subtitle', 'My menu title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', 'icon-url.png', $position=100);
		
		// Adds a config section
		$section = $wp_settings_page->addSettingsSection('first_section', 'The first section', 'This is the first section');
		
		// Adds a text input
		$field = $section->addField('test_value', 'Test', 'text', array('my_options[test]', 'my_options[test2]'));
		$field->setAttributes(array(array('readonly' => 'readonly'), array('maxlength' => 10));
		$field->setStyle(array(array('wifth' => '200px'), array('width' => '300px'));
	
		// Can also be done like this:
		$field = $section->addField('test_value2', 'Test 2', 'text', array('my_options[test3]', 'my_options[test4]'));
		$field->addAttribute('readonly', 'readonly', 0);
		$field->addAttribute('maxlength', '10', 1);
		$field->addStyle('width', '200px', 0);
		$field->addStyle('width', '300px', 1);
		
		// Or for only one value
		$field = $section->addField('test_value3', 'Test ', 'text', 'my_options[test5]');
		$field->addAttribute('readonly', 'readonly');
		$field->addAttribute('maxlength', '10');
		$field->addStyle('width', '200px');
		$field->addStyle('border', '1px solid red');
		
		// Activate settings
		$wp_settings_page->activateSettings();
	}
	
	function my_admin_page_output() {
		global $wp_settings_page;
		
		$wp_settings_page->output();
	}


Field types
-----------

These are the types that can be used in addField() (the third parameter)
	
* "text" - A standard text input type. Unsanitized.
* "textarea" - A textarea input type. Unsanitized. Set size with $field->setSize(int $width, int $height)
* "wysiwyg" - A What You See Is What You Get editor using the built in wp_editor() function. Unsanitized. Set settings args with $field->setSettings()
* "url" - A URL text. Sanitized with esc_url()
* "int" - A integer. Sanitized with (int)
* "checkbox" - A checkbox, sanitizes to save 1 or 0
* "dropdown" - A select type dropdown. Unsanitized.
* "radio" - A set of radio options. Unsanitized.
* "hex_color" - A HTML hex color value. Sanitize with allowed hex valued colors.


Page types
----------
	
Stand alone - Using $page = new WPSettingsPage() you can create a stand alone page that can contains sub pages
using $page->addSubPage().

Sub page to Theme section - Use WPSettingsThemePage.

Sub page to Dashboard page - Use WPSettingsDashboardPage.

Sub page to Posts page - Use WPSettingsPostsPage.

Sub page to Media page - Use WPSettingsMediaPage.

Sub page to Links page - Use WPSettingsLinksPage.

Sub page to Pages page - Use WPSettingsPagesPage.

Sub page to Comments page - Use WPSettingsCommentsPage.

Sub page to Plugins page - Use WPSettingsPluginsPage.

Sub page to Users page - Use WPSettingsUsersPage.

Sub page to Management page - Use WPSettingsManagementPage.

Sub page to Options page - Use WPSettingsOptionsPage.

Note that using $page->addSubPage() on any other page then the stand alone WPSettingsPage() object will throw
an exception.


Network admin
-------------

From version 1.9.0 there should be no issues creating a network admin settings page. Settings are stored using 
update_site_option() and must therefor be fetched using get_site_option() instead of get_option().

http://codex.wordpress.org/Function_Reference/update_site_option
http://codex.wordpress.org/Function_Reference/get_site_option


Filters
-------
	
These filters are available
	
* FILTER_UPDATE
 * Parameters: 2 
 * Parameter 1: WPSettingsField object
 * Parameter 2: Input value
 * Runs after sanitize, before value is stored in DB. The $field_id is the first parameter sent into addField(). This parameter must be 1 to 50 characters, a-z (case insensitive), 0-9 or "-" and "_". Note that this filter runs on all inputs in that field. If you send in multiple fields in an array (like in the example "Adds three checkboxes") the same filter will run on all.


Actions
-------
	
These are the custom actions that are thrown by WPSettings which can be used to hook in custom features.
	
* wps_before_update
 * Parameters: 0
 * Called after validation. Before update. Note that this action is triggered once for each field.


Requirements
------------
	
1. PHP 5.3 (changed from 5.0 to 5.3 in WPSettings 1.6.4)
2. WordPress 3.x (Tested in 3.2.1 and up, but will most likely work in 3.0 or even 2.7 when the Settings API was added)


Todos
-----
	
1. Add more types :)
2. Add html5 style input boxes (as well as some setting to create html or xhtml type inputs)
3. Add more filters and actions


Important notes
---------------

### 1.9.0 - 2013-11-06
I've noticed that the wps_before_update action was only activated when input names existed, and when it was an 
array, because the action was just triggered from WPSettingsPage->sanitize().
The action has been moved to WPSettingsField->sanitize() so it will get triggered on regular input names as well.
This means that it will be triggered once for each added input.
		
I've also noticed that there is no way to only use output settings and store stuff using the regular WPSettings
form. You have to do a output setting that contains the form it self. Or just add some setting through WPSetting
and the specials through a output section.

WPSettings can now also create network admin pages. Fields are stored using update_site_option().

### 1.7.0 - 2013-01-01
In WordPress 3.5 it seams like a change was made on ajax calls where the admin_menu action isn't triggered which 
would cause problems with media upload if you follow the old examples using admin_menu to setup the WPSettingsPage
object and admin_init to register the settings page fields.

The easiest solution is to only use the admin_menu action and setup everything on that action. Another way would 
be to make sure that your $wp_settings_page variable is an object before moving on in admin_init.

The examples have been updated to reflect this in 1.7.0.
		
		
Version history
---------------

* 1.9.1
 * Fixed dropdown to work with multiple attribute set (uses in_array() to test value instead of string == compare)
 * Removed esc_sql() sanitization. Both update_option() and update_site_option() expect unsanitized data and esc_sql() can cause issues.	
* 1.9.0
 * Changes to wps_before_update action, will now trigger once on each added field
 * Settings pages are now also functional on network admin pages
 * Added action to correctly enqueue jquery on admin pages
 * Replaced deprecated $wpdb->escape() with esc_sql()
 * Added $field->setCurrentValue()
 * Added $field->setHelpText()
 * Added $field->setPlaceholder()
 * Added $field->setDescription()
 * Added $field->setAttributes()
 * Added $field->addAttribute()
 * Added $field->setStyle()
 * Added $field->addStyle()
 * Input fields (not checkbox, radio buttons or the wysiwyg) can have added attributes and custom style
* 1.8.1
 * Removed &$this pass by reference (deprecated)
 * Bugfix. Couldn't use only output sections.
 * Only output form if there are any settings sections.
* 1.8.0
 * Added the possibility to create subpages to the available sections using the objects WPSettingsThemePage, WPSettingsDashboardPage, WPSettingsPostsPage, WPSettingsMediaPage, WPSettingsLinksPage, WPSettingsPagesPage, WPSettingsCommentsPage, WPSettingsPluginsPage, WPSettingsUsersPage, WPSettingsManagementPage and WPSettingsOptionsPage.
* 1.7.0
 * Fixed div container width on output regular text field
 * Added "textare" as field type. Set size using $field->setSize(int $width, int $height).
 * Added "wysiwyg" as field type. Set settings using $field->setSettings(array $settings)
 * Changed examples due to changes in WP 3.5 where admin_menu action isn't triggered (I think) on ajax calls which created an error with media uploads site wide.
* 1.6.11
 * Bugfix. Had two places with short php open tags (just <? without "php") as well as missing the "echo".
* 1.6.10
 * Added $description as 8th parameter in $field = $section->addField(). Will output a <p class="description"> tag below the field HTML. (Can, like Headline, Type, InputName, CurrentValue, HelpText and Placeholder be set using  $field->Description = "Foo Bar" because of the magic __set() funciton).
 * Updated the magic __set() function to always call setX() functions, if they exists, before just setting the value.
 * Bugfix in activateSettings() which caused subpage forms not submit correctly.
 * Changed some bas usages of esc_attr_e() to esc_attr()
 * Added text domain
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