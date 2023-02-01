<?php
/**
 * Featured Posts Block
 *
 * @package    Evie_XT
 * @subpackage Modules/Elements/Blocks
 * @version    1.0.0
 */

/**
 * Registers the block.
 */
function evie_block_featured_posts_register() {
	register_block_type_from_metadata(
		plugin_dir_path( __FILE__ ) . 'featured-posts',
		array(
			'render_callback' => 'evie_block_featured_posts_render',
		)
	);
}

add_action( 'init', 'evie_block_featured_posts_register' );

/**
 * Renders the Featured Posts block.
 *
 * @param array $attributes The attributes list for the block.
 * @return string The HTML content for the block.
 */
function evie_block_featured_posts_render( $attributes ) {
	/**
	 * This is an exclusive block and it only works with Evie theme.
	 *
	 * @since 1.0.4
	 */
	if ( ! evie_can_load_blocks() ) {
		return '';
	}

	$query_vars = wp_parse_args(
		array(
			'no_found_rows'       => true,
			'ignore_sticky_posts' => 1,
		),
		flextension_block_get_query_vars( $attributes['query'] )
	);

	if ( ! empty( $attributes['query_vars'] ) ) {
		$query_vars = wp_parse_args(
			$attributes['query_vars'],
			$query_vars
		);
	}

	$attributes['query_vars'] = $query_vars;

	$post_type = '';

	if ( 'post' !== $query_vars['post_type'] ) {
		$post_type = $query_vars['post_type'];
	}

	if ( ! post_type_exists( $post_type ) ) {
		$post_type = '';
	}

	ob_start();

	evie_block_template( 'featured-posts', $attributes['type'], $post_type, $attributes );

	$output = ob_get_clean();

	return $output;
}

/**
 * Prints out a 'Read more' link.
 *
 * @param int|WP_Post $post    Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string      $url     Link URL.
 * @param string      $text    Link text. Default 'Read more'.
 * @param string      $tooltip Link tooltip. Default 'Continue reading %s'.
 */
function evie_block_featured_posts_more_link( $post = 0, $url = '', $text = '', $tooltip = '' ) {

	if ( empty( $text ) ) {
		$text = esc_html__( 'Read more', 'evie-xt' );
	}

	if ( empty( $url ) ) {
		$url = get_permalink( $post );
	}

	if ( empty( $tooltip ) ) {
		$tooltip = sprintf(
			/* translators: %s: Post title. */
			esc_html__( 'Continue reading %s', 'evie-xt' ),
			the_title_attribute(
				array(
					'before' => '"',
					'after'  => '"',
					'echo'   => false,
					'post'   => $post,
				)
			)
		);
	}

	echo sprintf(
		'<a href="%1$s" title="%2$s" class="slide-more-link">
			<span>%3$s</span>
			<i class="more-button-icon">
			</i>
		</a>',
		esc_url( $url ),
		esc_attr( $tooltip ),
		esc_html( $text )
	);
}
