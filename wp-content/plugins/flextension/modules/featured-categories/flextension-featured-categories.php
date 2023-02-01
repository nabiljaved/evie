<?php
/**
 * Featured Categories
 *
 * @package    Flextension
 * @subpackage Modules/Featured_Categories
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the meta key for the term image.
 *
 * @return string The meta key for the term image.
 */
function flextension_term_thumbnail_meta_key() {
	/**
	 * Filters the meta key for the term image.
	 *
	 * @param string The meta key for the term image.
	 */
	return apply_filters( 'flextension_term_thumbnail_meta_key', 'thumbnail_id' );
}

/**
 * Returns an array list of the featured taxonomies.
 *
 * @return array An array list of the featured taxonomies.
 */
function flextension_featured_taxonomies() {
	$settings = flextension_get_theme_support( 'featured-categories', true );
	if ( true === $settings ) {
		$settings = array( 'category' );
	}
	$taxonomies = array();
	if ( is_array( $settings ) && ! empty( $settings ) ) {
		foreach ( $settings as $taxonomy ) {
			$taxonomies[] = $taxonomy;
		}
	}
	return $taxonomies;
}

/**
 * Retrieves image ID for the term.
 *
 * @param int $term_id The term ID.
 * @return int The image ID of the term.
 */
function flextension_get_term_thumbnail_id( $term_id = '' ) {

	if ( ! $term_id ) {
		$term = get_queried_object();
		if ( $term ) {
			$term_id = $term->term_id;
		}
	}

	if ( ! $term_id ) {
		return 0;
	}

	$image_id = get_term_meta( $term_id, flextension_term_thumbnail_meta_key(), true );

	/**
	 * Filters the image ID for the term.
	 *
	 * @param int $image_id The image ID for the term.
	 * @param int $term_id  The term ID.
	 */
	return apply_filters( 'flextension_term_thumbnail_id', $image_id, $term_id );
}

/**
 * Returns the featured image for the term.
 *
 * @param int          $term_id The term ID.
 * @param string|array $size    The post thumbnail size. Image size or array of width and height
 *                              values (in that order). Default 'post-thumbnail'.
 * @return string HTML img element or empty string on failure.
 */
function flextension_get_term_thumbnail( $term_id = '', $size = 'post-thumbnail' ) {
	if ( ! empty( $term_id ) ) {
		$image_id = flextension_get_term_thumbnail_id( $term_id );
		if ( ! empty( $image_id ) ) {
			return wp_get_attachment_image( $image_id, $size );
		}
	}
	return '';
}

/**
 * Outputs an unordered list of checkbox input elements labelled with term names.
 *
 * @since 1.1.3
 *
 * @param array|string $args {
 *     Optional. Array or string of arguments for generating a terms checklist. Default empty array.
 *
 *     @type int[]  $selected_terms Array of category IDs to mark as checked. Default false.
 *     @type Walker $walker         Walker object to use to build the output. Default empty which
 *                                  results in a Walker_Category_Checklist instance being used.
 *     @type string $taxonomy       Taxonomy to generate the checklist for. Default 'category'.
 * }
 * @return string HTML list of input elements.
 */
function flextension_terms_checklist( $args = array() ) {
	$defaults = array(
		'selected_terms' => false,
		'walker'         => null,
		'taxonomy'       => 'category',
		'item_class'     => '',
		'checkbox_name'  => '',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( empty( $args['walker'] ) || ! ( $args['walker'] instanceof Walker ) ) {
		$walker = new Flextension_Walker_Category_Checklist();
	} else {
		$walker = $args['walker'];
	}

	if ( is_array( $args['selected_terms'] ) ) {
		$args['selected_terms'] = array_map( 'intval', $args['selected_terms'] );
	} else {
		$args['selected_terms'] = array();
	}

	$categories = get_terms(
		array(
			'taxonomy' => $args['taxonomy'],
			'get'      => 'all',
		)
	);

	// Then the rest of them.
	$output = $walker->walk( $categories, 0, $args );

	return $output;
}

/**
 * Retrieves number of post count from given term and its children.
 *
 * @since 1.1.4
 *
 * @param string $taxonomy The taxonomy name.
 * @param int    $term     Term ID.
 * @return int Number of posts count.
 */
function flextension_get_term_post_count( $taxonomy = 'category', $term = 0 ) {

	if ( ! $term ) {
		return 0;
	}

	$args = array(
		'nopaging'            => true,
		'suppress_filters'    => true,
		'ignore_sticky_posts' => true,
		'posts_per_page'      => 1,
		'fields'              => 'ids',
		'tax_query'           => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => $taxonomy,
				'terms'    => $term,
			),
		),
	);

	$query = new WP_Query( $args );

	return $query->found_posts;
}

