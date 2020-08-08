<?php
/**
 * Template Name: Sidebar Left
 *
 * @package WordPress
 * @subpackage FFW
 * @since FFW 1.0
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

Timber::render( 'template-sidebar-left.twig', $context );
