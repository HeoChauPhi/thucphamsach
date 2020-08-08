<?php
/*
**
** Enable Function
**
*/


// AJAX_DATA_ACTION action.
// add_action( 'wp_ajax_AJAX_DATA_ACTION', 'AJAX_DATA_ACTION_callback' );
// add_action( 'wp_ajax_nopriv_AJAX_DATA_ACTION', 'AJAX_DATA_ACTION_callback' );
function AJAX_DATA_ACTION_callback() {
  $values = $_REQUEST;
  //$content = do_shortcode ('[view_list name="'.$values['name'].'" post_type="news" per_page="2" custom_fields=""]');
  $content = $values['value'];
  $result = json_encode(array('markup' => $content));
  echo $result;
  wp_die();
}

// Theme support menu
add_theme_support( 'menus' );
add_action('init', 'ct_support_menu');
function ct_support_menu() {
  register_nav_menus(array (
    'main' => 'Main Menu'
  ));
}

// Theme support custom logo
add_action( 'after_setup_theme', 'ct_support_after_setup' );
function ct_support_after_setup() {
  add_theme_support( 'custom-logo', array(
    'flex-width' => true,
  ) );
}

// Theme support default fields
add_theme_support( 'post-thumbnails' );

add_action( 'init', 'ct_support_remove_default_field' );
function ct_support_remove_default_field() {
  $post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : '') ;

  $template_file = get_post_meta($post_id, '_wp_page_template', true);

  if( $template_file == 'page-templates/template-landing-page.php' || $template_file == 'page-templates/template-sidebar-left.php' || $template_file == 'page-templates/template-sidebar-right.php' || $template_file == 'page-templates/template-two-sidebar.php' ) { // the filename of the page template
    remove_post_type_support('page', 'editor');
  }

  if( $template_file == 'page-templates/template-landing-page.php' ) {
    remove_post_type_support( 'page', 'thumbnail' );
  }
}

// Unset URL from comment form
add_filter( 'comment_form_fields', 'ct_support_move_comment_form_below' );
function ct_support_move_comment_form_below( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}

// Set per page on each page
add_action( 'pre_get_posts',  'ct_support_set_posts_per_page'  );
function ct_support_set_posts_per_page( $query ) {
  global $wp_the_query;
  if ( (!is_admin()) && ( $query === $wp_the_query ) && ( $query->is_archive() ) ) {
    $query->set( 'posts_per_page', 1 );
  }

  if ( is_plugin_active('woocommerce') ) {
    if ( !is_woocommerce() ) {
      $query->set( 'posts_per_page', 1 );
    }
  }
  return $query;
}

add_filter( 'body_class', 'ct_support_body_class' );
function ct_support_body_class( $classes ) {
  global $post;
  if(is_page()){
    $post_slug = $post->post_name;
    $classes[] = 'page-'.$post_slug;
  }
  return $classes;
}

add_filter('upload_mimes', 'ct_support_files_type', 1, 1);
function ct_support_files_type($mime_types){
  $mime_types['svg'] = 'image/svg+xml';
  return $mime_types;
}

/**
 *
 * Get files from directory
 * @param type $custom_cat String slug of vocabulary.
 * @param type $showpost Int number post want show.
 *
 * @return type $loop_custom Object for post.
 *
 */
function hasfiles($folder_pattern, $file_name) {
  $file_list = array();

  //foreach (glob(get_template_directory() . "/templates/**/*.twig") as $filename) {
  foreach (glob($folder_pattern) as $file) {
    $file_arr = pathinfo($file);
    /*print_r($file_arr);
    echo '<br>';*/

    array_push($file_list, $file_arr['basename']);
  }

  if ( in_array($file_name, $file_list) ) {
    return $file_name;
  }
}

/*
**
** Support Widget Layout
**
*/


