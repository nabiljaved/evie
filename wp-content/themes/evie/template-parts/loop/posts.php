<?php
/**
 * Template part for displaying the posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @var array $args Optional. Additional arguments passed to the template. See evie_posts() for all.
 */

?>
<div <?php evie_attributes( $args['attrs'] ); ?>>

	<?php

	if ( isset( $args['query'] ) && $args['query']->have_posts() ) {

		if ( true === $args['show_filter'] || true === $args['show_sortby'] ) {
			evie_posts_filters( $args );
		}

		evie_posts_loop( $args );

		if ( ! empty( $args['pagination'] ) && 'none' !== $args['pagination'] ) {
			evie_posts_pagination( $args['pagination'], $args['query']->max_num_pages, $args['query']->query_vars['paged'] );
		}

		wp_reset_postdata();

	} else {

		if ( true === $args['show_filter'] ) {
			evie_posts_filters( $args );
		}

		evie_content_template( 'none' );

	}

	?>

</div><!-- .evie-posts -->
