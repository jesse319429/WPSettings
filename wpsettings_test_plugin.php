<?php
/*
Plugin Name: WPSettings Test Plugin
Plugin URI: http://github.com/feedmeastraycat/WPSettings
Description: Test
Author: David Mårtensson <david.martensson@gmail.com>
Version: 0.3
Author URI: http://www.feedmeastraycat.net/
*/

use \FeedMeAStrayCat\WPSettings_1_7_0\WPSettingsPage;
if (!class_exists('\FeedMeAStrayCat\WPSettings_1_7_0\WPSettings')) {
    require_once('wpsettings.php');
}

add_action('admin_menu', 'my_admin_menu');

// This will contain the global WPSettingsPage object
global $wp_settings_page;
$wp_settings_page = null;

function my_admin_menu() {
    global $wp_settings_page;

    // Create a settings page
    $wp_settings_page = new WPSettingsPage('My page title', 'My settings page title', 'My Menu Title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', null, $position=100);
    // Set a id and add a css class so we can change the icon
    //$wp_settings_page->setIcon('my-icon-id', array('my-icon-class'));

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
    $wp_settings_page-> activateSettings();
}

function my_admin_page_output() {
    global $wp_settings_page;

    $wp_settings_page->output();
}