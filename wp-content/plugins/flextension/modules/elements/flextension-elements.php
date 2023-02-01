<?php
/**
 * Elements
 *
 * @package    Flextension
 * @subpackage Modules/Elements
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers scripts and stylesheets.
 */
function flextension_elements_register_scripts() {
	wp_register_style( 'flextension-widgets', plugins_url( 'css/widgets.css', __FILE__ ), array( 'flextension-carousel', 'flextension-tabs' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-widgets', 'rtl', 'replace' );

	wp_register_style( 'flextension-blocks', plugins_url( 'css/blocks.css', __FILE__ ), array( 'flextension-carousel' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-blocks', 'rtl', 'replace' );

	wp_register_style( 'flextension-block-editor', plugins_url( 'css/block-editor.css', __FILE__ ), array( 'flextension-blocks' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-block-editor', 'rtl', 'replace' );

	wp_register_script( 'flextension-widget-editor', plugins_url( 'js/widget-editor.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );

	wp_register_script( 'flextension-blocks', plugins_url( 'js/blocks.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );

	wp_register_script( 'flextension-block-editor', plugins_url( 'js/block-editor.js', __FILE__ ), array( 'flextension-editor' ), flextension_get_setting( 'version' ), true );

}

add_action( 'init', 'flextension_elements_register_scripts' );

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_elements_enqueue_scripts() {
	wp_enqueue_style( 'flextension-widgets' );

	wp_enqueue_style( 'flextension-blocks' );

	wp_enqueue_script( 'flextension-carousel' );

	wp_enqueue_script( 'flextension-tabs' );
}

add_action( 'wp_enqueue_scripts', 'flextension_elements_enqueue_scripts' );

/**
 * Gets archives link for the Archive widget.
 *
 * @param string $link_html The archive HTML link content.
 * @param string $url       URL to archive.
 * @param string $text      Archive text description.
 * @param string $format    Link format. Can be 'link', 'option', 'html', or custom.
 * @param string $before    Content to prepend to the description.
 * @param string $after     Content to append to the description.
 * @return string URL The archive HTML link content.
 */
function flextension_get_archives_link( $link_html, $url, $text, $format, $before, $after ) {

	$text = wptexturize( $text );

	$url = esc_url( $url );

	if ( 'link' === $format ) {
		$link_html = "\t<link rel='archives' title='" . esc_attr( $text ) . "' href='$url' />\n";
	} elseif ( 'option' === $format ) {
		$link_html = "\t<option value='$url'>$before $text $after</option>\n";
	} elseif ( 'html' === $format ) {
		$after     = '<span class="posts-count">' . $after . '</span>';
		$link_html = "\t<li>$before<a href='$url'>$text</a>$after</li>\n";
	} else {
		$after     = '<span class="posts-count">' . $after . '</span>';
		$link_html = "\t$before<a href='$url'>$text</a>$after\n";
	}

	return $link_html;

}

add_filter( 'get_archives_link', 'flextension_get_archives_link', 50, 6 );

/**
 * Registers all widgets in the folder 'widgets'.
 */
function flextension_elements_register_widgets() {
	flextension_load_files( 'widgets/*.php', plugin_dir_path( __FILE__ ) );
}

/**
 * Registers all blocks in the folder 'blocks/src'.
 */
function flextension_elements_register_blocks() {
	flextension_load_files( 'blocks/*.php', plugin_dir_path( __FILE__ ) );
}

/**
 * Returns a single category name for the post; wrap it in a link.
 *
 * @param int|WP_Post $post     Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string      $taxonomy Optional. Taxonomy name. Default 'category'.
 * @return string A link of single category for the post.
 */
function flextension_get_single_category( $post = 0, $taxonomy = 'category' ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	$category_link = '';

	$categories = get_the_terms( $post, $taxonomy );

	if ( ! empty( $categories ) ) {

		$category_names = array();

		foreach ( $categories as $category ) {
			$category_names[] = esc_html( $category->name );
		}

		if ( count( $category_names ) > 0 && isset( $categories[0] ) ) {
			$category_link = sprintf(
				'<a href="%s" title="%s" rel="category tag">%s</a>',
				esc_url( get_term_link( $categories[0]->term_id, $categories[0]->taxonomy ) ),
				esc_attr( implode( ', ', $category_names ) ),
				esc_html( $categories[0]->name )
			);
		}
	}

	if ( ! empty( $category_link ) ) {
		$category_link = '<span class="meta-' . esc_attr( $taxonomy ) . '">' . $category_link . '</span>';
	}

	return $category_link;
}

/**
 * Returns HTML content for the Post Carousel widget.
 *
 * @param array $attributes The attributes list for the widget.
 * @return string The HTML content for the widget.
 */
function flextension_post_carousel_widget( $attributes = array() ) {

	$defaults = array(
		'post_type'       => 'post',
		'type'            => 'recent',
		'tags'            => '',
		'number'          => 5,
		'image_size'      => 'post-thumbnail',
		'navigation'      => true,
		'autoplay'        => false,
		'loop'            => false,
		'effect'          => 'creative',
		'render_callback' => 'flextension_post_carousel_widget_render',
	);

	$attributes = wp_parse_args(
		$attributes,
		$defaults
	);

	/**
	 * Filters the Post Carousel widget attributes.
	 *
	 * @param array $attributes The widget attributes.
	 */
	$attributes = apply_filters( 'flextension_post_carousel_attributes', $attributes );

	$output = is_callable( $attributes['render_callback'] ) ? call_user_func( $attributes['render_callback'], $attributes ) : '';

	return $output;
}

/**
 * Renders Post Carousel widget.
 *
 * @since 1.0.8
 *
 * @param array $attributes The attributes list for the widget.
 * @return string The HTML content for the widget.
 */
function flextension_post_carousel_widget_render( $attributes ) {
	$args = array();

	switch ( $attributes['type'] ) {
		case 'popular':
			$args = array(
				'post_type'        => $attributes['post_type'],
				'posts_per_page'   => $attributes['number'],
				'suppress_filters' => false,
				'orderby'          => 'likes',
				'meta_query'       => array(
					array(
						'key'     => '_thumbnail_id',
						'compare' => 'EXISTS',
					),
				),
			);
			break;
		case 'tags':
			$args = array(
				'post_type'        => $attributes['post_type'],
				'posts_per_page'   => $attributes['number'],
				'tag'              => $attributes['tags'],
				'suppress_filters' => false,
				'meta_query'       => array(
					array(
						'key'     => '_thumbnail_id',
						'compare' => 'EXISTS',
					),
				),
			);
			break;
		default:
			$args = array(
				'post_type'        => $attributes['post_type'],
				'posts_per_page'   => $attributes['number'],
				'suppress_filters' => false,
				'meta_query'       => array(
					array(
						'key'     => '_thumbnail_id',
						'compare' => 'EXISTS',
					),
				),
			);
			break;
	}

	$output = '';

	/**
	 * Filters an array of the arguments to retrieve the posts.
	 *
	 * @param array  $args An array of the arguments to retrieve the posts.
	 * @param string $type Data source type.
	 */
	$args = apply_filters( 'flextension_post_carousel_args', $args, $attributes['type'] );

	$posts = get_posts( $args );

	if ( ! empty( $posts ) ) {

		/**
		 * Filters the post taxonomy to show in the widget.
		 *
		 * @param string $taxonomy  The taxonomy to show.
		 * @param string $post_type The current post type.
		 */
		$taxonomy = apply_filters( 'flextension_widget_post_taxonomy', 'category', $attributes['post_type'] );

		$attrs = array();

		$attrs['class'] = 'flext-post-carousel flext-carousel';

		$attrs['data-slides-per-view'] = 1;

		if ( $attributes['navigation'] ) {
			$attrs['data-navigation'] = true;
		}

		if ( $attributes['autoplay'] ) {
			$attrs['data-auto-play'] = true;
		}

		if ( $attributes['loop'] ) {
			$attrs['data-loop'] = true;
		}

		if ( ! empty( $attributes['effect'] ) ) {
			$attrs['data-effect'] = $attributes['effect'];
		}

		$output = '<div' . flextension_get_attributes( $attrs ) . '>
					<div class="flext-carousel-wrapper">';

		$count = count( $posts );

		for ( $i = 0; $i < $count; $i++ ) {
			$post = $posts[ $i ];

			$output .= '<div class="flext-slide">
							<div class="post-thumbnail">
								<a href="' . esc_attr( get_permalink( $post ) ) . '">'
									. get_the_post_thumbnail( $post, $attributes['image_size'] ) .
								'</a>
							</div>
							<header class="post-header">
								<span class="post-category">' . flextension_get_single_category( $post, $taxonomy ) . '</span>
								<h4 class="post-title"><a href="' . esc_attr( get_permalink( $post ) ) . '" rel="bookmark">' . esc_html( get_the_title( $post ) ) . '</a></h4>
							</header>
						</div>';
		}

		$output .= '</div>
				</div>';
	}

	return $output;
}

/**
 * Returns HTML content for the Post Tabs widget.
 *
 * @param array $attributes The attributes list for the widget.
 * @return string The HTML content for the widget.
 */
function flextension_post_tabs_widget( $attributes = array() ) {

	$defaults = array(
		'post_type'       => 'post',
		'popular'         => true,
		'recent'          => true,
		'comments'        => true,
		'number'          => 5,
		'image_size'      => 'post-thumbnail',
		'render_callback' => 'flextension_post_tabs_widget_render',
	);

	$attributes = wp_parse_args(
		$attributes,
		$defaults
	);

	/**
	 * Filters the Post Tabs widget attributes.
	 *
	 * @param array $attributes The widget attributes.
	 */
	$attributes = apply_filters( 'flextension_post_tabs_attributes', $attributes );

	$output = is_callable( $attributes['render_callback'] ) ? call_user_func( $attributes['render_callback'], $attributes ) : '';

	return $output;
}

/**
 * Renders Post Tabs widget.
 *
 * @param array $attributes The attributes list for the widget.
 * @return string The HTML content for the widget.
 */
function flextension_post_tabs_widget_render( $attributes = array() ) {

	/**
	 * Filters the post taxonomy to show in the widget.
	 *
	 * @param string $taxonomy  The taxonomy to show.
	 * @param string $post_type The current post type.
	 */
	$taxonomy = apply_filters( 'flextension_widget_post_taxonomy', 'category', $attributes['post_type'] );

	$output = '<div class="flext-post-tabs flext-tabs">';

	$output .= '<nav class="flext-tabs-nav">';

	if ( true === (bool) $attributes['popular'] ) {

		$output .= sprintf(
			'<a href="#popular-posts" title="%s"><span>%s</span></a>',
			esc_attr( esc_html__( 'Popular Posts', 'flextension' ) ),
			esc_html__( 'Popular', 'flextension' )
		);

	}

	if ( true === (bool) $attributes['recent'] ) {

		$output .= sprintf(
			'<a href="#recent-posts" title="%s"><span>%s</span></a>',
			esc_attr( esc_html__( 'Recent Posts', 'flextension' ) ),
			esc_html__( 'Recent', 'flextension' )
		);

	}

	if ( true === (bool) $attributes['comments'] ) {

		$output .= sprintf(
			'<a href="#recent-comments" title="%s"></i><span>%s</span></a>',
			esc_attr( esc_html__( 'Recent Comments', 'flextension' ) ),
			esc_html__( 'Comments', 'flextension' )
		);

	}

	$output .= '</nav>';

	$output .= '<div class="flext-tab-wrapper">';

	if ( true === (bool) $attributes['popular'] ) {

		$output .= '<div class="flext-tab">';

		/**
		 * Filters an array of the arguments to retrieve the posts.
		 *
		 * @param array $args An array of the arguments to retrieve the posts.
		 */
		$args = apply_filters(
			'flextension_post_tabs_popular_posts_args',
			array(
				'post_type'        => $attributes['post_type'],
				'posts_per_page'   => $attributes['number'],
				'orderby'          => 'likes',
				'suppress_filters' => false,
			)
		);

		$popular_posts = get_posts( $args );

		if ( ! empty( $popular_posts ) ) {

			$output .= '<ul class="post-tab-posts popular-posts-list">';

			foreach ( $popular_posts as $popular_post ) {

				$post_class = '';

				$thumbnail = get_the_post_thumbnail( $popular_post, $attributes['image_size'] );
				if ( '' === $thumbnail ) {
					$thumbnail = '<i class="flext-ico-article"></i>';
				} else {
					$post_class .= ' class="has-post-thumbnail"';
				}

				$output .= sprintf( '<li%s>', $post_class );

				$output .= '<div class="post-thumbnail"><a href="' . esc_url( get_permalink( $popular_post ) ) . '">' . $thumbnail . '</a></div>';

				$output .= '<div class="post-header">';

				$output .= '<span class="post-category">' . flextension_get_single_category( $popular_post, $taxonomy ) . '</span>';

				$output .= '<h4 class="post-title"><a href="' . esc_url( get_permalink( $popular_post ) ) . '">' . esc_html( get_the_title( $popular_post ) ) . '</a></h4>';

				$output .= '</div>';

				$output .= '</li>';

			}

			$output .= '</ul>';

		} else {

			$output .= esc_html__( 'There are no posts to show right now.', 'flextension' );

		}

		$output .= '</div>';
	}

	if ( true === (bool) $attributes['recent'] ) {

		$output .= '<div class="flext-tab">';

		/**
		 * Filters an array of the arguments to retrieve the posts.
		 *
		 * @param array $args An array of the arguments to retrieve the posts.
		 */
		$args = apply_filters(
			'flextension_post_tabs_recent_posts_args',
			array(
				'post_type'        => $attributes['post_type'],
				'posts_per_page'   => $attributes['number'],
				'suppress_filters' => false,
			)
		);

		$recent_posts = get_posts( $args );

		if ( ! empty( $recent_posts ) ) {

			$output .= '<ul class="post-tab-posts recent-posts-list">';

			foreach ( $recent_posts as $recent_post ) {

				$post_class = '';

				$thumbnail = get_the_post_thumbnail( $recent_post, $attributes['image_size'] );
				if ( '' === $thumbnail ) {
					$thumbnail = '<i class="flext-ico-article"></i>';
				} else {
					$post_class .= ' class="has-post-thumbnail"';
				}

				$output .= sprintf( '<li%s>', $post_class );

				$output .= '<div class="post-thumbnail"><a href="' . esc_url( get_permalink( $recent_post ) ) . '">' . $thumbnail . '</a></div>';

				$output .= '<div class="post-header">';

				$output .= '<span class="post-category">' . flextension_get_single_category( $recent_post, $taxonomy ) . '</span>';

				$output .= '<h4 class="post-title"><a href="' . esc_url( get_permalink( $recent_post ) ) . '">' . esc_html( get_the_title( $recent_post ) ) . '</a></h4>';

				$output .= '</div>';

				$output .= '</li>';

			}

			$output .= '</ul>';

		} else {

			$output .= esc_html__( 'There are no posts to show right now.', 'flextension' );

		}

		$output .= '</div>';
	}

	if ( true === (bool) $attributes['comments'] ) {

		$output .= '<div class="flext-tab">';

		/**
		 * Filters an array of the arguments to retrieve the comments.
		 *
		 * @param array $args An array of the arguments to retrieve the comments.
		 */
		$args = apply_filters(
			'flextension_post_tabs_recent_comments_args',
			array(
				'post_type'   => $attributes['post_type'],
				'number'      => $attributes['number'],
				'status'      => 'approve',
				'post_status' => 'publish',
			)
		);

		$recent_comments = get_comments( $args );

		if ( ! empty( $recent_comments ) ) {

			$output .= '<ul class="recent-comments-list">';

			foreach ( (array) $recent_comments as $comment ) {

				$title = get_the_title( $comment->ID );

				$output .= '<li>';

				$output .= '<div class="flext-grid-item post-thumbnail"><a href="' . esc_url( get_comment_link( $comment ) ) . '">' . get_avatar( $comment, '80' ) . '</a></div>';

				$output .= '<div class="flext-grid-item post-header">';

				$output .= '<span>' .
								sprintf(
									/* translators: %s: Author name */
									esc_html__( '%s on', 'flextension' ),
									get_comment_author( $comment )
								) .
							'</span>';

				$output .= '<h4 class="post-title"><a href="' . esc_url( get_comment_link( $comment ) ) . '">' . get_the_title( $comment->comment_post_ID ) . '</a></h4>';

				$output .= '</div>';

				$output .= '</li>';

			}

			$output .= '</ul>';

		} else {

			$output .= esc_html__( 'There are no posts to show right now.', 'flextension' );

		}

		$output .= '</div>';
	}

	$output .= '    </div>';

	$output .= '</div><!-- .flext-post-tabs -->';

	return $output;
}

/**
 * Adds a new block category.
 *
 * @param array $categories An array list of the categories.
 * @return array An array list of the categories.
 */
function flextension_elements_add_block_category( $categories ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'flextension',
				'title' => esc_html__( 'Flextension', 'flextension' ),
			),
		)
	);
}

add_filter( 'block_categories_all', 'flextension_elements_add_block_category' );
