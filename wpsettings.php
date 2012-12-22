<?php
/**
 * WP Settings - A set of classes to create a WordPress settings page for a Theme or a plugin.
 * @author David M&aring;rtensson <david.martensson@gmail.com>
 * @version 1.6.11
 * @package FeedMeAStrayCat
 * @subpackage WPSettings
 * @license MIT http://en.wikipedia.org/wiki/MIT_License
 */


// Set namespace
namespace FeedMeAStrayCat\WPSettings_1_6_11;


/*************************************
 
	FEED ME A STRAY CAT
 	
 	Flattr me here: 
 	http://www.feedmeastraycat.net/projects/wordpress-snippets/wpsettings/

	See more WP Snippets by me here: 
	http://www.feedmeastraycat.net/projects/wordpress-snippets/
	
	I do more WP stuff together with Odd Alice:
	http://oddalice.com/
 	
 	
 	
 	HOW TO
 	
 	Important note about namespaces:
 	----------------------------------
 	To enable WPSettings to work on a WordPress site, where multiple plugins or themes uses WPSettings (even though it might not be that common), 
 	a namespace has been added to WPSettings. 
 	The namespace will always look like this: \FeedMeAStrayCat\WPSettings_1_6_4 (for that specific version).
 	
 	Use it together with if (!class_exists('\FeedMeAStrayCat\WPSettings_1_6_4\WPSettings')) to only include your WPSettings file with the correct
 	version if it hasn't already been included.
 	
 	In you code you can use the namespace to call the function/class directly: 
 		$wp_settings_page = new \FeedMeAStrayCat\WPSettings_1_6_4\WPSettingsPage(...)
 	
 	Or you use the "use" statement:
 		use \FeedMeAStrayCat\WPSettings_1_6_4\WPSettingsPage;
 		if (!class_exists('\FeedMeAStrayCat\WPSettings_1_6_4\WPSettings'))
 			require_once('/path/to/wpsettings.php');
 		}
 		$wp_settings_page = new WPSettingsPage(...)
 		
 	Just remember to add use statements to all classes that you call directly.
 	----------------------------------
 	
	A simple example:
	----------------------------------
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
		$wp_settings_page = new WPSettingsPage('My page title', 'Subtitle', 'My Menu Title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', 'icon-url.png', $position=100);
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
		$wp_settings_page->activateSettings();
	}
	
	function my_admin_page_output() {
		global $wp_settings_page;
		
		$wp_settings_page->output();
	}
	----------------------------------	
	
	Subpages:
	You can add subpages by calling the function addSubPage() on a WPSettingsPage object.
	All the regular WPSettings features works on a sub page. The sub page is put as a sub menu
	page link in the WP menu.
	----------------------------------
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
	----------------------------------
	
	Filters:
	Through WPSettingsField->addFilter() you can add filters that uses the built in WP filtes api. Send in which type of filter
	you want to use, which must be one of the WPSettingsField::FILTER_ constants, the callback function and a priority integer.
	----------------------------------
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
	----------------------------------
	
	Output Sections:
	Output sections can be used to output custom HTML in the end of a settings page. Each output section is a callback function
	that will be called after the settings sections in the order they where added. If you want to input custom form elements, you
	need to store them by your self using the "wps_before_update" action.
	----------------------------------
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
	----------------------------------
	
	
	
	FIELD TYPES
	
	These are the types that can be used in addField() (the third parameter)
	
	"text"
		A standard text input type. Sanitized with $wpdb->escape()
	"url"
		A URL text. Sanitized with esc_url()
	"int"
		A integer. Sanitized with (int)
	"checkbox"
		A checkbox, sanitizes to save 1 or 0
	"dropdown"
		A select type dropdown. Sanitizes with standard $wpdb->escape()
	"radio"
		A set of radio options. Sanitizes with the standard $wpdb->escape()
	"hex_color"
		A HTML hex color value. Sanitize with allowed hex valued colors.
		
		
		
	FILTERS
	
	These filters are available
	
	FILTER_UPDATE
		Parameters: 2 
		Parameter 1: WPSettingsField object
		Parameter 2: Input value
		Runs after sanitize, before value is stored in DB. The $field_id is the first parameter sent into addField(). This
		parameter must be 1 to 50 characters, a-z (case insensitive), 0-9 or "-" and "_".
		Note that this filter runs on all inputs in that field. If you send in multiple fields in an array (like in the
		example "Adds three checkboxes") the same filter will run on all.
		
		
		
	ACTIONS
	
	These are the custom actions that are thrown by WPSettings which can be used to hook in custom features.
	
	wps_before_update
		Parameters: 0
		Called after validation. Before update.
		
		
		
	SETTINGS
	
	WPSettings::$no_footer_text = true/false; 
		To display, or not display, footer information text on WPSettings created pages.
	
	
	
	REQUIREMENTS
	
	1) PHP 5.3 (changed from 5.0 to 5.3 in WPSettings 1.6.4)
	2) WordPress 3.x (Tested in 3.2.1 and up, but will most likely work in 3.0 or even 2.7 when the Settings API was added)
	
	
	
	TODOS
	
	1) Add more types :)
	2) Add html5 style input boxes (as well as some setting to create html or xhtml type inputs)
	3) Add more filters and actions
		
		
	
	VERSION HISTORY
	
	1.6.11
		Bugfix. Had two places with short php open tags (just <? without "php") as well as missing the "echo".
	1.6.10
		Added $description as 8th parameter in $field = $section->addField(). Will output a <p class="description"> tag below
		the field HTML. (Can, like Headline, Type, InputName, CurrentValue, HelpText and Placeholder be set using 
		$field->Description = "Foo Bar" because of the magic __set() funciton).
		Updated the magic __set() function to always call setX() functions, if they exists, before just setting the value.
		Bugfix in activateSettings() which caused subpage forms not submit correctly.
		Changed some bas usages of esc_attr_e() to esc_attr()
		Added text domain
	1.6.9
		Removed redundant class_exist check. You should do a class exist like in the examples. :)
		Changed bad nameing habit of mine where i use double underscore ("__") as a prefix to private methods/vars. Changed
		to single underscore ("_").
	1.6.8
		Added $placeholder as 7th optional parameter in $section->addField() which is used as placeholder-attribute in
		text fields (for example "text" and "url" field type).
	1.6.7
		Changed how "checkbox" is output by adding a hidden field that is changed to "1" or "0" depending on the checkbox.
		It's changed using jQuery (which is required and enqueued when a WPSettingsPage is constructed) and the .change()
		event. This way a checkbox is stored as "1" or "0". Not just "1" when it's clicked and not at all when it's "0".
	1.6.6
		Moved Field sanitize from WPSettingsPage->sanitize() to WPSettingsField->sanitize() and fixed how 
		register_setting() is called in WPSettingsPage->activateSettings(). This fixes a bug that prevented settings name,
		that wasn't part of an array (ex my_settings[name]), to be stored correctly. Settings now registers ones per
		name (which must be unique) as well as one per "array name" ("my_settings" from example my_settings[name]).
		Array names registers with WPSettingsPage->sanitize() which loops through and calles WPSettingsField->sanitize()
		once per field. On the fields that has that specific array name.
		Throws an exception when adding fields without a unique name.
		Fixed namespace error when throwing Exceptions.
	1.6.5
		Removed width on container divs for output of dropdowns
	1.6.4
		Now requires PHP 5.3
		WPSettings now is available from namespace FeedMeAStrayCat\WPSettings_X_X_X\ (see first note about namespaces).
		Added footer text "Created with WPSettings X.X.X" to admin footer (on pages created with WPSettings). Can be
		disabled by setting WPSettings::$no_footer_text to true.
		Removed WP_SETTINGS_VERSION (added in 1.6.2) since it's not needed now with the namespace.
	1.6.3
		New type: "hex_color"
	1.6.2
		WPSettings now make sure a constant exists called WP_SETTINGS_VERSION. This will contain the version number
		of the current loaded WPSettings. If two versions are loaded. The first loaded version number will be in the
		const. If WPSettings is loaded, but no WP_SETTINGS_VERSION is found, it is set as 1.0. With this you can 
		make sure that the latest is loaded, and output an error message if it's not. If multiple WPSettings are
		loaded it can still cause some problem, since you need to make sure that the first one loaded is the version
		you need. Not sure how to fix that. Now you can see which version is loaded anyway. :)
	1.6.1
		Added Output Sections (see how to).
		Fixed a small error in the how to examples.
		Added action "wps_before_update".
		Bug fix on FILTER_UPDATE.
	1.6
		Added validations of the id and field id in addSettingsSection() and addField(). These ids must be 1 to 50
		characters, a-z (case insensitive), 0-9 or "-" and "_". The functions will throw an exception if the id
		fails the validation. 
		Added filters function (see how to).
		Added filter FILTER_UPDATE.
		Had misspelled activateSettings() as activeteSettings() ... Since start. :-| Both works now. Misspelled is
		deprecated and might be removed in future releases.
	1.5.2
		Wrap eeeverything within a class_exists() check to make sure the code isn't included twice through
		different files, and by that causes trouble.
	1.5.1
		Bugfix set default position to null instead of 100. If there is two pages on 100 only one will show. But if
		you just use null they will show in the bottom. After each other.
	1.5
		Added $wp_settings_page->setIcon($icon_id, $add_classes) which can be used to change the HTML id and class
		of the settings page icon. Togheter with some css it can be used to change the icon. (WPSettings currently
		doesn't create the css required). See the simple example.
	1.4.1
		Bugfix for subpage settings not beeing saved correct.
	1.4
		Added the possibility to add subpages to a settings page using $wp_settings_page->addSubPage(). See how to.
		Changed a bit on page title and subtitle. On setting page it writes it "Title" if only title is given and
		"Title &mdash; Subtitle" if both is given.
		Changed so a settings form is only outputed if at least one section has been added via addSettingsSection().
		This way a settings page can be created and default content can be put into it. 
	1.3
		Added type: radio (see how to)
		Added update message to settings page
	1.2
		Added type: selectbox (see how to)
	1.1
		Added types: url, int, checkbox
	1.0
		A first simple version to handle just text values.
	
	
	
	LICENSE - MIT
	
	http://en.wikipedia.org/wiki/MIT_License
	
	Copyright (C) 2011 by David M&aring;rtensson <david.martensson@gmail.com>
	
	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
		
*************************************/

	
/**
 * WP Settings base class - Extended by WPSettingsPage, WPSettingsSection and WPSettingsField
 */
