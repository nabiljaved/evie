<?php
/**
 * AJAX Pagination
 *
 * @package    Evie_XT
 * @subpackage Modules/AJAX_Pagination
 * @version    1.0.0
 */

/**
 * Retrieves the post by ID or path.
 *
 * @param WP_REST_Request $request Optional. Full details about the request.
 * @return WP_Post|null Post object if the ID or path is valid, null otherwise.
 */
function evie_ajax_pagination_get_post( $request ) {
	$post = null;
	if ( isset( $request['path'] ) ) { // Find single post or page by path.
		if ( ! empty( $request['path'] ) ) { // Single post or page.
			$post = get_page_by_path( $request['path'] );
			if ( null === $post ) {
				$id = url_to_postid( $request['path'] );
				if ( $id > 0 ) {
					$post = get_post( $id );
				}
			}
		} elseif ( 'page' === get_option( 'show_on_front' ) ) { // Front page.
			$post = get_post( get_option( 'page_on_front' ) );
		}
	} elseif ( ! empty( $request['id'] ) ) { // Find single post or page by ID.
		$post = get_post( $request['id'] );
	}

	return $post;
}

/**
 * Retrieves the first instance of a block by given attribute name and value.
 *
 * @param array  $blocks     An array list of blocks to find.
 * @param string $attr_name  Attribute name to search.
 * @param string $attr_value Attribute value to match.
 * @return array|null An array object of the block if exists, null otherwise.
 */
function evie_ajax_pagination_get_block( $blocks, $attr_name, $attr_value ) {
	$matched = null;
	if ( ! empty( $blocks ) ) {
		foreach ( $blocks as $block ) {
			if ( isset( $block['attrs'][ $attr_name ] ) && $block['attrs'][ $attr_name ] === $attr_value ) {
				$matched = $block;
				break;
			}

			if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) ) {
				$matched = evie_ajax_pagination_get_block( $block['innerBlocks'], $attr_name, $attr_value );
				if ( ! empty( $matched ) ) {
					break;
				}
			}
		}
	}
	return $matched;
}

/**
 * Determines the allowed query_vars for a get_items() response and prepares
 * them for WP_Query.
 *
 * @param WP_REST_Request $request Optional. Full details about the request.
 * @return array Items query arguments.
 */
function evie_ajax_pagination_prepare_items_query( $request = null ) {
	$args = array();

	// Set post ID.
	if ( isset( $request['p'] ) ) {
		$args['p'] = $request['p'];
	}

	// Set page ID.
	if ( isset( $request['page_id'] ) ) {
		$args['page_id'] = $request['page_id'];
	}

	if ( get_option( 'permalink_structure' ) ) { // Permalinks enabled.
		// Set author name query.
		if ( isset( $request['author'] ) && ! is_numeric( $request['author'] ) ) {
			$args['author_name'] = $request['author'];
		}
	}

	// Set author id query.
	if ( isset( $request['author'] ) && is_numeric( $request['author'] ) ) {
		$args['author'] = $request['author'];
	}

	// Set category id query.
	if ( isset( $request['cat'] ) ) {
		$args['cat'] = $request['cat'];
	}

	// Set tag id query.
	if ( isset( $request['tag_id'] ) ) {
		$args['tag_id'] = $request['tag_id'];
	}

	// Set post name query (for custom post types).
	$post_types = get_post_types(
		array(
			'publicly_queryable' => 1,
		),
		'objects'
	);

	foreach ( $post_types as $post_type ) {
		if ( ! empty( $post_type->query_var ) && isset( $request[ $post_type->query_var ] ) ) {
			$args[ $post_type->query_var ] = $request[ $post_type->query_var ];
			$args['post_type']             = $post_type->name;
			$args['name']                  = $request[ $post_type->query_var ];
		}
	}

	// Set search query.
	if ( isset( $request['s'] ) ) {
		$args['s'] = $request['s'];
	}

	// Set search query.
	if ( isset( $request['search'] ) ) {
		$args['s'] = $request['search'];
	}

	// Set post type query.
	if ( isset( $request['post_type'] ) ) {
		$args['post_type'] = $request['post_type'];
	}

	if ( isset( $request['day'] ) ) {
		$args['day'] = $request['day'];
	}

	// Check for & assign any parameters which require special handling or setting.
	if ( isset( $request['m'] ) ) {
		$args['m'] = $request['m'];
	}

	if ( isset( $request['monthnum'] ) ) {
		$args['monthnum'] = $request['monthnum'];
	}

	if ( isset( $request['year'] ) ) {
		$args['year'] = $request['year'];
	}

	if ( isset( $request['order'] ) ) {
		$args['order'] = $request['order'];
	}

	if ( isset( $request['orderby'] ) ) {
		$args['orderby'] = $request['orderby'];
	}

	// Set page number of the posts.
	if ( isset( $request['paged'] ) ) {
		$args['paged'] = $request['paged'];
	}

	// Set page number of the single post or page.
	if ( isset( $request['page'] ) ) {
		$args['page'] = $request['page'];
	}

	if ( isset( $request['filter'] ) ) {
		$args['filter'] = $request['filter'];
	}

	$taxonomies = get_taxonomies(
		array(
			'publicly_queryable' => 1,
		),
		'objects'
	);

	foreach ( $taxonomies as $taxonomy ) {
		if ( $taxonomy->query_var && isset( $request[ $taxonomy->query_var ] ) ) {
			$args[ $taxonomy->query_var ] = $request[ $taxonomy->query_var ];
			$args['post_type']            = $taxonomy->object_type;
		}
	}

	if ( ! isset( $args['s'] ) && ( isset( $request['path'] ) || isset( $request['id'] ) ) ) {
		if ( isset( $request['path'] ) && empty( $request['path'] ) && 'posts' === get_option( 'show_on_front' ) ) {
			$args['post_type'] = 'post';
		} else {
			$post = evie_ajax_pagination_get_post( $request );
			if ( null !== $post ) {
				if ( 'page' === $post->post_type ) {
					$args['page_id'] = $post->ID;
				} else {
					$args['p']                = $post->ID;
					$args['post_type']        = $post->post_type;
					$args[ $post->post_type ] = $post->post_name;
				}
			} else {
				$args['p'] = -1;
			}
		}
	}

	/**
	 * Filters the query arguments for a request.
	 *
	 * Enables adding extra arguments or setting defaults for a post collection request.
	 *
	 * @link https://developer.wordpress.org/reference/classes/wp_query/
	 *
	 * @param array           $args    Key value array of query var to query value.
	 * @param WP_REST_Request $request The request used.
	 */
	return apply_filters( 'evie_ajax_pagination_posts_query_args', $args, $request );
}

