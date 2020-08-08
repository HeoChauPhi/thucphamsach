<?php

/*
Plugin Name: Advanced Custom Fields: Crelly Slider
Plugin URI: http://heochaua.com/
Description: Custom ACF Fiels get Crelly Slider
Version: 1.0.0
Author: HeoChauA
Author URI: http://heochaua.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

function custom_acf_get_crellyslider() {
  global $wpdb;

  if ( is_plugin_active('crelly-slider/crellyslider.php') ) {
    $result = array(
      'none' => __('None','acf-crelly-slider'),
    );
    $db_result = $wpdb->get_results( "SELECT * FROM wp_crellyslider_sliders");

    foreach ($db_result as $value) {
      //array_push($result, $value->alias);
      $result[$value->alias] = $value->name;
    }

    return $result;
  }

  /*$result = array(
    'none' => __('None','acf-crelly-slider'),
    'test_slider' => 'Test Slider',

  );
  return $result;*/
}

// check if class already exists
if( !class_exists('custom_acf_plugin_crelly_slider') ) :

class custom_acf_plugin_crelly_slider {
  
  // vars
  var $settings;
  
  
  /*
  *  __construct
  *
  *  This function will setup the class functionality
  *
  *  @type  function
  *  @date  17/02/2016
  *  @since  1.0.0
  *
  *  @param  void
  *  @return  void
  */
  
  function __construct() {
    
    // settings
    // - these will be passed into the field class.
    $this->settings = array(
      'version'  => '1.0.0',
      'url'    => plugin_dir_url( __FILE__ ),
      'path'    => plugin_dir_path( __FILE__ )
    );
    
    
    // include field
    add_action('acf/include_field_types',   array($this, 'include_field')); // v5
    add_action('acf/register_fields',     array($this, 'include_field')); // v4
  }
  
  
  /*
  *  include_field
  *
  *  This function will include the field type class
  *
  *  @type  function
  *  @date  17/02/2016
  *  @since  1.0.0
  *
  *  @param  $version (int) major ACF version. Defaults to false
  *  @return  void
  */
  
  function include_field( $version = false ) {
    
    // support empty $version
    if( !$version ) $version = 4;
    
    
    // load textdomain
    load_plugin_textdomain( 'acf-crelly-slider', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 
    
    
    // include
    include_once('fields/class-custom-acf-field-crelly-slider-v' . $version . '.php');
  }
  
}


// initialize
new custom_acf_plugin_crelly_slider();


// class_exists check
endif;
