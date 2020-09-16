<?php
// View list users
add_shortcode( 'ddv_store_system', 'ct_ddv_store_system_render' );
function ct_ddv_store_system_render($attrs) {
  extract(shortcode_atts (array(
  ), $attrs));

  ob_start();

    $context = Timber::get_context();
    $map_areas = array();
    $map_citys = array();
    $map_districts = array();
    $types = array();

    if ( function_exists('ddv_store_system_render') ) {
      $context['stores'] = Timber::get_posts(ddv_store_system_render());

      foreach ($context['stores'] as $post) {
        $map_areas[] = get_post_meta( $post->ID, 'map_area', true );
        $map_citys[] = get_post_meta( $post->ID, 'map_city', true );
        $map_districts[] = get_post_meta( $post->ID, 'map_district', true );
        $types[] = get_post_meta( $post->ID, 'type', true );
      }

      $context['map_areas'] = array_unique(array_filter($map_areas));
      $context['map_citys'] = array_unique(array_filter($map_citys));
      $context['map_districts'] = array_unique(array_filter($map_districts));
      $context['types'] = array_unique(array_filter($types));

      $context['total_stores'] = count($context['stores']);
    }

    if (hasfiles(get_template_directory() . "/templates/shortcode/*.twig", 'ddv_store_system.twig')) {
      Timber::render('ddv_store_system.twig', $context);
    } else {
      echo 'Could not find a twig file for layout type: /templates/shortcode/ddv_store_system.twig<br>';
    }

    $content = ob_get_contents();
  ob_end_clean();
  return $content;
  wp_reset_postdata();
}
