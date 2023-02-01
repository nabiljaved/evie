<?php
/**
 * Template part for displaying a Posts block.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    Evie_XT
 * @subpackage Modules/Elements/Blocks/Posts/Templates
 * @version    1.1.0
 *
 * @var array $attributes The attributes list for the block.
 */

// This template part requires the block attributes.
if ( ! $attributes ) {
	return;
}

?>
<div <?php evie_attributes( $attributes['posts']['attrs'] ); ?>>

	<?php

	if ( isset( $attributes['posts']['query'] ) && $attributes['posts']['query']->have_posts() ) {
		evie_posts_loop( $attributes['posts'] );

		if ( ! empty( $attributes['pagination'] ) && 'none' !== $attributes['pagination'] ) {
			evie_posts_pagination( $attributes['pagination'], $attributes['posts']['query']->max_num_pages, $attributes['posts']['query']->query_vars['paged'] );
		} elseif ( ! empty( $attributes['link'] ) ) {
			evie_block_posts_more_link( $attributes );
		}

		wp_reset_postdata();

	} else {
		evie_content_template( 'none' );
	}

	?>

</div><!-- .evie-posts -->