/**
 * Renders HTML content for the categories block.
 *
 * @param array $attributes The attributes list for the block.
 * @return string HTML content for the categories block.
 */
function flextension_categories_block( $attributes = array() ) {

	/**
	 * Filters Categories block attributes.
	 *
	 * @param array $attributes An array list of block attributes.
	 */
	$attributes = apply_filters( 'flextension_categories_block_attributes', $attributes );

	$args = array(
		'taxonomy' => $attributes['taxonomy'],
	);

	if ( 'all' !== $attributes['display'] && ! empty( $attributes['terms'] ) ) {
		$args['include'] = $attributes['terms'];
	}

	$terms = get_terms( $args );

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return;
	}

	$classes = array();

	$classes[] = 'flext-block-categories flext-categories';

	$attrs = array();

	switch ( $attributes['layout'] ) {
		case 'grid':
			$classes[] = 'is-style-grid';
			$classes[] = 'flext-grid';
			$classes[] = 'flext-columns-' . absint( $attributes['columns'] );
			break;
		case 'carousel':
			$classes[]                     = 'is-style-carousel';
			$classes[]                     = 'flext-carousel';
			$attrs['data-slides-per-view'] = absint( $attributes['columns'] );
			if ( true === $attributes['navigation'] ) {
				$attrs['data-navigation'] = true;
			}

			if ( false !== $attributes['pagination'] ) {
				$attrs['data-pagination'] = $attributes['pagination'];
			}
			break;
		default:
			$classes[] = 'is-style-plain';
			if ( ! empty( $attributes['textAlign'] ) ) {
				$classes[] = 'has-text-align-' . $attributes['textAlign'];
			}
			break;
	}

	if ( ! empty( $attributes['layout'] ) && true === $attributes['showImage'] ) {
		$classes[] = 'has-thumbnail';
	}

	if ( isset( $attributes['attr'] ) && ! empty( $attributes['attr'] ) ) {
		$attrs = wp_parse_args( $attributes['attr'], $attrs );
	}

	$attrs['class'] = esc_attr( implode( ' ', $classes ) );

	$output = sprintf( '<div %s>', get_block_wrapper_attributes( $attrs ) );

	if ( 'carousel' === $attributes['layout'] ) {
		$output .= '<div class="flext-carousel-wrapper">';
	}

	foreach ( $terms as $term ) {

		$item_class = 'category-item';

		if ( 'carousel' === $attributes['layout'] ) {
			$item_class .= ' flext-slide';
		} else {
			$item_class .= ' flext-grid-item';
		}

		$image = '';

		if ( ! empty( $attributes['layout'] ) && true === $attributes['showImage'] ) {
			$image = flextension_get_term_thumbnail( $term->term_id, $attributes['imageSize'] );
		}

		if ( ! empty( $image ) ) {
			$item_class .= ' has-thumbnail';
		}

		$posts_count = '';

		if ( true === $attributes['showPostCounts'] ) {
			$count = $term->count;

			if ( 'all' !== $attributes['display'] && ! empty( $attributes['terms'] ) ) {
				$count = flextension_get_term_post_count( $term->taxonomy, $term->term_id );
			}

			$posts_count = '<span class="posts-count">' . number_format_i18n( $count ) . '</span>';
		}

		$output .= sprintf(
			'<div class="%1$s"><a href="%2$s">%3$s<span>%4$s</span></a>%5$s</div>',
			esc_attr( $item_class ),
			esc_url( get_term_link( $term ) ),
			$image,
			esc_html( $term->name ),
			$posts_count
		);
	}

	if ( 'carousel' === $attributes['layout'] ) {
		$output .= '</div>';
	}

	$output .= '</div>';

	return $output;
}

