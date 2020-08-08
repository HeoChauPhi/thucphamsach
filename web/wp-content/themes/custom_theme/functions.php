<?php
/*
 *  Author: Framework | @Framework
 *  URL: wordfly.com | @wordfly
 *  Custom functions, support, custom post types and more.
 */

// Theme setting
require_once('init/theme-init.php');
require_once('init/theme-shortcode.php');
require_once('init/options/option.php');

/* Custom for theme */
//echo get_stylesheet_directory_uri();

if(!is_admin()) {
  // Add scripts
  function ct_libs_scripts() {
    wp_register_script('lib-slick', get_stylesheet_directory_uri() . '/dist/js/libs/slick.min.js', array('jquery'), FALSE, '1.8.1', TRUE);
    wp_enqueue_script('lib-slick');

    wp_register_script('Bootstrap', get_stylesheet_directory_uri() . '/dist/bootstrap/bootstrap.min.js', array('jquery'), FALSE, '4.5.0', TRUE);
    wp_enqueue_script('Bootstrap');

    wp_register_script('lib-matchHeight', get_stylesheet_directory_uri() . '/dist/js/libs/jquery.matchHeight-min.js', array('jquery'), FALSE, '0.7.0', TRUE);
    wp_enqueue_script('lib-matchHeight');

    wp_register_script('lib-fancybox', get_stylesheet_directory_uri() . '/dist/js/libs/jquery.fancybox.min.js', array('jquery'),  FALSE, '3.5.7', TRUE);
    wp_enqueue_script('lib-fancybox');

    wp_register_script('lib-parallax', get_stylesheet_directory_uri() . '/dist/js/libs/parallax.min.js', array('jquery'),  FALSE, '1.5.0', TRUE);
    wp_enqueue_script('lib-parallax');

    wp_register_script('script', get_stylesheet_directory_uri() . '/dist/js/script.js', FALSE, '1.0.0', TRUE);
    wp_localize_script( 'script', 'paginationAjax', array( 'ajaxurl' => admin_url('admin-ajax.php' )));
    wp_enqueue_script('script');
  }
  add_action('wp_print_scripts', 'ct_libs_scripts');

  // Add stylesheet
  function ct_styles() {
    wp_register_style('bootstrap', get_stylesheet_directory_uri() . '/dist/bootstrap/bootstrap.min.css', array(), '4.5.0', 'all');
    wp_enqueue_style('bootstrap');

    wp_register_style('theme-style', get_stylesheet_directory_uri() . '/dist/css/styles.css', array(), '1.0', 'all');
    wp_enqueue_style('theme-style');

    wp_register_style('user-custom', get_stylesheet_directory_uri() . '/dist/css/user-custom.css', array(), '1.0', 'all');
    wp_enqueue_style('user-custom');
  }
  add_action('wp_enqueue_scripts', 'ct_styles');
}

// Add admin script
function ct_admin_scripts() {
 /* wp_register_script('lib-moment', get_stylesheet_directory_uri() . '/dist/js/admin-libs/moment.js', array('jquery'), '2.13.0');
  wp_enqueue_script('lib-moment');

  wp_register_script('lib-datetimepicker', get_stylesheet_directory_uri() . '/dist/js/admin-libs/bootstrap-datetimepicker.min.js', array('jquery'), '4.17.37');
  wp_enqueue_script('lib-datetimepicker');*/

  wp_enqueue_script('admin-script', get_stylesheet_directory_uri() . '/dist/js/admin-script.js', array('jquery'), '1.0.0', true);
  //wp_enqueue_script('admin-script');
}
add_action('admin_enqueue_scripts', 'ct_admin_scripts');

// Add admin script
function ct_admin_styles() {
  wp_register_style('admin-style', get_stylesheet_directory_uri() . '/dist/css/admin.css', array(), '1.0', 'all');
  wp_enqueue_style('admin-style');
}
add_action('admin_init', 'ct_admin_styles');

/*
 *
 * Add custom post type
 *
 */
