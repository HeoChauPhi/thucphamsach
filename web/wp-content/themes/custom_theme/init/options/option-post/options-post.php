<?php
//add_action( 'cmb2_admin_init', 'ct_sidebar_post_metaboxes' );
function ct_sidebar_post_metaboxes() {

  $prefix = '_cmb2_';

  $cmb = new_cmb2_box( array(
    'id'            => 'post_options',
    'title'         => __( 'Post option', 'cmb2' ),
    'object_types'  => array('post'), // Post type or any post type use: ct_list_posttype()
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
  ) );

  // login required
  /*$cmb->add_field( array(
    'name'       => __( ' login required', 'cmb2' ),
    'desc'       => __( 'Check it if you want users must log in to view the content', 'cmb2' ),
    'id'         => $prefix . 'roleloginrequired',
    'type'       => 'checkbox',
  ) );*/
}

function framework_post($name = '') {
  global $post;
  $value = get_post_meta( $post->ID, '_cmb2_' . $name, true );
  return $value;
}