class WPSettings {
	
	// Version constant
	const VERSION = "1.6.10";
	
	
	/**
	 * Keeps all input names to make sure that they are unique
	 * Access is protected because it's only used internally by WPSettingsField->addField()
	 * @var array
	 * @static
	 * @access protected
	 */
	protected static $_input_names = array();
	
	
	/**
	 * Override to true to remove footer text
	 * @var bool
	 */
	public static $no_footer_text = false;

	/**
	 * Magic get function, gets method first if exists, or property
	 * @param string $name
	 * @return mixed
	 * @throws Exception
	 */
	public function __get($name) {
		if (method_exists($this, $name)) {
			return $this->$name();
		}
		elseIf (isset($this->$name)) {
			return $this->$name;
		}
		else {
			// isset is false on null parameters, this "fixes" that checking if tha parameter truely doesn't exist
			$props = get_object_vars($this);
			if (array_key_exists($name, $props)) {
				return $this->$name;
			}
			else {
				throw new \Exception("Undefined method or property ".$name);
			}
		}
	}
	
	/**
	 * Magic set function
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function __set($name, $value) {
		$method = "set{$name}";
		if (method_exists($this, $method)) {
			$this->$method($value);
		}
		elseIf (isset($this->$name)) {
			$this->$name = $value;
		}
		else {
			// isset is false on null parameters, this "fixes" that checking if tha parameter truely doesn't exist
			$props = get_object_vars($this);
			if (array_key_exists($name, $props)) {
				$this->$name = $value;
			}
			else {
				throw new \Exception("Failed to set \"".$name."\".");
			}
		}
	}

}



/**
 * WP Settings Page class
 * @see WPSettings
 */
class WPSettingsPage extends WPSettings {

