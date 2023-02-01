<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Evie
 * @version 1.0.0
 */

get_header();

while ( have_posts() ) {

	the_post();

	get_template_part( 'template-parts/single', get_post_type() );

}

get_footer();
