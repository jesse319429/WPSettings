<?php
/**
 * WP Settings - A set of classes to create a WordPress settings page for a Theme or a plugin.
 * @author David M&aring;rtensson <david.martensson@gmail.com>
 * @version 1.4
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
		$wp_settings_page = new WPSettingsPage('My page title', 'Subtitle', 'My Menu Title', 'manage_options', 'my_unique_slug', 'my_admin_page_output', 'icon-url.png', $position=100);
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
	----------------------------------	
	
	Subpages:
	You can add subpages by calling the function addSubPage() on a WPSettingsPage object.
	All the regular WPSettings features works on a sub page. The sub page is put as a sub menu
	page link in the WP menu.
	----------------------------------
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
	
	
	
	REQUIREMENTS
	
	1) PHP 5
	2) WordPress 3.x (Tested in 3.2.1 and up, but will most likely work in 3.0 or even 2.7 when the Settings API was added)
	
	
	
	TODOS
	
	1) Add more types :)
	2) Add html5 style input boxes (as well as some setting to create html or xhtml type inputs)
		
		
	
	VERSION HISTORY
	
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
				throw new Exception("Undefined method or property ".$name);
			}
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
	protected $MenuSlug;
	
	private $__subpages = array();
	
	
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
		$this->__subpages[$subpage->Id] = &$subpage;
		
		return $subpage; 
		
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
	 * @param string $subpage
	 */
	public function output($subpage='') {
		// Output a subpage (call that objects output() function)
		if ($subpage && is_object($this->__subpages[$subpage])) {
			$this->__subpages[$subpage]->output();
		}
		// Output this object page
		else {
			$this->__output();
		}
	}
	
	/**
	 * The actual output of this objects page
	 */
	private function __output() {
		?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
			<h2><?php echo $this->Title ?><?php echo ($this->Title && $this->Subtitle ? " &mdash; ".$this->Subtitle:"") ?></h2>
			<?php if( isset($_GET['settings-updated']) ) : ?>
			    <div id="message" class="updated">
			        <p><strong><?php _e('Settings saved.') ?></strong></p>
			    </div>
			<?php endif; ?>
			<?php if ($this->SettingsPageDescription) : ?>
				<p><?php echo $this->SettingsPageDescription ?></p>
			<?php endif; ?>
			<?php if ( !empty($this->Sections )): ?>
				<form action="options.php" method="post">
				<?php settings_fields($this->Id); ?>
				<?php do_settings_sections($this->Id); ?>
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
				</p>
				</form>
			<?php endif; ?>
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
						case "dropdown":
						case "radio":
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
 * WP Settings Sub Page class
 * @see WPSettingsPage
 */
class WPSettingsSubPage extends WPSettingsPage {

	public $Id;
	
	protected $Title;
	protected $Subtitle;
	protected $MenuSlug;
	
	
	/**
	 * Create a WP Settings Page
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

		add_submenu_page($WPSettingsPage->MenuSlug, $page_title, $menu_title, $capability, $menu_slug, $function);
		
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
	 * @return WPSettingsField
	 */
	public function addField($field_id, $headline, $type, $input_name, $current_value='', $help_text='') {
		// Create dropdown
		if ($type == "dropdown") {
			$field = new WPSettingsFieldDropDown($this, $field_id, $headline, $type, $input_name, $current_value, $help_text);
		}
		// Create a radio
		elseIf ($type == "radio") {
			$field = new WPSettingsFieldRadio($this, $field_id, $headline, $type, $input_name, $current_value, $help_text);
		}
		// Create any other type
		else {
			$field = new WPSettingsField($this, $field_id, $headline, $type, $input_name, $current_value, $help_text);
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
			
			// Drop down
			case "dropdown":
				$this->__outputDropDownField();
			break;
			// Radio
			case "radio":
				$this->__outputRadioField();
			break;
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
				<input type="checkbox" name="<?php esc_attr_e( $this->InputName[$index] ) ?>" id="<?php esc_attr_e( $this->FieldId .'_'.$index ) ?>" value="1" <?php checked($this->CurrentValue[$index], 1) ?> />
			</div>
			<div style="clear: both;"></div>
			<?php
			
		}
	}
	
	/**
	 * Output field - Type "dropdown"
	 */
	private function __outputDropDownField() {
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
				<select name="<?php esc_attr_e( $this->InputName[$index] ) ?>" id="<?php esc_attr_e( $this->FieldId .'_'.$index ) ?>">
					<?php
					// Loop options through option groups
					if ($this->OptionGroups) {
						// First get options with specifically no group (default value most likely)
						$this->__outputDropDownOptions($index, false);
						// Then loop through the option groups
						foreach ($this->OptionGroups AS $optgroup_index => $optgroup) {
							?>
							<optgroup label="<?php esc_attr_e( $optgroup->Name )?>">
								<?php
								$this->__outputDropDownOptions($index, $optgroup);
								?>
							</optgroup>
							<?php
						}
					}
					// Loop all options without groups
					else {
						$this->__outputDropDownOptions($index);
					} 
					?>
				</select>
			</div>
			<div style="clear: both;"></div>
			<?php
			
		}
	}
	
	/**
	 * Output dropdown options. All or for a specific option group. If $optgroup is null, all options will be
	 * shown. If it's false, only options without group will be shown. If it's set only options of that group
	 * will be shown.
	 * @param int $field_index The current fields index
	 * @param WPSettingsDropDownOptionGroup|null|false $optgroup Optional
	 */
	private function __outputDropDownOptions($field_index, $optgroup=null) {
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
			<option value="<? esc_attr_e( $option->Value )?>" id="<?php esc_attr_e( $this->FieldId .'_'.$field_index.'_'.$option_index ) ?>" <?php selected($this->CurrentValue[$field_index], $option->Value) ?>><?php esc_attr_e( $option->Name ) ?></option>
			<?php
		}
	}
	
	/**
	 * Output field - Type "radio"
	 */
	private function __outputRadioField() {
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
				<?php
				foreach ($this->Options AS $option_index => $option) {
					?>
					<p>
						<input type="radio" name="<?php esc_attr_e( $this->InputName[$index] ) ?>" value="<? esc_attr_e( $option->Value )?>" id="<?php esc_attr_e( $this->FieldId .'_'.$index.'_'.$option_index ) ?>" <?php checked($this->CurrentValue[$index], $option->Value) ?>> <label for="<?php esc_attr_e( $this->FieldId .'_'.$index.'_'.$option_index ) ?>"><?php esc_attr_e( $option->Name ) ?></label>
					</p>
					<?php
				}
				?>
			</div>
			<div style="clear: both;"></div>
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




