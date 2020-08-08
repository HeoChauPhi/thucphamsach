<?php
/*
 *
 *
 * Registor Theme option
 *
 *
 */
/**
 * Admin settings page.
 */

class CTSettingsPage {
  /**
  * Holds the values to be used in the fields callbacks
  */
  private $options;

  /**
  * Start up
  */
  public function __construct() {
    add_action('admin_menu', array($this, 'ct_add_setting_page' ));
    add_action('admin_init', array($this, 'ct_page_init'));
  }

  /**
  * Add options page
  */
  public function ct_add_setting_page() {
    // This page will be under "Settings"
    add_options_page(
      __('Custom Theme Setting', 'custom_theme'),
      __('Theme Setting', 'custom_theme'),
      'manage_options',
      'ct-setting-admin',
      array($this, 'ct_reate_admin_page')
    );
  }

  /**
  * Options page callback
  */
  public function ct_reate_admin_page() {
    // Set class property
    $this->options = get_option('ct_board_settings');

    ?>
    <div class="wrap">
      <h1><?php echo __('Theme settings', 'custom_theme') ?></h1>
      <form method="post" action="options.php">
      <?php
        // This prints out all hidden setting fields
        settings_fields('ct_option_config');
        do_settings_sections('ct-setting-admin');
        submit_button();
      ?>
      </form>
    </div>
    <?php
  }

  /**
  * Register and add settings
  */
  public function ct_page_init() {
    register_setting(
      'ct_option_config', 
      'ct_board_settings',
      array( $this, 'ct_sanitize' )
    );

    // Setting ID
    add_settings_section(
      'ct_google_api', // ID
      __('Google API', 'custom_theme'), // Title
      array( $this, 'ct_google_print_section_info' ), // Callback
      'ct-setting-admin' // Page
    );

    add_settings_field(
      'ct_google_api_key',
      __('Google API Key', 'custom_theme'),
      array( $this, 'ct_form_textfield' ), // Callback
      'ct-setting-admin', // Page
      'ct_google_api',
      'ct_google_api_key'
    );
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function ct_sanitize( $input ) {
    $new_input = array();

    if( isset( $input['ct_google_api_key'] ) )
      $new_input['ct_google_api_key'] = sanitize_text_field( $input['ct_google_api_key'] );

    return $new_input;
  }

  /**
  * Print the Section text
  */
  public function ct_google_print_section_info() {
    echo __("", 'custom_theme');
  }

  /**
  * Get the settings option array and print one of its values
  */
  public function ct_form_checkbox($name) {
    $value = isset($this->options[$name]) ? esc_attr($this->options[$name]) : '';
    $checked = "";
    if($value){
      $checked = " checked='checked' ";
    }
    printf('<input type="checkbox" id="form-id-%s" name="ct_board_settings[%s]" value="1" %s/>', $name, $name, $checked);
  }

  public function ct_form_textfield($name) {
    $value = isset($this->options[$name]) ? esc_attr($this->options[$name]) : '';
    printf('<input type="text" size=60 id="form-id-%s" name="ct_board_settings[%s]" value="%s" />', $name, $name, $value);
  }

  public function ct_form_textarea($name) {
    $value = isset($this->options[$name]) ? esc_attr($this->options[$name]) : '';
    printf('<textarea cols="100%%" rows="3" type="textarea" id="form-id-%s" name="ct_board_settings[%s]">%s</textarea>', $name, $name, $value);
  }
}