/**
 * Returns the main WordPress Query object.
 *
 * @global WP_Query $wp_the_query WordPress Query object.
 */
function evie_ajax_pagination_get_main_query() {
	global $wp_the_query;
	// If the main query object is not set.
	if ( ! isset( $wp_the_query ) ) {
		$wp_the_query = $GLOBALS['wp_query']; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
	return $wp_the_query;
}

/**
 * Sets up the Loop with query parameters.
 *
 * @param array $query (Required) Array of WP_Query arguments.
 */
function evie_ajax_pagination_get_posts( $query ) {
	// Setup the main query to make all templates work correctly.
	$main_query = evie_ajax_pagination_get_main_query();
	$main_query->query( $query );
	if ( ! (
		$main_query->is_singular ||
		$main_query->is_archive ||
		$main_query->is_search ||
		$main_query->is_feed ||
		$main_query->is_trackback ||
		$main_query->is_404 ||
		$main_query->is_admin ||
		$main_query->is_robots ||
		$main_query->is_favicon )
	) {
		$main_query->is_home = true;
	}

	// If paginated posts, the global $paged needs to be set to make pagination work.
	if ( $main_query->get( 'paged' ) ) {
		$GLOBALS['paged'] = $main_query->get( 'paged' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}

/**
 * Retrieves the link for a page number.
 *
 * @global WP_Rewrite $wp_rewrite
 *
 * @param string $link    The page number link.
 * @param int    $pagenum Optional. Page ID. Default 1.
 * @return string The link URL for the given page number.
 */
function evie_ajax_pagination_get_pagenum_link( $link = '', $pagenum = 1 ) {
	global $wp_rewrite;

	$pagenum = (int) $pagenum;

	$base = evie_ajax_pagination_get_link_base();

	$request = remove_query_arg( array( 'paged', 'page' ), $base );

	$home_root = parse_url( home_url() ); // phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
	$home_root = ( isset( $home_root['path'] ) ) ? $home_root['path'] : '';
	$home_root = preg_quote( $home_root, '|' );

	$request = preg_replace( '|^' . $home_root . '|i', '', $request );
	$request = preg_replace( '|^/+|', '', $request );

	if ( ! $wp_rewrite->using_permalinks() || is_admin() ) {

		if ( $pagenum > 1 ) {
			$link = add_query_arg( 'paged', $pagenum, $base );
		} else {
			$link = $request;
		}
	} else {

		$qs_regex = '|\?.*?$|';
		preg_match( $qs_regex, $request, $qs_match );

		if ( ! empty( $qs_match[0] ) ) {
			$query_string = $qs_match[0];
			$request      = preg_replace( $qs_regex, '', $request );
		} else {
			$query_string = '';
		}

		$request = preg_replace( "|$wp_rewrite->pagination_base/\d+/?$|", '', $request );
		$request = preg_replace( '|^' . preg_quote( $wp_rewrite->index, '|' ) . '|i', '', $request );
		$request = ltrim( $request, '/' );

		if ( $pagenum > 1 ) {
			$request = ( ( ! empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( $wp_rewrite->pagination_base . '/' . $pagenum, 'paged' );
		}

		$link = $request . $query_string;

	}

	return esc_url_raw( $link );
}

/**
 * Gets the link base URL.
 *
 * @return string The link base URL.
 */
function evie_ajax_pagination_get_link_base() {
	$base = wp_get_referer();
	if ( empty( $base ) ) {
		// If front page is set to display a static page, get the URL of the posts page.
		if ( 'page' === get_option( 'show_on_front' ) ) {
			$base = get_permalink( get_option( 'page_for_posts' ) );
		} else {
			$base = get_home_url();
		}
	}
	return $base;
}

/**
 * Appends settings to the current plugin settings.
 *
 * @param array $settings The current settings of the plugin.
 * @return array An array list of the plugin settings.
 */
function evie_ajax_pagination_add_settings( $settings = array() ) {
	$settings['strings']['prev'] = esc_html__( 'Newer', 'evie-xt' );
	$settings['strings']['next'] = esc_html__( 'Older', 'evie-xt' );
	return $settings;
}

add_filter( 'evie_settings', 'evie_ajax_pagination_add_settings' );

/**
 * Removes default link pages arguments from the theme.
 *
 * @since 1.0.6
 */
function evie_ajax_pagination_remove_default_link_pages_args() {
	if ( has_filter( 'wp_link_pages_args', 'evie_link_pages_args' ) ) {
		remove_filter( 'wp_link_pages_args', 'evie_link_pages_args' );
	}
}

add_filter( 'init', 'evie_ajax_pagination_remove_default_link_pages_args' );

/**
 * Changes the pagination type from number to next.
 *
 * @param array $args An array of arguments for page links for paginated posts.
 * @return array An array of arguments for page links for paginated posts.
 */
function evie_ajax_pagination_link_pages_args( $args = array() ) {
	$args['next_or_number'] = 'next';

	$args['nextpagelink'] = esc_html__( 'Load more', 'evie-xt' ) . '<i></i>';

	$args['before'] = '<nav class="navigation pagination post-pagination loadmore-pagination infinite-scroll"><div class="nav-links">';

	$args['after'] = '<div class="post-loader"></div></div></nav>';

	return $args;
}

add_filter( 'wp_link_pages_args', 'evie_ajax_pagination_link_pages_args' );

/**
 * Replaces a Previous page link from the paginated posts.
 *
 * @global int $page
 *
 * @param string $link The page number HTML output.
 * @param int    $i    Page number for paginated posts' page links.
 * @return string The page number HTML output.
 */
function evie_ajax_pagination_link_pages_link( $link, $i ) {
	global $page;

	if ( $page - 1 === $i ) {
		$link = '';
	}

	return $link;
}

add_filter( 'wp_link_pages_link', 'evie_ajax_pagination_link_pages_link', 10, 2 );

/**
 * Changes the label of the next and previous navigation text.
 *
 * @param array $args Default comments navigation arguments.
 * @return array New comments navigation arguments.
 */
function evie_ajax_pagination_comments_navigation_args( $args = array() ) {

	$args['class'] = 'loadmore-pagination';

	$label = esc_html__( 'Load more', 'evie-xt' ) . '<i></i>';

	$args['prev_text'] = $label;

	$args['next_text'] = $label;

	return $args;
}

add_filter( 'evie_comments_navigation_args', 'evie_ajax_pagination_comments_navigation_args' );

/**
 * Returns whether the Single Page module is allowed.
 *
 * @return bool Whether the module is allowed.
 */
function evie_ajax_pagination_is_active() {
	// Admin or preview or customize preview.
	if ( is_admin() || is_preview() || is_customize_preview() ) {
		return false;
	}

	// REST API request.
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return false;
	}

	// Feed.
	if ( is_feed() ) {
		return false;
	}

	// Printpage.
	if ( get_query_var( 'print' ) || get_query_var( 'printpage' ) ) {
		return false;
	}

	return true;
}

/**
 * Registers the Posts REST API endpoint.
 */
function evie_ajax_pagination_register_api_routes() {
	$post_controller = new Evie_REST_Post_Controller();
	$post_controller->register_routes();

	$posts_controller = new Evie_REST_Posts_Controller();
	$posts_controller->register_routes();

	$posts_renderer_controller = new Evie_REST_Posts_Renderer_Controller();
	$posts_renderer_controller->register_routes();

	$comments_renderer_controller = new Evie_REST_Comments_Renderer_Controller();
	$comments_renderer_controller->register_routes();

	$block_renderer_controller = new Evie_REST_Block_Renderer_Controller();
	$block_renderer_controller->register_routes();
}

add_action( 'rest_api_init', 'evie_ajax_pagination_register_api_routes' );

/**
 * Enqueues the scripts and stylesheets.
 */
function evie_ajax_pagination_enqueue_scripts() {
	if ( evie_ajax_pagination_is_active() ) {

		wp_enqueue_style( 'evie-ajax-pagination', plugins_url( 'css/style.css', __FILE__ ), array(), EVIE_XT_VERSION );
		wp_style_add_data( 'evie-ajax-pagination', 'rtl', 'replace' );

		wp_enqueue_script( 'evie-ajax-pagination', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension-api', 'evie-router' ), EVIE_XT_VERSION, true );

	}
}

add_action( 'wp_enqueue_scripts', 'evie_ajax_pagination_enqueue_scripts' );