	public $Id;
	
	protected $Title;
	protected $Subtitle;
	protected $SettingsPageDescription;
	protected $Sections = array();
	protected $OutputSections = array();
	protected $MenuSlug;
	
	private $_subpages = array();
	private $_pageIconClass = array();
	private $_pageIconId;
	
	
	/**
	 * Create a WP Settings Page
	 * @todo Allow both menu page and options page?
	 * @param string $page_title 
	 * @param string $page_subtitle
	 * @param string $menu_title
	 * @param string $capability
	 * @param string $menu_slug
	 * @param string|array $function
	 * @param string $icon_url Optional
	 * @param int|null $position Optional
	 * @return WPSettingsPage
	 */
	public function __construct($page_title, $page_subtitle, $menu_title, $capability, $menu_slug, $function, $icon_url='', $position=null) {
		
		wp_enqueue_script("jquery");
	
		$this->Id = $menu_slug;
		
		$this->Title = $page_title;
		$this->Subtitle = $page_subtitle;
		$this->MenuSlug = $menu_slug;

		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
		
		return $this;
		
	}
	
	/**
	 * Add a sub page
	 * @param string $page_title
	 * @param string $page_subtitle
	 * @param string $menu_title
	 * @param string $capability
	 * @param string $menu_slug
	 * @param string|array $function
	 * @return WPSettingsPage
	 */
	public function addSubPage($page_title, $page_subtitle, $menu_title, $capability, $menu_slug, $function) {
		
		// Create sub page as.
		$subpage = new WPSettingsSubPage(&$this, $page_title, $page_subtitle, $menu_title, $capability, $menu_slug, $function);
		
		// Store in this (parent) page array
		$this->_subpages[$subpage->Id] = &$subpage;
		
		return $subpage; 
		
	}
	
	/**
	 * Activate settings. Is required to be run after all sections and fields has been added to register the settings so that
	 * WordPress saves the data
	 */
	public function activateSettings() {

		// Start looping through all pages
		$pages = array_merge(array($this), $this->_subpages);
		foreach ($pages AS $index => $page) {
			// Get all uniques post names on array type names (ex my_setting[name])
			$array_post_names = array();
			// - Start looping through section
			foreach ($page->Sections AS $index => $section) {
				// - Start looping through each field in section
				foreach ($section->Fields AS $index2 => $field) {
					// - Start looping through each input name in this field (most often only 1)
					foreach ($field->InputName AS $index3 => $input_name) {
						// Part of an array name, ex: my_settings[name]
						// These registers as one setting for "my_settings", even if there exist my_settings[name]
						// and my_settings[name2]. Registers once per $page (in first loop here).
						if (strpos($input_name, "[") !== false) {
							$temp = explode("[", $input_name);
							$name = $temp[0];
							$array_post_names[] = $name;
						}
						// Is it's own name, can only work when the name is unique, registers the setting directly
						// with sanitize on the $field object 
						else {
							register_setting($page->Id, $input_name, array($field, 'sanitize'));
						}
					}
				}
			}
			// Got any array post names?
			if (count($array_post_names) > 0) {
				// Get unique names
				$array_post_names = array_unique($array_post_names);
				// Register them for sanitize on the $page object
				foreach ($array_post_names AS $index => $name) {
				 	register_setting($page->Id, $name, array($page, 'sanitize'));
				}
			}
		}
		
	}
	
