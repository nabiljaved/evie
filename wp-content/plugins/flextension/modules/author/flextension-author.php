<?php
/**
 * Author
 *
 * @package    Flextension
 * @subpackage Modules/Author
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers scripts and stylesheets.
 */
function flextension_author_register_scripts() {

	wp_register_style( 'flextension-author', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-author', 'rtl', 'replace' );

	wp_register_style( 'flextension-author-block-editor', plugins_url( 'css/editor.css', __FILE__ ), array( 'flextension', 'flextension-author' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-author-block-editor', 'rtl', 'replace' );

	wp_register_script( 'flextension-author', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension-lightbox' ), flextension_get_setting( 'version' ), true );

	wp_register_script( 'flextension-author-widget-editor', plugins_url( 'js/widget-editor.js', __FILE__ ), array( 'media-editor', 'jquery', 'flextension' ), flextension_get_setting( 'version' ), true );

	$role_options = array();

	$roles = flextension_author_get_roles();

	foreach ( $roles as $name => $label ) {
		if ( ! empty( $name ) ) {
			$role_options[] = array(
				'value' => $name,
				'label' => $label,
			);
		}
	}

	wp_register_script(
		'flextension-author-block-editor',
		plugins_url( 'js/block-editor.js', __FILE__ ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-element', 'wp-i18n', 'flextension-editor' ),
		flextension_get_setting( 'version' ),
		true
	);

	wp_localize_script( 'flextension-author-block-editor', 'flextensionRoleOptions', $role_options );

	register_block_type_from_metadata(
		plugin_dir_path( __FILE__ ) . 'blocks/authors',
		array(
			'render_callback' => 'flextension_authors_block',
		)
	);
}

add_action( 'init', 'flextension_author_register_scripts' );

/**
 * Retrieves the author cover image ID.
 *
 * @param int $user_id The author ID.
 * @return int Post thumbnail ID (which can be 0 if the thumbnail is not set).
 */
function flextension_author_cover_image_id( $user_id ) {
	$id = get_user_meta( $user_id, '_flext_cover_image', true );
	if ( empty( $id ) ) {
		$id = 0;
	}
	return absint( $id );
}

/**
 * Gets an HTML img element representing a cover image of the author.
 *
 * @param int          $user_id The author ID.
 * @param string|array $size    (Optional) Image size. Default value: 'thumbnail'.
 * @return string HTML img element or empty string on failure.
 */
function flextension_author_cover_image( $user_id, $size = 'thumbnail' ) {
	$cover_image = '';

	$image_id = flextension_author_cover_image_id( $user_id );
	if ( ! empty( $image_id ) ) {
		$cover_image = wp_get_attachment_image( $image_id, $size );
	}

	return $cover_image;
}

/**
 * Returns an array object of the image styles of the author.
 *
 * @since 1.0.3
 *
 * @param int $user_id The author ID.
 * @return array An array object of the image styles.
 */
function flextension_author_cover_image_styles( $user_id ) {
	$styles = array();

	$position = get_user_meta( $user_id, '_flext_cover_image_position', true );

	if ( ! empty( $position ) ) {
		$styles['background-position'] = $position;
	}
	return $styles;
}

/**
 * Returns the settings values of the Author module.
 *
 * @return array An array object of the settings.
 */
function flextension_author_settings() {
	return wp_parse_args(
		get_option( 'flext_author', array() ),
		array(
			'follow'    => false,
			'followers' => false,
		)
	);
}

/**
 * Returns the list of all authors.
 *
 * @param string|array $role An array or a comma-separated list of role names. Default empty.
 * @return array An array list of all authors.
 */
function flextension_author_get_authors( $role = '' ) {
	$args = array(
		'role'    => $role,
		'orderby' => 'display_name',
		'fields'  => array(
			'ID',
			'display_name',
		),
	);

	if ( empty( $role ) ) {
		$args['capability'] = 'edit_posts';
	}

	/**
	 * Filters the authors query parameters.
	 *
	 * @param array $args An array of authors query parameters.
	 */
	$args = apply_filters( 'flextension_author_get_authors_args', $args );

	return get_users( $args );
}

/**
 * Returns the list of the author roles.
 *
 * @return array An array list of the author roles.
 */
function flextension_author_get_roles() {
	$roles = array(
		''            => esc_html__( 'All', 'flextension' ),
		'author'      => esc_html__( 'Author', 'flextension' ),
		'contributor' => esc_html__( 'Contributor', 'flextension' ),
		'editor'      => esc_html__( 'Editor', 'flextension' ),
	);

	/**
	 * Filters the author roles.
	 *
	 * @param array $roles An array of the author roles.
	 */
	return apply_filters( 'flextension_author_get_roles', $roles );
}

/**
 * Returns the author description.
 *
 * @param int $author_id The author ID.
 * @return string The user description.
 */
function flextension_author_get_description( $author_id = 0 ) {
	return get_the_author_meta( 'description', $author_id );
}

/**
 * Returns the HTML output for the author's latest posts.
 *
 * @param int    $author_id WP_User ID.
 * @param string $post_type Post type.
 * @param int    $number    Number of posts.
 * @return string HTML output for the author's latest posts.
 */
function flextension_author_get_recent_posts( $author_id = 0, $post_type = 'post', $number = 0 ) {

	if ( ! $author_id ) {
		return '';
	}

	if ( empty( $post_type ) ) {
		$post_type = 'post';
	}

	$output = '';

	$recent_posts = get_posts(
		array(
			'author'           => $author_id,
			'post_type'        => $post_type,
			'numberposts'      => $number,
			'suppress_filters' => false,
		)
	);

	if ( ! empty( $recent_posts ) ) {

		$output = '<div class="author-recent-posts post-type-' . esc_attr( $post_type ) . '"><ul>';

		foreach ( $recent_posts as $recent_post ) {

			$title = get_the_title( $recent_post );

			$output .= '<li>';

			$thumbnail = get_the_post_thumbnail( $recent_post );

			if ( ! empty( $thumbnail ) ) {
				$output .= '<div class="post-thumbnail"><a href="' . esc_url( get_permalink( $recent_post ) ) . '">' . $thumbnail . '</a></div>';
			}

			$output .= '<div class="post-title"><a href="' . esc_url( get_permalink( $recent_post ) ) . '" rel="bookmark">' . esc_html( $title ) . '</a></div>';

			$output .= '</li>';

		}

		$output .= '</ul></div>';
	}

	return $output;
}

/**
 * Returns the Author widget.
 *
 * @param array $attributes The attributes list for the widget.
 * @return string The HTML content for the widget.
 */
function flextension_author_widget( $attributes = array() ) {

	$defaults = array(
		'author_id'  => 0,
		'avatar'     => false,
		'cover'      => false,
		'followers'  => false,
		'image_size' => 'post-thumbnail',
	);

	$attributes = wp_parse_args( $attributes, $defaults );

	/**
	 * Filters Author widget attributes.
	 *
	 * @param array $attributes The widget attributes.
	 */
	$attributes = apply_filters( 'flextension_author_widget_attributes', $attributes );

	$author_id = $attributes['author_id'];

	if ( ! $author_id ) {
		$author_id = get_the_author_meta( 'ID' );
	}

	if ( empty( $author_id ) ) {
		return '';
	}

	$classes = array( 'flext-author-entry' );

	$cover_image = '';
	if ( true === $attributes['cover'] ) {
		$cover_image = flextension_author_cover_image( $author_id, $attributes['image_size'] );
	}

	if ( ! empty( $cover_image ) ) {
		$classes[] = 'has-cover-image';
	}

	$output = '<div class="flext-author">';

	$output .= '<div id="flext-author-' . esc_attr( $author_id ) . '" class="' . esc_attr( implode( ' ', $classes ) ) . '">';

	if ( ! empty( $cover_image ) ) {
		$output .= '<div class="flext-author-cover-image">' . $cover_image . '</div>';
	}

	$display_name     = get_the_author_meta( 'display_name', $author_id );
	$author_posts_url = get_author_posts_url( $author_id );

	if ( true === $attributes['avatar'] ) {

		/**
		 * Filters the author image size.
		 *
		 * @param int $size The author image size.
		 */
		$image_size = apply_filters( 'flextension_author_widget_image_size', 125 );

		$output .= '<div class="flext-author-header">
						<div class="flext-author-avatar">
                            <a href="' . esc_url( $author_posts_url ) . '">' .
								get_avatar( get_the_author_meta( 'email', $author_id ), $image_size ) .
								flextension_author_follow_button( $author_id ) .
							'</a>
						</div>
					</div><!-- .flext-author-header -->';
	}

	$output .= '<div class="flext-author-detail">
					<h4 class="flext-author-title"><a href="' . esc_url( $author_posts_url ) . '">' . esc_html( $display_name ) . '</a></h4>';

	if ( true === $attributes['followers'] ) {
		$output .= flextension_author_follow_numbers( $author_id );
	}

	$output .= flextension_author_get_description( $author_id );

	$output .= '</div><!-- .flext-author-detail -->
            </div><!-- .flext-author-entry -->
		</div><!-- .flext-author -->';

	return $output;
}

/**
 * Renders an Authors widget.
 *
 * @param array $attributes The attributes list for the widget.
 * @return string The HTML content for the widget.
 */
function flextension_authors_widget( $attributes = array() ) {

	$defaults = array(
		'title'      => esc_html__( 'Authors', 'flextension' ),
		'role'       => '',
		'avatar'     => false,
		'cover'      => false,
		'image_size' => 'post-thumbnail',
	);

	$attributes = wp_parse_args( $attributes, $defaults );

	/**
	 * Filters Authors widget attributes.
	 *
	 * @param array $attributes The widget attributes.
	 */
	$attributes = apply_filters( 'flextension_authors_widget_attributes', $attributes );

	$classes = array();

	$classes[] = 'flext-authors';

	$output = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">';

	$authors = flextension_author_get_authors( $attributes['role'] );

	/**
	 * Filters the author image size.
	 *
	 * @param int $size The author image size.
	 */
	$image_size = apply_filters( 'flextension_authors_widget_image_size', 125 );

	foreach ( $authors as $author ) {

		$author_posts_url = get_author_posts_url( $author->ID );

		$class = 'flext-author-entry';

		$cover_image = '';
		if ( true === $attributes['cover'] ) {
			$cover_image = flextension_author_cover_image( $author->ID, $attributes['image_size'] );
		}

		if ( ! empty( $cover_image ) ) {
			$class .= ' has-cover-image';
		}

		$output .= '<div id="flext-author-' . esc_attr( $author->ID ) . '" class="' . esc_attr( $class ) . '">';

		if ( ! empty( $cover_image ) ) {
			$output .= '<div class="flext-author-cover-image">' . $cover_image . '</div>';
		}

		if ( true === $attributes['avatar'] ) {
			$output .= '<div class="flext-author-header">
							<div class="flext-author-avatar">
								<a href="' . esc_url( $author_posts_url ) . '">' .
									get_avatar( get_the_author_meta( 'email', $author->ID ), $image_size ) .
									flextension_author_follow_button( $author->ID ) .
								'</a>
							</div>
						</div><!-- .flext-author-header -->';
		}

		$output .= '<div class="flext-author-detail">
                        <h4 class="flext-author-title"><a href="' . esc_url( $author_posts_url ) . '">' . esc_html( $author->display_name ) . '</a></h4>';

		if ( true === $attributes['followers'] ) {
			$output .= flextension_author_follow_numbers( $author->ID );
		}

		$output .= flextension_author_get_description( $author->ID );

		$output .= '</div><!-- .flext-author-detail -->';

		$output .= '</div><!-- .flext-author-entry -->';
	}

	$output .= '</div><!-- .flext-authors -->';

	return $output;
}

/**
 * Renders the Authors block.
 *
 * @param array $attributes The attributes list for the block.
 * @return string The HTML content for the block.
 */
function flextension_authors_block( $attributes ) {

	$attrs = array();

	if ( ! empty( $attributes['blockId'] ) ) {
		$attrs['id'] = 'flext-block-' . $attributes['blockId'];
	}

	$classes = array(
		'flext-block-authors',
		'flext-authors',
	);

	if ( true === $attributes['displayPosts'] ) {
		$classes[] = 'has-recent-posts';
	}

	$attrs['class'] = implode( ' ', $classes );

	$output = '<div ' . get_block_wrapper_attributes( $attrs ) . '>';

	$per_page     = $attributes['numberOfItems'];
	$current_page = max( $attributes['page'], get_query_var( 'paged' ) );

	$args = array(
		'count_total' => false,
		'orderby'     => 'display_name',
		'fields'      => array(
			'ID',
			'display_name',
		),
		'number'      => $per_page,
	);

	if ( true === $attributes['pagination'] ) {
		$offset = ( $current_page - 1 ) * $per_page;

		$args['paged']       = $current_page;
		$args['offset']      = $offset;
		$args['count_total'] = true;
	}

	if ( 'role' === $attributes['filterBy'] ) {
		$args['role__in'] = $attributes['roles'];
	} elseif ( 'author' === $attributes['filterBy'] ) {
		$args['include'] = $attributes['authors'];
	}

	if ( true === $attributes['hasPublishedPosts'] ) {
		$args['has_published_posts'] = true;
	}

	/**
	 * Filters the author image size.
	 *
	 * @param int $size The author image size.
	 */
	$image_size = apply_filters( 'flextension_authors_block_image_size', 125 );

	$user_query = new WP_User_Query( $args );

	$authors = $user_query->get_results();

	if ( count( $authors ) > 0 ) {

		$output .= '<div class="flext-authors-list">';

		foreach ( $authors as $author ) {

			$author_posts_url = get_author_posts_url( $author->ID );

			if ( ! empty( $attributes['postType'] ) && 'post' !== $attributes['postType'] ) {
				$author_posts_url = add_query_arg( 'post_type', $attributes['postType'], $author_posts_url );
			}

			$class = 'flext-author-entry';

			$cover_image = '';
			if ( true === $attributes['displayCover'] ) {
				$cover_image = flextension_author_cover_image( $author->ID, $attributes['imageSize'] );
				if ( ! empty( $cover_image ) ) {
					$class .= ' has-cover-image';
				}
			}

			$output .= '<div id="author-' . esc_attr( $author->ID ) . '" class="' . esc_attr( $class ) . '">';

			if ( ! empty( $cover_image ) ) {
				$output .= '<div class="flext-author-cover-image">' . $cover_image . '</div>';
			}

			if ( true === $attributes['displayAvatar'] ) {
				$output .= '<div class="flext-author-header">
								<div class="flext-author-avatar">
									<a href="' . esc_url( $author_posts_url ) . '">' .
										get_avatar( get_the_author_meta( 'email', $author->ID ), $image_size ) .
										flextension_author_follow_button( $author->ID ) .
									'</a>								
								</div>
							</div><!-- .flext-author-header -->';
			}

			$output .= '<div class="flext-author-detail">';

			$output .= '<h3 class="flext-author-title"><a href="' . esc_url( $author_posts_url ) . '">' . esc_html( $author->display_name ) . '</a></h3>';

			if ( true === $attributes['displayFollowers'] ) {
				$output .= flextension_author_follow_numbers( $author->ID );
			}

			if ( true === $attributes['displayDescription'] ) {
				$output .= flextension_author_get_description( $author->ID );
			}

			$output .= '</div><!-- .flext-author-detail -->';

			if ( true === $attributes['displayPosts'] ) {
				/**
				 * Filters the number of recent posts to show.
				 *
				 * @param int $number       The number of recent posts to show.
				 * @param array $attributes The attributes list for the block.
				 */
				$number = apply_filters( 'flextension_author_block_recent_posts_number', 5, $attributes );

				$output .= flextension_author_get_recent_posts( $author->ID, $attributes['postType'], $number );
			}

			$output .= '</div><!-- .flext-author-entry -->';

		}

		$output .= '</div><!-- .flext-authors-list -->';

		if ( true === $attributes['pagination'] ) {
			$total       = $user_query->get_total();
			$total_pages = ceil( $total / $per_page );

			$output .= '<nav class="flext-authors-pagination navigation pagination next-previous-pagination"><div class="nav-links">';
			$output .= flextension_authors_pagination( $total_pages, $current_page );
			$output .= '</div></nav>';
		}
	}

	$output .= '</div><!-- .flext-block-authors -->';

	return $output;
}

/**
 * Returns the next and previous authors pagination.
 *
 * @param int $total   Total pages.
 * @param int $current The current page.
 * @return string The HTML output for the authors pagination.
 */
function flextension_authors_pagination( $total = 1, $current = 0 ) {
	if ( ! $current ) {
		$current = max( 1, get_query_var( 'paged' ) );
	}

	$pagination = '';

	$prev_page = absint( $current ) - 1;
	if ( $prev_page > 0 ) {
		$pagination = '<a href="' . get_pagenum_link( $prev_page ) . '" class="prev"><i class="flext-ico-left"></i><span>' . esc_html__( 'Previous', 'flextension' ) . '</span></a>';
	}

	$next_page = absint( $current ) + 1;
	if ( $next_page <= $total ) {
		$pagination .= '<a href="' . get_pagenum_link( $next_page ) . '" class="next"><span>' . esc_html__( 'Next', 'flextension' ) . '</span><i class="flext-ico-right"></i></a>';
	}

	return $pagination;
}

/**
 * Adds the placeholder text if the description is empty.
 *
 * @since 1.0.6
 *
 * @param string $value   The value of the metadata.
 * @param int    $user_id The user ID for the value.
 * @return string The author description.
 */
function flextension_author_description_placeholder( $value = '', $user_id = 0 ) {

	if ( empty( $value ) ) {
		$author = get_user_by( 'ID', $user_id );
		if ( $author instanceof WP_User ) {
			$number = count_user_posts( $user_id, 'post', true );
			$value  = sprintf(
				translate_nooped_plural(
					/* translators: %1: Author name. %2: number of posts. */
					_n_noop( '%1$s has created %2$s post.', '%1$s has created %2$s posts.', 'flextension' ),
					$number,
					'flextension'
				),
				$author->display_name,
				number_format_i18n( $number )
			);
		}
	}

	return $value;
}

add_filter( 'get_the_author_description', 'flextension_author_description_placeholder', 5, 2 );

/**
 * Adds the container (paragraph) the author description.
 *
 * @since 1.0.6
 *
 * @param string $value The value of the metadata.
 * @return string The author description with the container.
 */
function flextension_author_description_container( $value = '' ) {

	if ( ! empty( $value ) ) {
		$value = '<p class="flext-author-description">' . $value . '</p>';
	}

	return $value;
}

add_filter( 'get_the_author_description', 'flextension_author_description_container', 10 );

/**
 * Retrieves author location.
 *
 * @param int $user_id The user ID for the value.
 * @return string The author location.
 */
function flextension_author_location( $user_id = 0 ) {

	$location = get_user_meta( $user_id, 'location', true );
	if ( ! empty( $location ) ) {
		$maps_api_url = sprintf(
			/* translators: %s: Location. */
			_x( 'https://www.google.com/maps/search/?api=1&query=%s', 'Maps API URL', 'flextension' ),
			rawurlencode( $location )
		);

		return '<span class="flext-author-location"><a href="' . esc_attr( $maps_api_url ) . '" target="_blank" rel="nofollow"><i class="flext-ico-location"></i>' . esc_html( $location ) . '</a></span>';
	}

	return '';
}

/**
 * Appends author location to the author description.
 *
 * @since 1.0.6
 *
 * @param string $value   The value of the metadata.
 * @param int    $user_id The user ID for the value.
 * @return string The author description with author location.
 */
function flextension_author_add_location_to_description( $value = '', $user_id = 0 ) {
	return $value . flextension_author_location( $user_id );
}

add_filter( 'get_the_author_description', 'flextension_author_add_location_to_description', 10, 2 );

/**
 * Adds a Location to the author's contact methods.
 *
 * @param string[] $methods Array of contact methods.
 * @return string[] Array of contact methods.
 */
function flextension_author_contact_methods( $methods = array() ) {
	$methods['location'] = esc_html__( 'Location', 'flextension' );
	return $methods;
}

add_filter( 'user_contactmethods', 'flextension_author_contact_methods' );

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_author_enqueue_scripts() {

	wp_enqueue_style( 'flextension-author' );

	wp_enqueue_script( 'flextension-author' );

}

add_action( 'wp_enqueue_scripts', 'flextension_author_enqueue_scripts' );
