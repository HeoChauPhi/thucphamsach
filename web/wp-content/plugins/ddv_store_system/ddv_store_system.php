<?php
/**
 * Plugin Name: Store Systerm
 * Plugin URI: http://heochaua.com/
 * Description: Import stores from csv file and display on google map
 * Version: 1.0
 * Author: HeoChauA
 * Author URI: http://heochaua.com/
 * License: GPLv2
 */

include_once('init/functions.php');
include_once('init/admin.php');

// Admin settings.
if(is_admin()) {
  //echo plugin_dir_path( __DIR__ );
  $settings = new DDVStoreSystem();

  //new DDVStoreSystemCustomMetaBox();

  if ( is_plugin_active('advanced-custom-fields-pro/acf.php') ) {
    add_action('acf/update_field_group', 'ddv_store_system_acf_json_update_point', 1, 1);
    add_filter('acf/settings/load_json', 'ddv_store_system_acf_json_load_point');
  } else {
    add_action('admin_notices', 'ddv_store_system_acf_deactivate');
  }
}

/*register_activation_hook( __FILE__, 'ddv_store_system_activate' );
function ddv_store_system_activate() {
  add_action( 'init', 'ddv_store_system_create_custom_post_types' );
}*/

register_deactivation_hook( __FILE__, 'ddv_store_system_deactivate' );