	/**
	 * Had misspelled activate as activete from beta to 1.5.2... :-|
	 * @deprecated
	 * @see WPSettingsPage::activateSettings()
	 */
	public function activeteSettings() {
		$this->activateSettings();
	}
	
	
	/**
	 * Output the settings page
	 * @param string $subpage
	 */
	public function output($subpage='') {
		// Output a subpage (call that objects output() function)
		if ($subpage && isset($this->_subpages[$subpage]) && is_object($this->_subpages[$subpage])) {
			$this->_subpages[$subpage]->output();
		}
		// Output this object page
		else {
			$this->_output();
		}
	}
	
	
	/**
	 * Output settings page footer (added through action: admin_footer_text)
	 * Enter description here ...
	 * @param unknown_type $footer_text
	 */
	public function outputFooterText($footer_text) {
		// Remove action
		remove_action('admin_footer_text', array($this, 'outputFooterText'));
		// Append and return footer text
		$footer_text .= " | ".sprintf(__('Settings page created with <a href="%s">WPSettings %s</a>', 'wpsettings'), 'https://github.com/feedmeastraycat/WPSettings', WPSettings::VERSION);
		return $footer_text;
	}
	
	
	/**
	 * The actual output of this objects page
	 */
	private function _output() {
		// Add action for footer text
		if (!$this::$no_footer_text) {
			add_action('admin_footer_text', array($this, 'outputFooterText'), 50, 1);
		}
		// Output page
		?>
		<div class="wrap">
			<?php $this->_getIcon(); ?>
			<h2><?php echo $this->Title ?><?php echo ($this->Title && $this->Subtitle ? " &mdash; ".$this->Subtitle:"") ?></h2>
			<?php if( isset($_GET['settings-updated']) ) : ?>
			    <div id="message" class="updated">
			        <p><strong><?php _e('Settings saved.', 'wpsettings') ?></strong></p>
			    </div>
			<?php endif; ?>
			<?php if ($this->SettingsPageDescription) : ?>
				<p><?php echo $this->SettingsPageDescription ?></p>
			<?php endif; ?>
			<?php if ( !empty($this->Sections )): ?>
				<form action="options.php" method="post">
				<?php settings_fields($this->Id); ?>
				<?php do_settings_sections($this->Id); ?>
				<?php
				if (count($this->OutputSections) > 0) {
					foreach ($this->OutputSections AS $index => $section) {
						if (!empty($section['headline'])) {
							?>
							<h3><?php echo $section['headline'] ?></h3>
							<?php
						}
						call_user_func($section['callback']);
					}
				}
				?>
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'wpsettings'); ?>" >
				</p>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}
	
	
	/**
	 * Sanitize data before it's stored in the databse. This sanitize function is registered when there are array post
	 * names. For example if the names on three fields are my_settings[name1], my_settings[name2] and other_setting.
	 * This function will be used to sanitize "my_settings" and $input will be an array containing the values.
	 * But "other_setting" will be santized directly by the field object sanitize() function (which gets called
	 * by this function, in the loop, as well).
	 * @param array $input
	 */
	public function sanitize($input) {
		
		// Create new input
		$new_input = array();
	
		// Loop through sections
		foreach ($this->Sections AS $section_index => $section) {
			// Loop through fields in section
			foreach ($section->Fields AS $field_index => $field) {
				// Input name is a loop as well. Even though they all are the same type
				foreach ($field->InputName AS $input_index => $input_name) {
					// Get name
					if (strpos($input_name, "[") !== false) {
						// Get name from input name
						$temp = explode("[", $input_name);
						$name = str_replace("]", "", $temp[1]);
						// Isn't a part of this input
						if (!isset($input[$name])) {
							continue;
						}
						// Get input
						$_input = $input[$name];
					}
					// Has nothing to do here
					else {
						continue;
					}
					
					// Sanitize on the field
					$new_input[$name] = $field->sanitize($_input);

				}
			}
		}

		// Do update action
		do_action('wps_before_update');

		return $new_input;
	}
	
	
	/**
	 * Add a new section
	 * @param string $id
	 * @param string $headline
	 * @param string $description Optional
	 * @return WPSettingsSection
	 * @throws Exception
	 */
	public function addSettingsSection($id, $headline, $description='') {
		if (!preg_match("/^[a-z0-9\-\_]{1,50}$/i", $id)) {
			throw new \Exception("Section id failed to validate");
		}
		$section = new WPSettingsSection($this, $id, $headline, $description);	
		$this->Sections[] = &$section;
		return $section;
	}
	
	
	/**
	 * Add output section
	 * @param string $id
	 * @param mixed $callback
	 * @param string $headline Optional
	 * @return bool
	 * @throws Exception
	 */
	public function addOutputSection($id, $callback, $headline='') {
		// Validate id
		if (!preg_match("/^[a-z0-9\-\_]{1,50}$/i", $id)) {
			throw new \Exception("Section id failed to validate");
		}
		
		// Make sure class and metod exists if array
		if (is_array($callback)) {
			if (is_string($callback[0]) && !class_exists($callback[0], true)) {
				return false;
			}
			if (!method_exists($callback[0], $callback[1])) {
				return false;
			}
		}
		
		// Make sure function exists if string
		if (is_string($callback)) {
			if (!function_exists($callback)) {
				return false;
			}
		}
		
		// Store output section
		$this->OutputSections[] = array('callback' => $callback, 'headline' => $headline);
	}
	
	
	/**
	 * Set page icon
	 * @param string $icon_id
	 * @param array $add_classes Optional
	 */
	public function setIcon($icon_id, $add_classes=array()) {
		$this->_pageIconId = $icon_id;
		$this->_pageIconClass = $add_classes;
	}
	
	
	/**
	 * Get page icon
	 */
	private function _getIcon() {
		$class = array("icon32");
		if (!empty($this->_pageIconClass)) {
			$class = array_merge($class, $this->_pageIconClass);
		}
		$id = ($this->_pageIconId ? $this->_pageIconId:"icon-options-general");
		?>
		<div class="<?php echo implode(" ", $class)?>" id="<?php echo $id ?>"><br></div>
		<?php
	}
	

}



