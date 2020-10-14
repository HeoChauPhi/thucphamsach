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
    wp_register_script('lib-slick', get_stylesheet_directory_uri() . '/dist/js/libs/slick.min.js', array('jquery'), FALSE, '1.8.0', TRUE);
    wp_enqueue_script('lib-slick');

    wp_register_script('Bootstrap', get_stylesheet_directory_uri() . '/dist/bootstrap/bootstrap.min.js', array('jquery'), FALSE, '4.5.0', TRUE);
    wp_enqueue_script('Bootstrap');

    wp_register_script('lib-matchHeight', get_stylesheet_directory_uri() . '/dist/js/libs/jquery.matchHeight-min.js', array('jquery'), FALSE, '0.7.0', TRUE);
    wp_enqueue_script('lib-matchHeight');

    wp_register_script('lib-fancybox', get_stylesheet_directory_uri() . '/dist/js/libs/jquery.fancybox.min.js', array('jquery'),  FALSE, '3.5.7', TRUE);
    wp_enqueue_script('lib-fancybox');

    //wp_register_script('lib-parallax', get_stylesheet_directory_uri() . '/dist/js/libs/parallax.min.js', array('jquery'),  FALSE, '1.5.0', TRUE);
    wp_register_script('lib-parallax', get_stylesheet_directory_uri() . '/dist/js/libs/simpleParallax.min.js', array('jquery'),  FALSE, '5.5.1', TRUE);
    wp_enqueue_script('lib-parallax');

    wp_register_script('lib-googlemap', get_stylesheet_directory_uri() . '/dist/js/libs/jquery.google-build-map.js', array('jquery'),  FALSE, '4.3.0', TRUE);
    wp_enqueue_script('lib-googlemap');

    wp_register_script('lib-isotope', get_stylesheet_directory_uri() . '/dist/js/libs/isotope.pkgd.min.js', array('jquery'),  FALSE, '3..0.6', TRUE);
    wp_enqueue_script('lib-isotope');

    wp_register_script('script', get_stylesheet_directory_uri() . '/dist/js/script.js', FALSE, '1.0.0', TRUE);
    wp_localize_script( 'script', 'themeAjax', array( 'ajaxurl' => admin_url('admin-ajax.php' )));
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
  // Blocks UI
  register_post_type( 'block_ui',
    array(
      'labels' => array(
        'name'               => _x( 'Blocks UI', 'post type general name', 'custom_block_ui' ),
        'singular_name'      => _x( 'Block UI', 'post type singular name', 'custom_block_ui' ),
        'menu_name'          => _x( 'Blocks UI', 'admin menu', 'custom_block_ui' ),
        'name_admin_bar'     => _x( 'Block UI', 'add new on admin bar', 'custom_block_ui' ),
        'add_new'            => _x( 'Add New', 'Block UI', 'custom_block_ui' ),
        'add_new_item'       => __( 'Add New Block UI', 'custom_block_ui' ),
        'new_item'           => __( 'New Block UI', 'custom_block_ui' ),
        'edit_item'          => __( 'Edit Block UI', 'custom_block_ui' ),
        'view_item'          => __( 'View Block UI', 'custom_block_ui' ),
        'all_items'          => __( 'All Blocks UI', 'custom_block_ui' ),
        'search_items'       => __( 'Search Blocks UI', 'custom_block_ui' ),
        'parent_item_colon'  => __( 'Parent Blocks UI:', 'custom_block_ui' ),
        'not_found'          => __( 'No Blocks UI found.', 'custom_block_ui' ),
        'not_found_in_trash' => __( 'No Blocks UI found in Trash.', 'custom_block_ui' )
      ),
      'description'           => __( 'Description.', 'custom_block_ui' ),
      'public'                => true,
      'publicly_queryable'    => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'query_var'             => true,
      'rewrite'               => array('slug' => 'block_ui'),
      'has_archive'           => true,
      'hierarchical'          => false,
      'menu_position'         => 20,
      'supports'              => array( 'title', 'editor' ),
      'capability_type'       => 'post',
    )
  );
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
function timber_set_product( $post ) {
  global $product;

  if ( is_woocommerce() ) {
    $product = wc_get_product( $post->ID );
  }
}

