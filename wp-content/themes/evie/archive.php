<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

get_header();

get_template_part( 'template-parts/archive', get_post_type() );

get_footer();