/**
 * WP Settings Sub Page class
 * @see WPSettingsPage
 */
class WPSettingsSubPage extends WPSettingsPage {

	public $Id;
	
	protected $Title;
	protected $Subtitle;
	protected $MenuSlug;
	
	
	/**
	 * Create a WP Settings Sub Page
	 * @todo Allow both menu page and options page?
	 * @param WPSettingsPage $WPSettingsPage
	 * @param string $page_title 
	 * @param string $page_subtitle
	 * @param string $menu_title
	 * @param string $capability
	 * @param string $menu_slug
	 * @param string|array|bool $function
	 * @return WPSettingsPage
	 */
	public function __construct(WPSettingsPage &$WPSettingsPage, $page_title, $page_subtitle, $menu_title, $capability, $menu_slug, $function) {
	
		$this->Id = $menu_slug;
		
		$this->Title = $page_title;
		$this->Subtitle = $page_subtitle;
		$this->MenuSlug = $menu_slug;

		//add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function);
		//echo $WPSettingsPage->MenuSlug;die;
		add_submenu_page($WPSettingsPage->MenuSlug, $page_title, $menu_title, $capability, $menu_slug, $function);
		//add_option_whitelist(array('oaui_login' => array('oa_user_import')));
		
		return $this;
		
	}
		
}



/**
 * WP Settings Section class
 * @see WPSettings
 */
class WPSettingsSection extends WPSettings {

	public $Id;
	public $SectionId;
	
	protected $Headline;
	protected $Description;
	protected $Fields = array();
	
	/**
	 * Create a WP Settings Section (called by WPSettingsPage->addSettingsSection()
	 * @see WPSettingsPage->addSettingsSection
	 * @param WPSettingsPage $WPSettingsPage
	 * @param string $section_id
	 * @param string $headline
	 * @param string $description Optional
	 * @return WPSettingsSection
	 */
	function __construct(WPSettingsPage &$WPSettingsPage, $section_id, $headline, $description='') {
		
		$this->Id = $WPSettingsPage->Id;
		$this->SectionId = $this->Id."_".$section_id;
		$this->Headline = $headline;
		$this->Description = $description;

		add_settings_section($this->SectionId,
			$this->Headline,
			array($this, 'outputDescription'),
			$this->Id
		);
		
		return $this;
		
	}
	
	/**
	 * Output description
	 */
	public function outputDescription() {
		if ($this->Description) {
			?><p><?php echo $this->Description ?></p><?php
		}
	}
	
	/**
	 * Add a WP Settings Field 
	 * @param string $field_id
	 * @param string $headline
	 * @param string|array $input_name
	 * @param string|array $current_value
	 * @param string|array $help_text
	 * @param string|array $placeholder
	 * @param string|array $description
	 * @return WPSettingsField
	 * @throws Exception
	 */
	public function addField($field_id, $headline, $type, $input_name, $current_value='', $help_text='', $placeholder='', $description='') {
		// Validate id
		if (!preg_match("/^[a-z0-9\-\_]{1,50}$/i", $field_id)) {
			throw new \Exception("Section id failed to validate");
		}
		// Make sure field name is unique
		if (in_array($input_name, self::$_input_names)) {
			throw new \Exception("Input name is not unique.");
		}
		// Store input name to make sure they are unique
		self::$_input_names[] = $input_name;
		// Create dropdown
		if ($type == "dropdown") {
			$field = new WPSettingsFieldDropDown($this, $field_id, $headline, $type, $input_name, $current_value, $help_text, $placeholder, $description);
		}
		// Create a radio
		elseIf ($type == "radio") {
			$field = new WPSettingsFieldRadio($this, $field_id, $headline, $type, $input_name, $current_value, $help_text, $placeholder, $description);
		}
		// Create any other type
		else {
			$field = new WPSettingsField($this, $field_id, $headline, $type, $input_name, $current_value, $help_text, $placeholder, $description);
		}
		$this->Fields[] = &$field;
		return $field;
	}

}



/**
 * WP Settings Field class
 * @see WPSettingsSection
 */
class WPSettingsField extends WPSettingsSection {

	public $Id;
	public $SectionId;
	public $FieldId;
	
	protected $Headline;
	protected $Type;
	protected $InputName;
	protected $CurrentValue;
	protected $HelpText;
	protected $Placeholder;
	protected $Description;
	protected $Events = array();
	
	const FILTER_UPDATE = "upd";
	
	private $_filterParameters = array(
		'upd' => 2
	);
	
