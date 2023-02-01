<?php
/**
 * Page Renderer REST API: Evie_REST_Page_Renderer_Controller class
 *
 * @package    Evie_XT
 * @subpackage Modules/Single_Page/REST_API
 * @version    1.0.0
 */

/**
 * Controller which provides REST endpoint for rendering a page in Single Page Application.
 *
 * @see WP_REST_Controller
 */
class Evie_REST_Page_Renderer_Controller extends WP_REST_Controller {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		$this->namespace = 'evie/v1';
		$this->rest_base = 'page-renderer';
	}

	/**
	 * Registers the necessary REST API routes.
	 *
	 * @access public
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_item' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
			)
		);

	}

	/**
	 * Checks if a given request has access to read blocks.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function get_item_permissions_check( $request ) {
		$post = evie_ajax_pagination_get_post( $request );
		if ( null !== $post ) { // If single post or page, check the post permission.
			// Check post password, and return error if invalid.
			if ( ! empty( $request['password'] ) && ! hash_equals( $post->post_password, $request['password'] ) ) {
				return new WP_Error(
					'rest_post_incorrect_password',
					__( 'Incorrect post password.', 'evie-xt' ),
					array( 'status' => 403 )
				);
			} else {
				return $this->check_read_permission( $post );
			}
		}

		return true;
	}

	/**
	 * Checks if a post can be read.
	 *
	 * Correctly handles posts with the inherit status.
	 *
	 * @param WP_Post $post Post object.
	 * @return bool Whether the post can be read.
	 */
	public function check_read_permission( $post ) {
		$post_type = get_post_type_object( $post->post_type );
		if ( ! $this->check_is_post_type_allowed( $post_type ) ) {
			return false;
		}

		// Is the post readable?
		if ( 'publish' === $post->post_status || current_user_can( 'read_post', $post->ID ) ) {
			return true;
		}

		$post_status_obj = get_post_status_object( $post->post_status );
		if ( $post_status_obj && $post_status_obj->public ) {
			return true;
		}

		// Can we read the parent if we're inheriting?
		if ( 'inherit' === $post->post_status && $post->post_parent > 0 ) {
			$parent = get_post( $post->post_parent );
			if ( $parent ) {
				return $this->check_read_permission( $parent );
			}
		}

		/*
		 * If there isn't a parent, but the status is set to inherit, assume
		 * it's published (as per get_post_status()).
		 */
		if ( 'inherit' === $post->post_status ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if a given post type can be viewed or managed.
	 *
	 * @param WP_Post_Type|string $post_type Post type name or object.
	 * @return bool Whether the post type is allowed in REST.
	 */
	protected function check_is_post_type_allowed( $post_type ) {
		if ( ! is_object( $post_type ) ) {
			$post_type = get_post_type_object( $post_type );
		}

		if ( ! empty( $post_type ) && ! empty( $post_type->show_in_rest ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determines the allowed query_vars for a get_item() response and prepares
	 * them for WP_Query.
	 *
	 * @access protected
	 *
	 * @param WP_REST_Request $request Optional. Full details about the request.
	 * @return array Item query arguments.
	 */
	protected function prepare_item_query( $request = null ) {

		// Get items query arguments.
		$query_args = evie_ajax_pagination_prepare_items_query( $request );

		// Set password query.
		if ( isset( $request['password'] ) ) {
			$query_args['post_password'] = $request['password'];
		}

		// Set cpage query.
		if ( isset( $request['cpage'] ) ) {
			$query_args['cpage'] = $request['cpage'];
		}

		return $query_args;
	}

	/**
	 * Retrieves a single post or page.
	 *
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_item( $request ) {

		$query_args = $this->prepare_item_query( $request );

		evie_ajax_pagination_get_posts( $query_args );

		$data = array();

		$post_link = '';

		add_filter( 'get_pagenum_link', 'evie_ajax_pagination_get_pagenum_link', 10, 2 );

		if ( is_404() ) {

			$data = $this->get_404_data();

		} else {
			if ( is_singular() ) {

				the_post();

				if ( is_post_type_viewable( get_post_type_object( get_post_type() ) ) ) {
					$post_link = get_permalink();
				}

				$data = $this->get_single_data();

			} else {

				$data = $this->get_archive_data();

			}
		}

		$response = rest_ensure_response( $data );

		if ( ! empty( $post_link ) ) {
			$response->link_header( 'alternate', $post_link, array( 'type' => 'text/html' ) );
		}

		wp_reset_postdata();

		return $response;
	}

	/**
	 * Returns document title for the current page.
	 *
	 * @access protected
	 *
	 * @return string Tag with the document title.
	 */
	protected function get_document_title() {
		return wp_get_document_title();
	}

	/**
	 * Retrieves the classes for the body element as an array.
	 *
	 * @access protected
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return string CSS class for body element.
	 */
	protected function get_body_class( $class = '' ) {
		if ( is_customize_preview() ) {
			define( 'IFRAME_REQUEST', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
		}

		$classes = get_body_class( $class );

		if ( $this->is_admin_bar_showing() ) {
			$classes[] = 'admin-bar';
		}

		// Separates classes with a single space, collates classes for body element.
		return join( ' ', $classes );
	}

	/**
	 * Determines whether the admin bar should be showing.
	 *
	 * @return bool Whether the admin bar should be showing.
	 */
	protected function is_admin_bar_showing() {
		$show_admin_bar;

		if ( ! isset( $show_admin_bar ) ) {
			if ( ! is_user_logged_in() ) {
				$show_admin_bar = false;
			} else {
				$admin_bar = get_user_option( 'show_admin_bar_front' );
				if ( false === $admin_bar || 'true' === $admin_bar ) {
					$show_admin_bar = true;
				}
			}
		}

		/** This filter is documented in wp-includes/admin-bar.php */
		$show_admin_bar = apply_filters( 'show_admin_bar', $show_admin_bar ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		return $show_admin_bar;
	}

	/**
	 * Returns the page content and information for the page.
	 *
	 * @access protected
	 *
	 * @param string $data The data to add to the response.
	 *
	 * @return array An array of the content for the page.
	 */
	protected function prepare_data_for_response( $data = '' ) {
		return array(
			'rendered'     => $data,
			'title'        => $this->get_document_title(),
			'bodyClass'    => $this->get_body_class(),
			'menuClasses'  => $this->get_nav_menu_classes(),
			'customStyles' => $this->get_custom_styles(),
			'editMenu'     => $this->get_admin_bar_edit_menu(),
		);
	}

	/**
	 * Returns custom CSS styles for the current post.
	 *
	 * @return string Custom CSS styles for the current post.
	 */
	protected function get_custom_styles() {

		if ( function_exists( 'flextension_editor_get_custom_css' ) ) {
			return flextension_editor_get_custom_css();
		}

		return '';
	}

	/**
	 * Returns the content and information for the single post or page.
	 *
	 * @access protected
	 *
	 * @return array An array of the content for the page.
	 */
	protected function get_single_data() {
		return $this->prepare_data_for_response( $this->get_single() );
	}

	/**
	 * Returns the page content and information for the Archive page.
	 *
	 * @access protected
	 *
	 * @return array An array of the content for the Archive page.
	 */
	protected function get_archive_data() {
		return $this->prepare_data_for_response( $this->get_archive() );
	}

	/**
	 * Returns the page content and information for the 404 page.
	 *
	 * @access protected
	 *
	 * @return array An array of the content for the 404 page.
	 */
	protected function get_404_data() {
		return $this->prepare_data_for_response( $this->get_404() );
	}

	/**
	 * Returns the admin bar edit menu.
	 *
	 * @access protected
	 *
	 * @return array An array of the edit menu.
	 */
	protected function get_admin_bar_edit_menu() {

		$current_object = get_queried_object();

		if ( empty( $current_object ) ) {
			return;
		}

		$menu_item = '';

		if ( ! empty( $current_object->post_type ) ) {
			$post_type_object = get_post_type_object( $current_object->post_type );
			$edit_post_link   = get_edit_post_link( $current_object->ID, false );
			if ( $post_type_object
				&& $edit_post_link
				&& current_user_can( 'edit_post', $current_object->ID )
				&& $post_type_object->show_in_admin_bar ) {
				$menu_item = array(
					'id'    => 'edit',
					'title' => $post_type_object->labels->edit_item,
					'href'  => $edit_post_link,
				);
			}
		} elseif ( ! empty( $current_object->taxonomy ) ) {
			$tax            = get_taxonomy( $current_object->taxonomy );
			$edit_term_link = get_edit_term_link( $current_object->term_id, $current_object->taxonomy );
			if ( $tax && $edit_term_link && current_user_can( 'edit_term', $current_object->term_id ) ) {
				$menu_item = array(
					'id'    => 'edit',
					'title' => $tax->labels->edit_item,
					'href'  => $edit_term_link,
				);
			}
		}

		return $menu_item;
	}

	/**
	 * Returns a navigation menu items classes.
	 *
	 * @access protected
	 *
	 * @return array An array list of menu items classes.
	 */
	protected function get_nav_menu_classes() {

		$nav_menu_class = wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'echo'           => false,
				'items_wrap'     => '%3$s',
				'walker'         => new Evie_Walker_Nav_Menu(),
			)
		);

		if ( $nav_menu_class ) {
			$nav_menu_class = rtrim( $nav_menu_class, ', ' );
		}

		return explode( ',', $nav_menu_class );
	}

	/**
	 * Returns HTML output for the posts.
	 *
	 * @access protected
	 *
	 * @return string The HTML output for the posts.
	 */
	protected function get_archive() {
		return evie_single_page_get_template_part( 'archive' );
	}

	/**
	 * Returns the content for the single post or page.
	 *
	 * @access protected
	 *
	 * @return string The content for the single post or page.
	 */
	protected function get_single() {
		return evie_single_page_get_template_part( 'single' );
	}

	/**
	 * Returns the content for the error 404 page.
	 *
	 * @access protected
	 *
	 * @return string The content for the error 404 page.
	 */
	protected function get_404() {
		return evie_single_page_get_template_part( '404' );
	}

}
