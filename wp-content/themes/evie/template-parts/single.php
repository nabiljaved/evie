<?php
/**
 * Template part for displaying a single post
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

get_template_part( 'template-parts/post/content', evie_get_post_layout() );

// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) {
	comments_template();
}

evie_related_posts();

evie_post_navigation();