	/**
	 * Creates a WP Settings Field (called by WPSettingsSection->addField). 
	 * Possible values for $type is:
	 * - text (regular text field)
	 * @see WPSettingsSection->addField
	 * @param WPSettingsSection $WPSettingsSection
	 * @param string $field_id
	 * @param string $headline
	 * @param string $type 
	 * @param string|array $input_name
	 * @param string[array $current_value
	 * @param string|array $help_text
	 * @param string|array $placeholder
	 * @param string|array $description
	 * @return WPSettingsField
	 */
	function __construct(WPSettingsSection &$WPSettingsSection, $field_id, $headline, $type, $input_name, $current_value='', $help_text='', $placeholder='', $description='') {
	
		$this->Id = $WPSettingsSection->Id;
		$this->SectionId = $WPSettingsSection->SectionId;
		$this->FieldId = $this->SectionId."_".$field_id;
		$this->Headline = $headline;
		$this->Type = $type;
		// Always store them as array, multiple elements can be added foreach field
		$this->InputName = (is_array($input_name) ? $input_name:array($input_name));
		$this->CurrentValue = (is_array($current_value) ? $current_value:array($current_value));
		$this->HelpText = (is_array($help_text) ? $help_text:array($help_text));
		$this->Placeholder = (is_array($placeholder) ? $placeholder:array($placeholder));
		$this->Description = (is_array($description) ? $description:array($description));
	
 		add_settings_field($this->FieldId,
			$this->Headline,
			array($this, 'outputField'),
			$this->Id,
			$this->SectionId
		);
		
		return $this;
		
	}
	
	/**
	 * Output field (calls private functions by type)
	 */
	public function outputField() {
		switch ($this->Type) {
			
			// Drop down
			case "dropdown":
				$this->_outputDropDownField();
			break;
			// Radio
			case "radio":
				$this->_outputRadioField();
			break;
			// Checbox
			case "checkbox":
				$this->_outputCheckboxField();
			break;
			// Int
			case "int":
				$this->_outputTextField();
			break;
			// Hex color
			case "hex_color":
				$this->_outputHexColorField();
			break;
			// URL
			case "url":
			// Regular text field
			case "text":
			default:
				$this->_outputTextField();
			break;
		
		}
	}
	
	/**
	 * Add filter to this field
	 * @param int $event Must be one of the FILTER_ constants of this class
	 * @param string|array $callback A function to call on the event
	 * @param int $priority Forwarded into the add_filter() function
	 */
	public function addFilter($filter, $callback, $priority=10) {
		// Make sure class and metod exists if array
		if (is_array($callback)) {
			if (is_string($callback[0]) && !class_exists($callback[0], true)) {
				return false;
			}
			if (!method_exists($callback[0], $callback[1])) {
				return false;
			}
		}
		
		// Make sure function exists if string
		if (is_string($callback)) {
			if (!function_exists($callback)) {
				return false;
			}
		}
		
		// Add filter
		add_filter('wps_'.$filter.'_'.$this->FieldId, $callback, $priority, $this->_filterParameters[$filter]);
	}
	
	/**
	 * Set description
	 * @param string|array $description
	 */
	public function setDescription($description) {
		$this->Description = (is_array($description) ? $description:array($description));
	}
	
	/**
	 * Sanitize
	 * @param mixed $value
	 * @return $value Returnes sanitized value
	 */
	public function sanitize($value) {
		
		// Sanitize by type
		switch ($this->Type) {
			case "checkbox":
				$new_value = $this->_sanitizeCheckbox($value);
			break;
			case "int":
				$new_value = $this->_sanitizeInt($value);
			break;
			case "url":
				$new_value = $this->_sanitizeURL($value);
			break;
			case "hex_color":
				$new_value = $this->_sanitizeHexColor($value);
			break;
			case "dropdown":
			case "radio":
			case "text":
			default:
				$new_value = $this->_sanitizeText($value);
			break;
		}
		
		// Do filter
		if ( has_filter('wps_'.WPSettingsField::FILTER_UPDATE.'_'.$this->FieldId) ) {
			$return_value = apply_filters('wps_'.WPSettingsField::FILTER_UPDATE.'_'.$this->FieldId, $this, $new_value);
			if (!is_null($return_value)) {
				$new_value = $return_value;
			}
		 }
		 
		 // Return
		 return $new_value;
		
	}
	
	/**
	 * Sanitize plain text
	 * @param string $text
	 * @return string
	 */
	private function _sanitizeText($text) {
		global $wpdb;
		return $wpdb->escape($text);
	}
	
	/**
	 * Sanitize url
	 * @param string $text
	 * @return string
	 */
	private function _sanitizeURL($text) {
		return esc_url($text);
	}
	
	/**
	 * Sanitize hex color
	 * @param string $text
	 */
	private function _sanitizeHexColor($text) {
		$text = str_replace("#", "", $text);
		if (preg_match('/^[a-f0-9]{6}$/i', $text)) {
			return $text;
		}
		else {
			return "";
		}
	}
	
	/**
	 * Sanitize int
	 * @param mixed $text
	 * @return int
	 */
	private function _sanitizeInt($text) {
		return (int)($text);
	}
	
	/**
	 * Sanitize checkbox
	 * @param mixed $checked
	 * @return int
	 */
	private function _sanitizeCheckbox($checked) {
		return ($checked ? 1:0);
	}
	
