WPSettings
=============

A set of classes to create a WordPress settings page for a Theme or a plugin.

How to
------------

### A simple example
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
	
	function admin_init() {
		global $wp_settings_page;
		
		// Adds a config section
		$section = $wp_settings_page->addSettingsSection('first_section', 'The first section', 'This is the first section');
		// Adds a text input
		$section->addField('test', 'Test value', 'text', 'my_options[test]', 'Default value', 'Prefixed help text');
		
		// Activate settings
		$wp_settings_page->activeteSettings();
	}
	
	function my_admin_page_output() {
		global $wp_settings_page;
		
		$wp_settings_page->output();
	}


Field types
------------

These are the types that can be used in addField() (the third parameter)
	
* "text"
** A standard text input type. Sanitized with $wpdb->escape()
* "url"
** A URL text. Sanitized with esc_url()
* "int"
** A integer. Sanitized with (int)
* "checkbox"
** A checkbox, sanitizes to save 1 or 0


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
	
* 1.0
** A first simple version to handle just text values.
* 1.1
** Added types: url, int, checkbox