add_filter( 'woocommerce_reviews_title', 'ct_change_reviews_heading', 10, 3 );
function ct_change_reviews_heading( $heading, $count, $product ) {
  $heading = __("Review", "custom_theme") . ' (' . $count . ')';
  return $heading;
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_single_product_summary', 'ct_change_template_single_title', 5 );
function ct_change_template_single_title() {
  the_title( '<h2 class="product_title entry-title">', '</h2>' );
}


/*$exited_post = get_page_by_title('Trung tâm thương mại - Việt Nam tại Lô1/20 KĐT Ngã Năm Sân bay Cát Bi, Ngô Quyền, Hải Phòng', OBJECT, 'store_system');
if ( $exited_post ) {
  print_r($exited_post);
}*/

/*function get_posts_by_title($page_title, $post_type = false, $output = OBJECT ) {
  global $wpdb;

  //Handle specific post type?
  $post_type_where = $post_type ? 'AND post_type = %s' : '';

  //Query all columns so as not to use get_post()
  $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_title = %s $post_type_where AND post_status = 'publish'", $page_title, $post_type ? $post_type : '' ) );

  if ( $results ){
    $output = array();
    foreach ( $results as $post ){
      $output[] = $post;
    }
    return $output;
  }
  return null;
}

//This should get you an array of all posts with 'Foo Bar' as the title
print_r(get_posts_by_title('Số 9-11, Ngõ Thổ Quan, Quận Đống Đa, Thành Phố Hà Nội', 'store_system'));*/

/*function ct_vietnam_regions($city) {
  $vietnam_regions = array(
    'Northern Vietnam' => array(
      'Điện Biên', 'Hòa Bình', 'Lai Châu', 'Lào Cai', 'Sơn La', 'Yên Bái', 'Bắc Giang', 'Bắc Kạn', 'Cao Bằng', 'Hà Giang', 'Lạng Sơn', 'Phú Thọ', 'Quảng Ninh', 'Thái Nguyên', 'Tuyên Quang', 'Bắc Ninh', 'Hà Nam', 'Hà Nội', 'Hải Dương', 'Hải Phòng', 'Hưng Yên', 'Nam Định', 'Ninh Bình', 'Thái Bình', 'Vĩnh Phúc'
    ),
    'Central Vietnam' => array(
      'Hà Tĩnh', 'Nghệ An', 'Quảng Bình', 'Quảng Trị', 'Thanh Hóa', 'Thừa Thiên Huế', 'Bình Định', 'Bình Thuận', 'Đà Nẵng', 'Khánh Hòa', 'Ninh Thuận', 'Phú Yên', 'Quảng Nam', 'Quảng Ngãi', 'Đắk Lắk', 'Đắk Nông', 'Gia Lai', 'Kon Tum', 'Lâm Đồng'
    ),
    'Southern Vietnam' => array(
      'Bà Rịa - Vũng Tàu', 'Bình Dương', 'Bình Phước', 'Đồng Nai', 'Saigon', 'Tây Ninh', 'An Giang', 'Bến Tre', 'Bạc Liêu', 'Cà Mau', 'Cần Thơ', 'Đồng Tháp', 'Hậu Giang', 'Kiên Giang', 'Long An', 'Sóc Trăng', 'Tiền Giang', 'Trà Vinh', 'Vĩnh Long'
    )
  );
  
  $area = __('vietnam', 'custom_theme');

  foreach ($vietnam_regions as $key => $value) {
    if ( in_array($city, $value) ) {
      $area = __($key, 'custom_theme');
      break;
    }
  }

  return $area;
}

function ct_get_address($lat,$lng) {
  $url    = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&key=AIzaSyDXTzcKeymQWRfKZgZGf_N6WuCK1HTxduo';
  $json   = @file_get_contents($url);
  $data   = json_decode($json);
  $status = $data->status;
  
  if($status=="OK") {
   $address_components = $data->results[0]->address_components;

   $address_arr = array();
   $map_area = null;
   $map_city = null;
   $map_district = null;

   foreach ($address_components as $key) {
     if ($key->types[0] == 'administrative_area_level_1') {
       $map_area = $key->long_name;
     }

     if ($key->types[0] == 'locality' || $key->types[0] == 'administrative_area_level_2') {
       $map_city = $key->long_name;
     }

     if ($key->types[0] == 'route') {
       $map_district = $key->long_name;
     }
   }

   array_push($address_arr, $map_area, $map_city, $map_district);
   return $address_arr;
  } else {
   return false;
  }
}
echo ct_vietnam_regions('Sơn La') . '<br>';
echo ct_vietnam_regions('Thừa Thiên Huế') . '<br>';
echo ct_vietnam_regions('Bà Rịa - Vũng Tàu') . '<br>';
echo ct_vietnam_regions('Gia Lai') . '<br>';
echo ct_vietnam_regions('Heochaua') . '<br>';
$location_arr = ct_get_address('10.3678715787925', '107.076285285975');
print_r($location_arr);*/

//wp_delete_post(539);