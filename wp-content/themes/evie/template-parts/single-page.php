<?php
/**
 * Template part for displaying a single page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

get_template_part( 'template-parts/page/content' );

// If comments are open or we have at least one comment, load up the comment template.
if ( ! is_front_page() && ( comments_open() || get_comments_number() ) ) {
	comments_template();
}
