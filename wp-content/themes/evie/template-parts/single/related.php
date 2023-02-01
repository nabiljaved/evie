<?php
/**
 * Template part for displaying the related posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @see evie_related_posts()
 *
 * @var array $args Optional. Additional arguments passed to the template. See evie_related_posts() for all.
 */

evie_related_posts_carousel(
	array(
		'title'       => esc_html__( 'You might also like', 'evie' ),
		'postStyle'   => 'card',
		'hoverEffect' => 'none',
		'navigation'  => true,
		'align'       => 'full',
		'query'       => array(
			'numberOfItems' => 10,
		),
	)
);