function ct_create_custom_post_types() {
  // Hotel
  register_post_type( '{CUSTOM-POST-TYPE}', array(
    'labels' => array(
      'name'               => _x( '{CUSTOM-POST-TYPE}s', 'post type general name', 'custom_theme' ),
      'singular_name'      => _x( '{CUSTOM-POST-TYPE}', 'post type singular name', 'custom_theme' ),
      'menu_name'          => _x( '{CUSTOM-POST-TYPE}s', 'admin menu', 'custom_theme' ),
      'name_admin_bar'     => _x( '{CUSTOM-POST-TYPE}', 'add new on admin bar', 'custom_theme' ),
      'add_new'            => _x( 'Add New', '{CUSTOM-POST-TYPE}', 'custom_theme' ),
      'add_new_item'       => __( 'Add New {CUSTOM-POST-TYPE}', 'custom_theme' ),
      'new_item'           => __( 'New {CUSTOM-POST-TYPE}', 'custom_theme' ),
      'edit_item'          => __( 'Edit {CUSTOM-POST-TYPE}', 'custom_theme' ),
      'view_item'          => __( 'View {CUSTOM-POST-TYPE}', 'custom_theme' ),
      'all_items'          => __( 'All {CUSTOM-POST-TYPE}s', 'custom_theme' ),
      'search_items'       => __( 'Search {CUSTOM-POST-TYPE}s', 'custom_theme' ),
      'parent_item_colon'  => __( 'Parent {CUSTOM-POST-TYPE}s:', 'custom_theme' ),
      'not_found'          => __( 'No {CUSTOM-POST-TYPE}s found.', 'custom_theme' ),
      'not_found_in_trash' => __( 'No {CUSTOM-POST-TYPE}s found in Trash.', 'custom_theme' )
    ),
    'description'           => __( 'Description.', 'custom_theme' ),
    'public'                => true,
    'publicly_queryable'    => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'query_var'             => true,
    'rewrite'               => array('slug' => '{CUSTOM-POST-TYPE}'),
    'has_archive'           => true,
    'hierarchical'          => false,
    'menu_position'         => 28,
    'supports'              => array( 'title', 'editor' ),
    'capabilities'          => array(
      // Meta capabilities

      'edit_post'               => "edit_{CUSTOM-POST-TYPE}",
      'read_post'               => "read_{CUSTOM-POST-TYPE}",
      'delete_post'             => "delete_{CUSTOM-POST-TYPE}",

      'edit_posts'              => "edit_{CUSTOM-POST-TYPE}s",
      'edit_others_posts'       => "edit_others_{CUSTOM-POST-TYPE}s",
      'publish_posts'           => "publish_{CUSTOM-POST-TYPE}s",
      'read_private_posts'      => "read_private_{CUSTOM-POST-TYPE}s",

      // Primitive capabilities used within map_meta_cap():

      'read'                    => "read",
      'delete_posts'            => "delete_{CUSTOM-POST-TYPE}s",
      'delete_private_posts'    => "delete_private_{CUSTOM-POST-TYPE}s",
      'delete_published_posts'  => "delete_published_{CUSTOM-POST-TYPE}s",
      'delete_others_posts'     => "delete_others_{CUSTOM-POST-TYPE}s",
      'edit_private_posts'      => "edit_private_{CUSTOM-POST-TYPE}s",
      'edit_published_posts'    => "edit_published_{CUSTOM-POST-TYPE}s",
      'create_posts'            => "edit_{CUSTOM-POST-TYPE}s",
    ),
    // as pointed out by iEmanuele, adding map_meta_cap will map the meta correctly 
    'map_meta_cap' => true
  ));
}
//add_action( 'init', 'ct_create_custom_post_types' );

/*
 *
 * Custom Taxonomy
 *
 */
function ct_create_custom_taxonomy() {
  $labels_subsite = array(
    'name' => __('{CUSTOM-TAXONOMIES}', 'custom_theme'),
    'singular' => __('{CUSTOM-TAXONOMY}', 'custom_theme'),
    'menu_name' => __('{CUSTOM-TAXONOMY}', 'custom_theme')
  );
  $args_subsite = array(
    'labels'                     => $labels_subsite,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
    'show_in_quick_edit'         => false,
  );
  register_taxonomy('{CUSTOM_TAXONOMY}', array('{CUSTOM-POST-TYPE}'), $args_subsite);
}
//add_action( 'init', 'ct_create_custom_taxonomy', 0 );

/*
 *
 *
 * Custom for theme
 *
 */
// Remove Editor Field for Landing page
function ct_remove_editor() {
  remove_post_type_support('page', 'editor');
  //remove_post_type_support('post', 'editor');
}
//add_action('admin_init', 'ct_remove_editor');

// Add google API Key
add_action('acf/init', function() {
  $theme_options = get_option('ct_board_settings');
  $google_api_key = $theme_options['ct_google_api_key'];
  acf_update_setting('google_api_key', $google_api_key);
});

// Change Post to New
add_action( 'init', 'ct_change_post_object_to_new' );
// Change dashboard Posts to News
function ct_change_post_object_to_new() {
  $get_post_type = get_post_type_object('post');
  $labels = $get_post_type->labels;
    $labels->name = __('News', 'custom_theme');
    $labels->singular_name = __('News', 'custom_theme');
    $labels->add_new = __('Add News', 'custom_theme');
    $labels->add_new_item = __('Add News', 'custom_theme');
    $labels->edit_item = __('Edit News', 'custom_theme');
    $labels->new_item = __('News', 'custom_theme');
    $labels->view_item = __('View News', 'custom_theme');
    $labels->search_items = __('Search News', 'custom_theme');
    $labels->not_found = __('No News found', 'custom_theme');
    $labels->not_found_in_trash = __('No News found in Trash', 'custom_theme');
    $labels->all_items = __('All News', 'custom_theme');
    $labels->menu_name = __('News', 'custom_theme');
    $labels->name_admin_bar = __('News', 'custom_theme');
}

// Custom for Woocommerce
