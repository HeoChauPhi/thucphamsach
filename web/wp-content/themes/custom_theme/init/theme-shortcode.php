<?php
// View list users
//add_shortcode( 'view_list_user', 'ct_view_list_user' );
function ct_view_list_user($attrs) {
  extract(shortcode_atts (array(
    'user_filter' => ''
  ), $attrs));

  ob_start();

    $context = Timber::get_context();

    try {
    Timber::render('[TEMPLATE].twig', $context );
    } catch (Exception $e) {
      echo 'Could not find a twig file for Shortcode Name: ' . $name;
    }

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}
