<?php
// Function for notice when ACF Pro deactivate
function ddv_store_system_acf_deactivate() {
  $class = 'notice notice-error';
  $message = __('Please install plugin <a href="//www.advancedcustomfields.com/pro/" target="_blank">Advance Custom Fields Pro</a>', 'ddv_store_system');

  printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
}

function ddv_store_system_acf_json_load_point( $paths ) {    
  // append path
  $paths[] = plugin_dir_path( __DIR__ ) . 'acf-json-files';
  // return
  return $paths;
}

function ddv_store_system_acf_json_update_point( $group ) {
  // list of field groups that should be saved to my-plugin/acf-json
  $groups = array('group_5f53f5d8b852c');

  if (in_array($group['key'], $groups)) {
    add_filter('acf/settings/save_json', function() {
      return plugin_dir_path( __DIR__ ) . 'acf-json-files';
    });
  }
}

// Function for Add custom post type
add_action( 'init', 'ddv_store_system_create_custom_post_types' );
function ddv_store_system_create_custom_post_types() {
  // Blocks UI
  register_post_type( 'store_system',
    array(
      'labels' => array(
        'name'               => _x( 'Stores System', 'post type general name', 'ddv_store_system' ),
        'singular_name'      => _x( 'Store System', 'post type singular name', 'ddv_store_system' ),
        'menu_name'          => _x( 'Stores System', 'admin menu', 'ddv_store_system' ),
        'name_admin_bar'     => _x( 'Store System', 'add new on admin bar', 'ddv_store_system' ),
        'add_new'            => _x( 'Add New', 'Block UI', 'ddv_store_system' ),
        'add_new_item'       => __( 'Add New Store System', 'ddv_store_system' ),
        'new_item'           => __( 'New Store System', 'ddv_store_system' ),
        'edit_item'          => __( 'Edit Store System', 'ddv_store_system' ),
        'view_item'          => __( 'View Store System', 'ddv_store_system' ),
        'all_items'          => __( 'All Stores System', 'ddv_store_system' ),
        'search_items'       => __( 'Search Stores System', 'ddv_store_system' ),
        'parent_item_colon'  => __( 'Parent Store System:', 'ddv_store_system' ),
        'not_found'          => __( 'No Stores System found.', 'ddv_store_system' ),
        'not_found_in_trash' => __( 'No Stores System found in Trash.', 'ddv_store_system' )
      ),
      'description'           => __( 'Description.', 'ddv_store_system' ),
      'public'                => true,
      'publicly_queryable'    => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'query_var'             => true,
      'rewrite'               => array('slug' => 'store_system'),
      'has_archive'           => true,
      'hierarchical'          => false,
      'menu_position'         => 20,
      'supports'              => array( 'title' ),
      'capability_type'       => 'post',
    )
  );
}

// Function for remove all Store system nodes
function ddv_store_system_deactivate() {
  $args = array (
    'post_type' => 'store_system',
    'nopaging' => true
  );

  $query = new WP_Query ($args);

  while ($query->have_posts ()) {
    $query->the_post();
    $id = get_the_ID();
    wp_delete_post ($id, true);
  }
  wp_reset_postdata();
}

// Function for register admin style
add_action('admin_init', 'ddv_store_system_admin_styles');
function ddv_store_system_admin_styles() {
  wp_register_style('ddv_store_system_style', plugin_dir_url( __DIR__ ) . 'access/css/ddv-store-system-styles.css');
  wp_enqueue_style('ddv_store_system_style');
}

// Function for register admin script
add_action('admin_enqueue_scripts', 'ddv_store_system_admin_script');
function ddv_store_system_admin_script() {
  wp_enqueue_script('ddv_store_system_admin_script', plugin_dir_url( __DIR__ ) . 'access/js/ddv-store-system-admin-script.js', array('jquery'), '1.0.0', true);
}

// Function for register script
// add_action('wp_print_scripts', 'ddv_store_system_script');
function ddv_store_system_script() {
  wp_enqueue_script('ddv_store_system_script', plugin_dir_url( __DIR__ ) . 'access/js/ddv-store-system-script.js', array('jquery'), '1.0.0', true);
}

// Function get Store system by title
function ddv_store_system_get_posts_by_title($page_title, $post_type = false, $output = OBJECT ) {
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

function ddv_store_system_get_address($lat,$lng) {
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

/*function ddv_store_system_vietnam_regions($city) {
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
}*/

/*======================================================
 * 
 * Ajax call data
 * 
 ======================================================*/
 add_action( 'wp_ajax_storesystemimport', 'ddv_store_system_storesystemimport' );
function ddv_store_system_storesystemimport() {
  global $wpdb; // this is how you get access to the database

  $values = $_REQUEST;

  $result_arr = array();
  $store_imported = '';
  $message = '';

  $store_duplicate = $values['store_duplicate'];

  // Open the file for reading
  if (isset($_FILES['store_system']) && is_uploaded_file($_FILES['store_system']['tmp_name'])) {
    if (($h = fopen($_FILES['store_system']['tmp_name'], "r")) !== FALSE) {
      $row = 0;
      // Convert each line into the local $data variable
      while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
        array_push($result_arr, $data);
        $row++;
      }

      // Close the file
      fclose($h);
    } else {
      $message = __('file can opend', 'ddv_store_system');
    }

    if ( $result_arr ) {
      $result_arr[0] = array_merge($result_arr[0], ['map_area', 'map_city', 'map_district']);

      foreach ($result_arr as $key => $value) {
        $my_post = array(
          'ID'            => '',
          'post_title'    => $value[4],
          'post_status'   => 'publish',
          'post_type'     => 'store_system'
        );

        if ( $key != 0 ) {
          // Get ID for posts
          $exited_posts = ddv_store_system_get_posts_by_title($value[4], 'store_system');

          if ( $exited_posts && ($store_duplicate == 'update') ) {
            foreach ($exited_posts as $exited_post) {
                wp_delete_post($exited_post->ID);
              }
          }

          $post_id = wp_insert_post($my_post);

          // Get location by lat, lon
          $lat = $value[0];
          $lng = $value[1];
          $location_arr = ddv_store_system_get_address($lat, $lng);

          $value = array_merge($value, $location_arr);

          for ($i=0; $i < count($value); $i++) {
            update_field($result_arr[0][$i], $value[$i], $post_id);
          }

          $store_imported .= '<tr><td>' . $value[4] . '</td></tr>';
        }
      }
      
      $message = sprintf(__('Imported and updated %s store', 'ddv_store_system'), (int)$row - 1);
    }
  }

  $result = json_encode(array(
    'stores' => $store_imported,
    'message' => $message
  ));
  echo $result;

  wp_die(); // this is required to terminate immediately and return a proper response
}

// Create function for return store system
function ddv_store_system_render() {
  //$result = array();

  $args = array(
    'post_type' => 'store_system',
    'post_status' => 'publish',
    'posts_per_page' => -1
  );

  $the_query = get_posts($args);

  return $the_query;
}
