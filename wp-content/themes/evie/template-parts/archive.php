<?php
/**
 * Template part for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @see evie_posts()
 *
 * @var array $args Optional. Additional arguments passed to the template. See evie_posts() for all.
 */

/**
 * Hook: evie_before_content.
 *
 * @hooked evie_content_header
 */
do_action( 'evie_before_content' );

evie_posts( array( 'class' => 'main-posts' ) );

do_action( 'evie_after_content' );
