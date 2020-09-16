<?php
/* Since you want a menu object available on every page, I added it to the universal Timber context
via the functions.php file. You could also this in each PHP file if you find that too confusing. */

$context = Timber::get_context();

if ( is_home() ) {
  $post = new TimberPost(get_option( 'page_for_posts' ));
  $protected = post_password_required($post->ID);
  $context['protected_label'] = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
  $context['blog_posts'] = Timber::get_posts();
  $context['post'] = $post;
  $context['title_option'] = framework_page('title');
  $context['container_option'] = framework_page('small-container');
  $context['pagination'] = Timber::get_pagination();

  if ($protected) {
    Timber::render( 'single-protected.twig', $context );
  } else {
    Timber::render( 'blog-posts.twig', $context );
  }
} else {
  $context['post'] = Timber::get_posts();
  Timber::render('page.twig', $context);
}
