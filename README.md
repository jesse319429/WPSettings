WPSettings
=============

A set of classes to create a WordPress settings page for a Theme or a plugin. (You only need the wpsettings.php file. The readme, license and other files are not required to download and use or distribute).

Flattr me here: http://www.feedmeastraycat.net/projects/wordpress-snippets/wpsettings/

See more WP Snippets by me here: http://www.feedmeastraycat.net/projects/wordpress-snippets/
	
I do more WP stuff together with Odd Alice: http://oddalice.com/

How to
------------

### A simple example
	require_once('/path/to/wpsettings.php');

	add_action('admin_menu', 'my_admin_menu');
	add_action('admin_init', 'my_admin_init');
	
	// This will contain the global WPSettingsPage object
	global $wp_settings_page;
	$wp_settings_page = null;
	
	function my_admin_menu() {
		global $wp_settings_page;
		
		// Create a settings page
		$wp_settings_page = new WPSettingsPage('My page title', 'My settings page title', 'My Menu Title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', 'icon-url.png', $position=100);
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
		$wp_settings_page->activeteSettings();
	}
	
	function my_admin_page_output() {
		global $wp_settings_page;
		
		$wp_settings_page->output();
	}

### Subpages
You can add subpages by calling the function addSubPage() on a WPSettingsPage object. All the regular WPSettings features works on a sub page. The sub page is put as a sub menu page link in the WP menu.
	require_once('/path/to/wpsettings.php');
	
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


Field types
------------

These are the types that can be used in addField() (the third parameter)
	
* "text" - A standard text input type. Sanitized with $wpdb->escape()
* "url" - A URL text. Sanitized with esc_url()
* "int" - A integer. Sanitized with (int)
* "checkbox" - A checkbox, sanitizes to save 1 or 0
* "dropdown" - A select type dropdown. Sanitizes with standard $wpdb->escape()
* "radio" - A set of radio options. Sanitizes with the standard $wpdb->escape()


Requirements
------------
	
1. PHP 5
2. WordPress 3.x (Tested in 3.2.1 and up, but will most likely work in 3.0 or even 2.7 when the Settings API was added)


Todos
------------
	
1. Add more types :)
2. Add html5 style input boxes (as well as some setting to create html or xhtml type inputs)
		
		
Version history
------------
	
* 1.4 (beta)
 * Added the possibility to add subpages to a settings page using $wp_settings_page->addSubPage(). See how to.
 * Changed a bit on page title and subtitle. On setting page it writes it "Title" if only title is given and "Title &mdash; Subtitle" if both is given.
 * Changed so a settings form is only outputed if at least one section has been added via addSettingsSection(). This way a settings page can be created and default content can be put into it.
* 1.3
 * Added type: radio (see how to)
 * Added update message to settings page
* 1.2 - Added type: selectbox (see how to)
* 1.1 - Added types: url, int, checkbox
* 1.0 - A first simple version to handle just text values.