	/**
	 * Output field - Type "text" (regular text field)
	 */
	private function _outputTextField() {
		foreach ($this->InputName AS $index => $input_name) {
			
			$width = 300;
			
			if (isset($this->HelpText[$index]) && $this->HelpText[$index]) {
				$width -= 150;
				?>
				<div style="width: 150px; float: left; padding-top: 2px;"><em><?php echo esc_html( $this->HelpText[$index] ) ?></em></div>
				<?php
			}
			?>
			<div style="width: 150px; float: left;">
				<input type="text" name="<?php echo esc_attr( $this->InputName[$index] ) ?>" id="<?php echo esc_attr( $this->FieldId .'_'.$index ) ?>" value="<?php echo esc_attr( $this->CurrentValue[$index] ) ?>" <?php if ($this->Placeholder[$index]): ?>placeholder="<?php echo esc_attr($this->Placeholder[$index]) ?>"<?php endif; ?> style="width: <?php echo $width ?>px;" >
			</div>
			<div style="clear: both;"></div>
			<?php
			
			$this->_outputDescription($index);
			
		}
	}
	
	/**
	 * Output field - Type "hex_color"
	 */
	private function _outputHexColorField() {
		foreach ($this->InputName AS $index => $input_name) {
			
			$width = 60;
			
			if (isset($this->HelpText[$index]) && $this->HelpText[$index]) {
				?>
				<div style="width: 150px; float: left; padding-top: 2px;"><em><?php echo esc_html( $this->HelpText[$index] ) ?></em></div>
				<?php
			}
			?>
			<div style="width: 150px; float: left;">
				# <input type="text" name="<?php echo esc_attr( $this->InputName[$index] ) ?>" id="<?php echo esc_attr( $this->FieldId .'_'.$index ) ?>" value="<?php echo esc_attr( $this->CurrentValue[$index] ) ?>" style="width: <?php echo $width ?>px;" >
			</div>
			<div style="clear: both;"></div>
			<?php
			
			$this->_outputDescription($index);
			
		}
	}
	
	/**
	 * Output field - Type "checkbox"
	 */
	private function _outputCheckboxField() {
		foreach ($this->InputName AS $index => $input_name) {
			
			$width = 300;
			
			if (isset($this->HelpText[$index]) && $this->HelpText[$index]) {
				$width -= 150;
				?>
				<div style="width: 150px; float: left; padding-top: 2px;"><em><?php echo esc_html( $this->HelpText[$index] ) ?></em></div>
				<?php
			}
			?>
			<div style="width: 150px; float: left;">
				<script language="javascript" type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#cb_<?php echo esc_attr( $this->FieldId .'_'.$index ) ?>').change(function() {
						var value = (jQuery('#cb_<?php echo esc_attr( $this->FieldId .'_'.$index ) ?>').is(':checked') ? 1:0);
						jQuery('#<?php echo esc_attr( $this->FieldId .'_'.$index ) ?>').val(value);
					});
				});
				</script>
				<input type="hidden" name="<?php echo esc_attr( $this->InputName[$index] ) ?>" id="<?php echo esc_attr( $this->FieldId .'_'.$index ) ?>" value="<?php echo ($this->CurrentValue[$index] ? "1":"0") ?>" >
				<input type="checkbox" name="cb_<?php echo esc_attr( $this->InputName[$index] ) ?>" id="cb_<?php echo esc_attr( $this->FieldId .'_'.$index ) ?>" value="1" <?php checked($this->CurrentValue[$index], 1) ?> >
			</div>
			<div style="clear: both;"></div>
			<?php
			
			$this->_outputDescription($index);
			
		}
	}
	
	/**
	 * Output field - Type "dropdown"
	 */
	private function _outputDropDownField() {
		foreach ($this->InputName AS $index => $input_name) {
			
			$width = 300;
			
			if (isset($this->HelpText[$index]) && $this->HelpText[$index]) {
				$width -= 150;
				?>
				<div style="width: 150px; float: left; padding-top: 2px;"><em><?php echo esc_html( $this->HelpText[$index] ) ?></em></div>
				<?php
			}
			?>
			<div style="float: left;">
				<select name="<?php echo esc_attr( $this->InputName[$index] ) ?>" id="<?php echo esc_attr( $this->FieldId .'_'.$index ) ?>">
					<?php
					// Loop options through option groups
					if ($this->OptionGroups) {
						// First get options with specifically no group (default value most likely)
						$this->_outputDropDownOptions($index, false);
						// Then loop through the option groups
						foreach ($this->OptionGroups AS $optgroup_index => $optgroup) {
							?>
							<optgroup label="<?php echo esc_attr( $optgroup->Name )?>">
								<?php
								$this->_outputDropDownOptions($index, $optgroup);
								?>
							</optgroup>
							<?php
						}
					}
					// Loop all options without groups
					else {
						$this->_outputDropDownOptions($index);
					} 
					?>
				</select>
			</div>
			<div style="clear: both;"></div>
			<?php
			
			$this->_outputDescription($index);
			
		}
	}
	
	/**
	 * Output dropdown options. All or for a specific option group. If $optgroup is null, all options will be
	 * shown. If it's false, only options without group will be shown. If it's set only options of that group
	 * will be shown.
	 * @param int $field_index The current fields index
	 * @param WPSettingsDropDownOptionGroup|null|false $optgroup Optional
	 */
	private function _outputDropDownOptions($field_index, $optgroup=null) {
		foreach ($this->Options AS $option_index => $option) {
			// Skip if
			// Trying to get option group only (not null and not false)
			// And this option isn't part of that option group
			// Or this option has no option group at all
			if ( !is_null($optgroup) && $optgroup !== false && ( (!is_null($option->OptionGroup) && $option->OptionGroup->Name !== $optgroup->Name) || (is_null($option->OptionGroup)) ) ) {
				continue;
			}
			// Skip if 
			// Optgroup is set to false, and this option has a optgroup
			if ($optgroup === false && !is_null($option->OptionGroup)) {
				continue;
			}
			?>
			<option value="<?php echo esc_attr( $option->Value )?>" id="<?php echo esc_attr( $this->FieldId .'_'.$field_index.'_'.$option_index ) ?>" <?php selected($this->CurrentValue[$field_index], $option->Value) ?>><?php echo esc_attr( $option->Name ) ?></option>
			<?php
		}
	}
	
	/**
	 * Output field - Type "radio"
	 */
	private function _outputRadioField() {
		foreach ($this->InputName AS $index => $input_name) {
			
			$width = 300;
			
			if (isset($this->HelpText[$index]) && $this->HelpText[$index]) {
				$width -= 150;
				?>
				<div style="width: 150px; float: left; padding-top: 2px;"><em><?php echo esc_html( $this->HelpText[$index] ) ?></em></div>
				<?php
			}
			?>
			<div style="width: 150px; float: left;">
				<?php
				foreach ($this->Options AS $option_index => $option) {
					?>
					<p>
						<input type="radio" name="<?php echo esc_attr( $this->InputName[$index] ) ?>" value="<?php echo esc_attr( $option->Value )?>" id="<?php echo esc_attr( $this->FieldId .'_'.$index.'_'.$option_index ) ?>" <?php checked($this->CurrentValue[$index], $option->Value) ?>> <label for="<?php echo esc_attr( $this->FieldId .'_'.$index.'_'.$option_index ) ?>"><?php echo esc_attr( $option->Name ) ?></label>
					</p>
					<?php
				}
				?>
			</div>
			<div style="clear: both;"></div>
			<?php
			
			$this->_outputDescription($index);
			
		}
	}
	
	/**
	 * Output descreiption for a specific field index
	 * @param int $field_index
	 */
	private function _outputDescription($field_index) {
		if (isset($this->Description[$field_index]) && !empty($this->Description[$field_index])) {
			?>
			<p class="description"><?php echo $this->Description[$field_index]; ?></p>
			<?php
		}
	}
		
	
}