/* Add Dynamic Siderbar */
if (function_exists('register_sidebar')) {
  // Define Sidebar Left
  register_sidebar(array(
    'name' => __('Sidebar Left'),
    'description' => __('Description for this widget-area...'),
    'id' => 'sidebar-left',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
  // Define Sidebar Right
  register_sidebar(array(
    'name' => __('Sidebar Right'),
    'description' => __('Description for this widget-area...'),
    'id' => 'sidebar-right',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
  // Define Header block
  register_sidebar(array(
    'name' => __('Header top'),
    'description' => __('Description for this widget-area...'),
    'id' => 'header-top',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
  register_sidebar(array(
    'name' => __('Header block'),
    'description' => __('Description for this widget-area...'),
    'id' => 'header-block',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
  // Define Footer
  register_sidebar(array(
    'name' => __('Footer Top'),
    'description' => __('Description for this widget-area...'),
    'id' => 'footer-top',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
  register_sidebar(array(
    'name' => __('Footer Panel'),
    'description' => __('Description for this widget-area...'),
    'id' => 'footer-panel',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
  register_sidebar(array(
    'name' => __('Footer Bottom'),
    'description' => __('Description for this widget-area...'),
    'id' => 'footer-bottom',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
}

// Theme support get widget ID
//add_action('in_widget_form', 'ct_support_get_widget_id');
function ct_support_get_widget_id($widget_instance) {
  if ($widget_instance->number=="__i__"){
    echo "<p><strong>Widget ID is</strong>: Pls save the widget first!</p>";
  } else {
    echo "<p><strong>Widget ID is: </strong>" .$widget_instance->id. "</p>";
  }
}

// ACF Custom Widget arena
add_action( 'widgets_init', 'ct_support_create_ACF_custom_Widget' );
function ct_support_create_ACF_custom_Widget() {
  register_widget('ACF_custom_Widget');
}

class ACF_custom_Widget extends WP_Widget {
  public function __construct() {
    $widget_ops = array(
      'classname' => 'acf_custom_widget',
      'description' => __( 'ACF Custom widget.', 'custom_theme' ),
      'customize_selective_refresh' => true,
    );
    $control_ops = array( 'width' => 400, 'height' => 350 );
    parent::__construct( 'acf_custom_widget', __( 'ACF Custom Widget', 'custom_theme' ), $widget_ops, $control_ops );
  }

  public function widget( $args, $instance ) {
    //$title    = apply_filters( 'widget_title', $instance['title'] );
    echo $args['before_widget'];
    /*if ( $title ) {
      echo $args['before_title'] . $title . $args['after_title'];
    }*/

    $fc = get_field('widget_components', 'widget_' . $args['widget_id']);

    if ( !empty( $fc ) ) {
      foreach ($fc as $key => $field) {
        $layout = $field['acf_fc_layout'];
        $layout_template = $layout . '.twig';
        $field['component_id'] = $key + 1;

        switch ($layout) {
          case 'test':
            print_r($field);

            if (hasfiles(get_template_directory() . "/templates/**/*.twig", $layout_template)) {
              Timber::render($layout_template, $field);
            } else {
              echo 'Could not find a twig file for layout type: ' . $layout_template . '<br>';
            }
            break;

          default:
            //print_r($field);
            if (hasfiles(get_template_directory() . "/templates/**/*.twig", $layout_template)) {
              Timber::render($layout_template, $field);
            } else {
              echo 'Could not find a twig file for layout type: ' . $layout_template . '<br>';
            }
        }
      }
    }


    echo $args['after_widget'];
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    return $instance;
  }

  function form( $instance ) {
    $title      = esc_attr( $instance['title'] );
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <?php
  }
}

// Sidebar widget arena
/*add_action( 'widgets_init', 'ct_support_create_sidebar_Widget' );
function ct_support_create_sidebar_Widget() {
  register_widget('sidebar_Widget');
}

class sidebar_Widget extends WP_Widget {
  public function __construct() {
    $widget_ops = array(
      'classname' => 'sidebar_widget',
      'description' => __( 'Sidebar widget.', 'custom_theme' ),
      'customize_selective_refresh' => true,
    );
    $control_ops = array( 'width' => 400, 'height' => 350 );
    parent::__construct( 'sidebar_widget', __( 'Sidebar Widget', 'custom_theme' ), $widget_ops, $control_ops );
  }

  public function widget( $args, $instance ) {
    $title    = apply_filters( 'widget_title', $instance['title'] );
    echo $args['before_widget'];
    if ( $title ) {
      echo $args['before_title'] . $title . $args['after_title'];
    }
    echo $args['after_widget'];
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    return $instance;
  }

  function form( $instance ) {
    $title      = esc_attr( $instance['title'] );
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <?php
  }
}

// Header widget arena
add_action( 'widgets_init', 'ct_support_create_header_Widget' );
function ct_support_create_header_Widget() {
  register_widget('header_Widget');
}

class header_Widget extends WP_Widget {
  public function __construct() {
    $widget_ops = array(
      'classname' => 'header_widget',
      'description' => __( 'Custom widget.', 'custom_theme' ),
      'customize_selective_refresh' => true,
    );
    $control_ops = array( 'width' => 400, 'height' => 350 );
    parent::__construct( 'header_widget', __( 'Header Widget', 'custom_theme' ), $widget_ops, $control_ops );
  }

  public function widget( $args, $instance ) {
    $title    = apply_filters( 'widget_title', $instance['title'] );
    echo $args['before_widget'];
    if ( $title ) {
      echo $args['before_title'] . $title . $args['after_title'];
    }
    echo $args['after_widget'];
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    return $instance;
  }

  function form( $instance ) {
    $title      = esc_attr( $instance['title'] );
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <?php
  }
}

// Footer widget arena
add_action( 'widgets_init', 'ct_support_create_footer_Widget' );
function ct_support_create_footer_Widget() {
  register_widget('footer_Widget');
}

class footer_Widget extends WP_Widget {
  public function __construct() {
    $widget_ops = array(
      'classname' => 'footer_Widget',
      'description' => __( 'Custom widget.', 'custom_theme' ),
      'customize_selective_refresh' => true,
    );
    $control_ops = array( 'width' => 400, 'height' => 350 );
    parent::__construct( 'footer_Widget', __( 'Footer Widget', 'custom_theme' ), $widget_ops, $control_ops );
  }

  public function widget( $args, $instance ) {
    $title    = apply_filters( 'widget_title', $instance['title'] );
    echo $args['before_widget'];
    if ( $title ) {
      echo $args['before_title'] . $title . $args['after_title'];
    }
    echo $args['after_widget'];
  }

  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    return $instance;
  }

  function form( $instance ) {
    $title      = esc_attr( $instance['title'] );
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <?php
  }
}*/
