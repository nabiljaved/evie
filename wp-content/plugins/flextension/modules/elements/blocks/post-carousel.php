<?php
/**
 * Post Carousel Block
 *
 * @package    Flextension
 * @subpackage Modules/Elements/Blocks
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the block.
 */
function flextension_block_post_carousel_register() {
	register_block_type_from_metadata(
		plugin_dir_path( __FILE__ ) . 'post-carousel',
		array(
			'render_callback' => 'flextension_block_post_carousel_render',
		)
	);
}

add_action( 'init', 'flextension_block_post_carousel_register' );

/**
 * Renders HTML content for the Post Carousel block.
 *
 * @since 1.0.7
 *
 * @param array $attributes The attributes list for the block.
 * @return string The HTML content for the block.
 */
function flextension_block_post_carousel_render( $attributes ) {

	$defaults = array(
		'blockId'         => '',
		'title'           => '',
		'className'       => '',
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
		'postClass'       => 'flext-slide',
		'template'        => plugin_dir_path( __FILE__ ) . 'post-carousel/templates/post-carousel',
	);

	$attributes = wp_parse_args( $attributes, $defaults );

	/**
	 * Filters the Post Carousel attributes.
	 *
	 * @since 1.1.0
	 *
	 * @param array $attributes The attributes list for the block.
	 */
	$attributes = apply_filters( 'flextension_post_carousel_block_attributes', $attributes );

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
		$args = array(
			'attributes'  => $attributes,
			'posts_query' => $posts_query,
		);
		ob_start();
		flextension_get_template( $attributes['template'], '', $args );
		$output = ob_get_clean();
	}

	return $output;
}

/**
 * Returns an array list of the block wrapper attributes.
 *
 * @since 1.1.0
 *
 * @param array $attributes An array list of the block attributes.
 * @return string[] An array of the block wrapper attributes.
 */
function flextension_block_post_carousel_wrapper_attributes( $attributes ) {
	$attrs = array();

	if ( ! empty( $attributes['blockId'] ) ) {
		$attrs['id'] = 'flext-block-' . $attributes['blockId'];
	}

	$attrs['class'] = flextension_class_names(
		array(
			'flext-block-post-carousel' => true,
			'has-post-number'           => true === $attributes['displayNumber'],
		)
	);

	/**
	 * Filters an array list of the block wrapper attributes.
	 *
	 * @since 1.1.0
	 *
	 * @param array $attrs An array list of the block wrapper attributes.
	 */
	return apply_filters( 'flextension_post_carousel_block_wrapper_attributes', $attrs );
}

/**
 * Returns an array list of the carousel attributes.
 *
 * @since 1.1.0
 *
 * @param array $attributes An array list of the block attributes.
 * @return string[] An array of the carousel attributes.
 */
function flextension_block_post_carousel_slider_attributes( $attributes ) {
	$attrs = array();

	$attrs['class'] = 'flext-post-carousel';

	if ( true === $attributes['navigation'] ) {
		$attrs['data-navigation'] = true;
	}

	if ( false !== $attributes['pagination'] ) {
		$attrs['data-pagination'] = $attributes['pagination'];
	}

	if ( ! empty( $attributes['columns'] ) ) {
		$attrs['data-slides-per-view'] = absint( $attributes['columns'] );
	}

	$attrs['data-space-between'] = 30;

	/**
	 * Filters an array list of the carousel attributes.
	 *
	 * @since 1.1.0
	 *
	 * @param array $attrs An array list of the carousel attributes.
	 */
	return apply_filters( 'flextension_post_carousel_block_carousel_attributes', $attrs );
}

/**
 * Displays HTML markup for the 'See more' link.
 *
 * @since 1.0.9
 *
 * @param array $attributes Arguments passed to the template.
 */
function flextension_block_post_carousel_link( $attributes ) {
	$url = flextension_block_get_more_link_url( $attributes );
	if ( ! empty( $url ) ) {
		echo '<a href="' . esc_attr( $url ) . '" class="see-more-link">';

		if ( ! empty( $attributes['linkText'] ) ) {
			echo esc_html( $attributes['linkText'] );
		}

		echo '</a>';
	}
}