/**
 * WP Settings Field Drop Down class
 * @see WPSettingsField
 */
class WPSettingsFieldDropDown extends WPSettingsField {
	
	public $Options = array();
	public $OptionGroups = array();
	
	/**
	 * Add an option to the drop down
	 * @param string|int $value
	 * @param string $name Optional
	 * @param WPSettingsDropDownOptionGroup|null $optgroup Optional
	 * @return WPSettingsDropDownOption
	 */
	public function addOption($value, $name='', $optgroup=null) {
		$name = ($name ? $name:$value);
		$option = new WPSettingsDropDownOption($value, $name, $optgroup);
		$this->Options[] = $option;
		return $option;
	}
	
	/**
	 * Add an option group
	 * @param string $name
	 */
	public function addOptionGroup($name) {
		$optgroup = new WPSettingsDropDownOptionGroup($name);
		$this->OptionGroups[] = $optgroup;
		return $optgroup;
	}
	
}



/**
 * WP Settings Field Drop Down Option class
 * @see WPSettings
 */
class WPSettingsDropDownOption extends WPSettings {
	
	protected $Value;
	protected $Name;
	protected $OptionGroup = null;
	
	/**
	 * Creates a drop down option
	 * @param string|int $value
	 * @param string $name
	 * @param WPSettingsDropDownOptionGroup|null $optgroup Optional option group
	 * @return WPSettingsDropDownOption
	 */
	function __construct($value, $name, $optgroup=null) {
		$this->Value = $value;
		$this->Name = $name;	
		$this->OptionGroup = $optgroup;
		return $this;
	}
	
}



/**
 * WP Settings Field Drop Down Option Group class
 * @see WPSettings
 */
class WPSettingsDropDownOptionGroup extends WPSettings {
	
	protected $Name;
	
	/**
	 * Creates a drop down option group
	 * @param string|int $value
	 * @param string $name
	 * @return WPSettingsDropDownOptionGroup
	 */
	function __construct($name) {
		$this->Name = $name;	
		return $this;
	}
	
}



/**
 * WP Settings Field Radio class
 * @see WPSettingsField
 */
class WPSettingsFieldRadio extends WPSettingsField {
	
	public $Options = array();
	
	/**
	 * Add an option to the drop down
	 * @param string|int $value
	 * @param string $name Optional
	 * @return WPSettingsRadioOption
	 */
	public function addOption($value, $name='') {
		$name = ($name ? $name:$value);
		$option = new WPSettingsRadioOption($value, $name);
		$this->Options[] = $option;
		return $option;
	}
	
}



/**
 * WP Settings Field Radio Option class
 * @see WPSettings
 */
class WPSettingsRadioOption extends WPSettings {
	
	protected $Value;
	protected $Name;
	
	/**
	 * Creates a radio option
	 * @param string|int $value
	 * @param string $name
	 * @return WPSettingsRadioOption
	 */
	function __construct($value, $name) {
		$this->Value = $value;
		$this->Name = $name;	
		return $this;
	}
	
}