/**
 * Renders Categories widget.
 *
 * @param array $attributes The attributes list for the widget.
 * @return string The HTML content for the widget.
 */
function flextension_categories_widget( $attributes = array() ) {

	$defaults = array(
		'display'    => 'all',
		'taxonomy'   => 'category',
		'terms'      => array(),
		'show_count' => false,
		'show_image' => false,
		'image_size' => 'post-thumbnail',
		'class'      => '',
	);

	$attributes = wp_parse_args( $attributes, $defaults );

	/**
	 * Filters Categories widget attributes.
	 *
	 * @param array $attributes The widget attributes.
	 */
	$attributes = apply_filters( 'flextension_categories_widget_attributes', $attributes );

	$classes = array();

	$classes[] = 'flext-categories';

	if ( ! empty( $attributes['class'] ) ) {
		$classes[] = $attributes['class'];
	}

	if ( $attributes['show_image'] ) {
		$classes[] = 'has-thumbnail';
	}

	$output = '<ul class="' . esc_attr( implode( ' ', $classes ) ) . '">';

	$args = array(
		'taxonomy' => $attributes['taxonomy'],
	);

	if ( 'all' !== $attributes['display'] && ! empty( $attributes['terms'] ) ) {
		$args['include'] = $attributes['terms'];
	}

	/**
	 * Filters Categories widget arguments.
	 *
	 * @param array $args The Categories widget arguments.
	 */
	$args = apply_filters( 'flextension_categories_widget_args', $args );

	$terms = get_categories( $args );
	if ( ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$image = '';
			if ( $attributes['show_image'] ) {
				$image = flextension_get_term_thumbnail( $term->term_id, $attributes['image_size'] );
			}

			$posts_count = '';
			if ( $attributes['show_count'] ) {
				$count = $term->count;
				if ( 'all' !== $attributes['display'] && ! empty( $attributes['terms'] ) ) {
					$count = flextension_get_term_post_count( $term->taxonomy, $term->term_id );
				}
				$posts_count = '<span class="posts-count">' . number_format_i18n( $count ) . '</span>';
			}
			$output .= sprintf(
				'<li class="category-item%1$s"><a href="%2$s">%3$s<span>%4$s</span></a>%5$s</li>',
				! empty( $image ) ? ' has-thumbnail' : '',
				esc_url( get_term_link( $term ) ),
				$image,
				$term->name,
				$posts_count
			);
		}
	}

	$output .= '</ul>';

	return $output;
}

/**
 * Registers scripts and stylesheets.
 */
function flextension_categories_register_scripts() {
	wp_register_style( 'flextension-categories', plugins_url( 'css/style.css', __FILE__ ), array(), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-categories', 'rtl', 'replace' );

	wp_register_style( 'flextension-categories-widget-editor', plugins_url( 'css/widget-editor.css', __FILE__ ), array(), flextension_get_setting( 'version' ) );

	wp_register_script( 'flextension-categories-widget-editor', plugins_url( 'js/widget-editor.js', __FILE__ ), array( 'flextension-api' ), flextension_get_setting( 'version' ), true );

	$taxonomy_options = array();

	$taxonomies = flextension_featured_taxonomies();
	foreach ( $taxonomies as $name ) {
		$taxonomy = get_taxonomy( $name );
		if ( $taxonomy ) {
			$taxonomy_options[] = array(
				'value' => $taxonomy->name,
				'label' => $taxonomy->label,
			);
		}
	}

	if ( empty( $taxonomy_options ) ) {
		return;
	}

	wp_register_script(
		'flextension-categories-block-editor',
		plugins_url( 'js/block-editor.js', __FILE__ ),
		array( 'flextension-editor', 'flextension-carousel' ),
		flextension_get_setting( 'version' ),
		true
	);

	wp_localize_script( 'flextension-categories-block-editor', 'flextensionCategoriesOptions', $taxonomy_options );

	register_block_type_from_metadata(
		plugin_dir_path( __FILE__ ) . 'blocks/categories',
		array(
			'render_callback' => 'flextension_categories_block',
		)
	);
}

add_action( 'init', 'flextension_categories_register_scripts' );

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_categories_enqueue_scripts() {

	wp_enqueue_style( 'flextension-categories' );

}

add_action( 'wp_enqueue_scripts', 'flextension_categories_enqueue_scripts' );
