<?php
/**
 * Post Carousel Block
 *
 * @package    Evie_XT
 * @subpackage Modules/Elements/Blocks
 * @version    1.0.0
 */

/**
 * Registers the block.
 */
function evie_block_post_carousel_register() {
	register_block_type_from_metadata(
		plugin_dir_path( __FILE__ ) . 'post-carousel',
		array(
			'render_callback' => 'evie_block_post_carousel_render',
		)
	);
}

add_action( 'init', 'evie_block_post_carousel_register' );

/**
 * Renders a Post Carousel block.
 *
 * @param array $attributes The attributes list for the block.
 * @return string The HTML content for the block.
 */
function evie_block_post_carousel_render( $attributes ) {
	/**
	 * This is an exclusive block and it only works with Evie theme.
	 *
	 * @since 1.0.4
	 */
	if ( ! evie_can_load_blocks() ) {
		return '';
	}

	$defaults = array(
		'blockId'         => '',
		'className'       => '',
		'postStyle'       => '',
		'hoverEffect'     => '',
		'columns'         => 5,
		'displayNumber'   => false,
		'displayTitle'    => true,
		'displayCategory' => true,
		'displayAuthor'   => true,
		'displayDate'     => true,
		'displayButtons'  => true,
		'navigation'      => false,
		'pagination'      => false,
		'query'           => array(),
		'link'            => '',
		'linkText'        => '',
		'linkURL'         => '',
		'isEditMode'      => false,
	);

	$attributes = wp_parse_args( $attributes, $defaults );

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

	$output = '';

	$posts_query = new WP_Query( $attributes['query_vars'] );

	if ( $posts_query->have_posts() ) {

		$attributes['posts_query'] = $posts_query;

		$post_type = '';

		if ( 'post' !== $attributes['query_vars']['post_type'] ) {
			$post_type = $attributes['query_vars']['post_type'];
		}

		if ( ! $attributes['isEditMode'] ) {
			$classes = array( 'evie-block-post-carousel' );

			if ( ! empty( $attributes['title'] ) ) {
				$classes[] = 'has-block-title';
			}

			if ( true === $attributes['displayNumber'] ) {
				$classes[] = 'has-post-number';
			}

			$attrs = array();

			if ( ! empty( $attributes['blockId'] ) ) {
				$attrs['id'] = 'evie-block-' . $attributes['blockId'];
			}

			$attrs['class'] = implode( ' ', $classes );

			$output = '<div ' . get_block_wrapper_attributes( $attrs ) . '>';
		}

		if ( ! post_type_exists( $post_type ) ) {
			$post_type = '';
		}

		ob_start();

		evie_block_template( 'post-carousel', 'post-carousel', $post_type, $attributes );

		$output .= ob_get_clean();

		if ( ! $attributes['isEditMode'] ) {
			$output .= '</div>';
		}

		wp_reset_postdata();

	}

	return $output;
}

/**
 * Sanitizes content for allowed HTML tags for the block content.
 *
 * @param string $content The content to filter.
 */
function evie_block_post_carousel_esc_content( $content ) {
	// The content has been filtered and sanitized by WordPress block editor.
	echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Renders the post content for the Post Carousel block.
 *
 * @param array    $args       Arguments passed to the template. See evie_posts_args() for additional options.
 * @param array    $attributes The attributes list for the block.
 * @param WP_Query $query      Current query object.
 */
function evie_block_post_carousel_content( $args, $attributes, $query ) {
	// Append the 'See more' link into the last item.
	if ( ! empty( $attributes['link'] ) && ( $query->current_post + 1 ) === $query->post_count ) {
		$args['attributes'] = $attributes;
		$args['post_class'] = array_merge( $args['post_class'], array( 'has-see-more-link' ) );
		add_action( 'evie_content', 'evie_block_post_carousel_more_link' );
		evie_content_template( 'grid', '', $args );
		remove_action( 'evie_content', 'evie_block_post_carousel_more_link' );
	} else {
		evie_content_template( 'grid', '', $args );
	}
}

/**
 * Prints out the See more link for the Post Carousel block.
 *
 * @param array $args Arguments passed to the template. See evie_posts_args() for additional options.
 */
function evie_block_post_carousel_more_link( $args = array() ) {
	if ( isset( $args['attributes'] ) && ! empty( $args['attributes'] ) ) {
		$attributes = $args['attributes'];

		$url = flextension_block_get_more_link_url( $attributes );
		if ( ! empty( $url ) ) {

			echo '<div class="post-carousel-see-more-link has-scheme-dark">
					<nav class="navigation posts-navigation" role="navigation">
						<a href="' . esc_attr( $url ) . '" class="see-more-link">
							<span>';

			if ( ! empty( $attributes['linkText'] ) ) {
				echo esc_html( $attributes['linkText'] );
			}

			echo '			</span>
							<i></i>
						</a>
					</nav>
				</div>';

		}
	}
}
