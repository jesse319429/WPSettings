<?php
/**
 * WP Settings - A set of classes to create a WordPress settings page for a Theme or a plugin.
 * @author David M&aring;rtensson <david.martensson@gmail.com>
 * @version 1.1.1
 * @package FeedMeAStrayCat
 * @subpackage WPSettings
 * @license MIT http://en.wikipedia.org/wiki/MIT_License
 */


/*************************************
 
	FEED ME A STRAY CAT
 	
 	Flattr me here: 
 	http://www.feedmeastraycat.net/projects/wordpress-snippets/wpsettings/

	See more WP Snippets by me here: 
	http://www.feedmeastraycat.net/projects/wordpress-snippets/
	
	I do more WP stuff together with Odd Alice:
	http://oddalice.com/
 	
 	
 	
 	HOW TO
 	
	A simple example:
	----------------------------------
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
		
		// Activate settings
		$wp_settings_page->activeteSettings();
	}
	
	function my_admin_page_output() {
		global $wp_settings_page;
		
		$wp_settings_page->output();
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
	
	
	
	REQUIREMENTS
	
	1) PHP 5
	2) WordPress 3.x (Tested in 3.2.1 and up, but will most likely work in 3.0 or even 2.7 when the Settings API was added)
	
	
	
	TODOS
	
	1) Add more types :)
	2) Add html5 style input boxes (as well as some setting to create html or xhtml type inputs)
		
		
	
	VERSION HISTORY
	
	1.0
		A first simple version to handle just text values.
	1.1
		Added types: url, int, checkbox
	
	
	
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
			throw new Exception("Undefined method or property ".$name);
		}
	}
	
	/**
	 * Magic set function, does nothing really at this time :)
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		$this->$name = $value;
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
	 * @param int $position Optional
	 * @return WPSettingsPage
	 */
	public function __construct($page_title, $page_subtitle, $menu_title, $capability, $menu_slug, $function, $icon_url='', $position=100) {
	
		$this->Id = $menu_slug;
		
		$this->Title = $page_title;
		$this->Subtitle = $page_subtitle;

		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
		
		return $this;
		
	}
	
	/**
	 * Activate settings. Is required to be run after all sections and fields has been added to register the settings so that
	 * WordPress saves the data
	 */
	public function activeteSettings() {

		// Get all uniques post names
		$names = array();
		// - Start looping through section
		foreach ($this->Sections AS $index => $section) {
			// - Start looping through each field in section
			foreach ($section->Fields AS $index2 => $field) {
				// - Start looping through each input name in this field (most often only 1)
				foreach ($field->InputName AS $index3 => $input_name) {
					if (strpos($input_name, "[") !== false) {
						$temp = explode("[", $input_name);
						$name = $temp[0];
					}
					else {
						$name = $input_name;
					}
					$names[] = $name;
				}
			}
		}
		$names = array_unique($names);
		
		// Register them for update
		foreach ($names AS $index => $name) {
		 	register_setting($this->Id, $name, array($this, 'sanitize'));
		}
		
	}
	
	
	/**
	 * Output the settings page
	 */
	public function output() {
		?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
			<h2><?php echo ($this->Subtitle ? $this->Subtitle:$this->Title) ?></h2>
			<?php if ($this->SettingsPageDescription) : ?>
				<p><?php echo $this->SettingsPageDescription ?></p>
			<?php endif; ?>
			<form action="options.php" method="post">
			<?php settings_fields($this->Id); ?>
			<?php do_settings_sections($this->Id); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
			</form>
		</div>
		<?php
	}
	
	
	/**
	 * Sanitize data before it's stored in the databse
	 */
	public function sanitize($input) {
		
		// Loop through sections
		foreach ($this->Sections AS $section_index => $section) {
			// Loop through fields in section
			foreach ($section->Fields AS $field_index => $field) {
				// Input name is a loop as well. Even though they all are the same type
				foreach ($field->InputName AS $input_index => $input_name) {
					// Get name
					if (strpos($input_name, "[") !== false) {
						$temp = explode("[", $input_name);
						$name = str_replace("]", "", $temp[1]);
					}
					else {
						$name = $input_name;
					}
					// Isn't a part of this input
					if (!isset($input[$name])) {
						continue;
					}
					// Sanitize by type
					switch ($field->Type) {
						case "checkbox":
							$new_input[$name] = $this->__sanitizeCheckbox($input[$name]);
						break;
						case "int":
							$new_input[$name] = $this->__sanitizeInt($input[$name]);
						break;
						case "url":
							$new_input[$name] = $this->__sanitizeURL($input[$name]);
						break;
						case "text":
						default:
							$new_input[$name] = $this->__sanitizeText($input[$name]);
						break;
					}
				}
			}
		}
		
		return $new_input;
	}
	
	
	/**
	 * Add a new section
	 * @param string $id
	 * @param string $headline
	 * @param string $description Optional
	 * @returns WPSettingsSection
	 */
	public function addSettingsSection($id, $headline, $description='') {
		$section = new WPSettingsSection($this, $id, $headline, $description);	
		$this->Sections[] = &$section;
		return $section;
	}
	
	
	/**
	 * Sanitize plain text
	 * @param string $text
	 * @return string
	 */
	private function __sanitizeText($text) {
		global $wpdb;
		return $wpdb->escape($text);
	}
	
	/**
	 * Sanitize url
	 * @param string $text
	 * @return string
	 */
	private function __sanitizeURL($text) {
		return esc_url($text);
	}
	
	/**
	 * Sanitize int
	 * @param mixed $text
	 * @return int
	 */
	private function __sanitizeInt($text) {
		return (int)($text);
	}
	
	/**
	 * Sanitize checkbox
	 * @param mixed $checked
	 * @return int
	 */
	private function __sanitizeCheckbox($checked) {
		return ($checked ? 1:0);
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
	 * @return WPSettingsField
	 */
	public function addField($field_id, $headline, $type, $input_name, $current_value='', $help_text='') {
		$field = new WPSettingsField($this, $field_id, $headline, $type, $input_name, $current_value, $help_text);
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
	 * @return WPSettingsField
	 */
	function __construct(WPSettingsSection &$WPSettingsSection, $field_id, $headline, $type, $input_name, $current_value='', $help_text='') {
	
		$this->Id = $WPSettingsSection->Id;
		$this->SectionId = $WPSettingsSection->SectionId;
		$this->FieldId = $this->SectionId."_".$field_id;
		$this->Headline = $headline;
		$this->Type = $type;
		// Always store them as array, multiple elements can be added foreach field
		$this->InputName = (is_array($input_name) ? $input_name:array($input_name));
		$this->CurrentValue = (is_array($current_value) ? $current_value:array($current_value));
		$this->HelpText = (is_array($help_text) ? $help_text:array($help_text));
	
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
			
			// Checbox
			case "checkbox":
				$this->__outputCheckboxField();
			break;
			// Int
			case "int":
				$this->__outputTextField();
			break;
			// URL
			case "url":
			// Regular text field
			case "text":
			default:
				$this->__outputTextField();
			break;
		
		}
	}
	
	/**
	 * Output field - Type "text" (regular text field)
	 */
	private function __outputTextField() {
		foreach ($this->InputName AS $index => $input_name) {
			
			$width = 300;
			
			if ($this->HelpText[$index]) {
				$width -= 150;
				?>
				<div style="width: 150px; float: left; padding-top: 2px;"><em><?php echo esc_html( $this->HelpText[$index] ) ?></em></div>
				<?php
			}
			?>
			<div style="width: 150px; float: left;">
				<input type="text" name="<?php esc_attr_e( $this->InputName[$index] ) ?>" id="<?php esc_attr_e( $this->FieldId .'_'.$index ) ?>" value="<?php esc_attr_e( $this->CurrentValue[$index] ) ?>" style="width: <?php echo $width ?>px;" />
			</div>
			<div style="clear: both;"></div>
			<?php
			
		}
	}
	
	/**
	 * Output field - Type "checkbox"
	 */
	private function __outputCheckboxField() {
		foreach ($this->InputName AS $index => $input_name) {
			
			$width = 300;
			
			if ($this->HelpText[$index]) {
				$width -= 150;
				?>
				<div style="width: 150px; float: left; padding-top: 2px;"><em><?php echo esc_html( $this->HelpText[$index] ) ?></em></div>
				<?php
			}
			?>
			<div style="width: 150px; float: left;">
				<input type="checkbox" name="<?php esc_attr_e( $this->InputName[$index] ) ?>" id="<?php esc_attr_e( $this->FieldId .'_'.$index ) ?>" value="1" <?php echo ( $this->CurrentValue[$index] ? 'checked="checked"':'' ) ?> />
			</div>
			<div style="clear: both;"></div>
			<?php
			
		}
	}
		
	
}


