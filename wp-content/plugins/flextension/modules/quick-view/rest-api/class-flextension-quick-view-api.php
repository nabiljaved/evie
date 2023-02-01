<?php
/**
 * Quick View REST API
 *
 * Adds a quick view button to the post thumbnail.
 * Displays the quick view content in the lightbox when clicking on a quick view button.
 *
 * @package    Flextension
 * @subpackage Modules/Quick_View/REST_API
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Quick View API class.
 */
class Flextension_Quick_View_API {

	/**
	 * Initializes the class, adds actions and filters.
	 */
	public function __construct() {

		add_action( 'rest_api_init', array( $this, 'register_api_routes' ) );

	}

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @see register_rest_route()
	 */
	public function register_api_routes() {

		register_rest_route(
			'flextension/v1',
			'/quick-view',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_item' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
			)
		);

	}

	/**
	 * Get the post, if the ID is valid.
	 *
	 * @param int $id Supplied ID.
	 * @return WP_Post|WP_Error Post object if ID is valid, WP_Error otherwise.
	 */
	protected function get_post( $id ) {
		$error = new WP_Error(
			'rest_post_invalid_id',
			esc_html__( 'Invalid post ID.', 'flextension' ),
			array( 'status' => 404 )
		);

		if ( (int) $id <= 0 ) {
			return $error;
		}

		$post = get_post( (int) $id );
		if ( empty( $post ) || empty( $post->ID ) ) {
			return $error;
		}

		return $post;
	}

	/**
	 * Checks if a given request has access to read a post.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return bool|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function get_item_permissions_check( $request ) {
		$post = $this->get_post( $request['id'] );
		if ( is_wp_error( $post ) ) {
			return $post;
		}

		if ( $post && ! empty( $request['password'] ) ) {
			// Check post password, and return error if invalid.
			if ( ! hash_equals( $post->post_password, $request['password'] ) ) {
				return new WP_Error(
					'rest_post_incorrect_password',
					esc_html__( 'Incorrect post password.', 'flextension' ),
					array( 'status' => 403 )
				);
			}
		}

		if ( $post ) {
			return $this->check_read_permission( $post );
		}

		return true;
	}

	/**
	 * Retrieves a single project.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_item( $request ) {

		$post_id = isset( $request['id'] ) ? absint( $request['id'] ) : 0;

		if ( 0 === $post_id ) {
			return new WP_Error( 'rest_post_invalid_post_id', esc_html__( 'Invalid post ID.', 'flextension' ), array( 'status' => 200 ) );
		}

		$post_type = isset( $request['postType'] ) ? $request['postType'] : '';

		if ( empty( $post_type ) ) {
			$post = get_post( $post_id );
			if ( $post ) {
				$post_type = $post->post_type;
			}
		}

		$posts = new WP_Query(
			array(
				'p'         => $post_id,
				'post_type' => $post_type,
			)
		);

		if ( 'post' === $post_type ) {
			$post_type = '';
		}

		$data = array();

		if ( $posts->have_posts() ) {

			$posts->the_post();

			$data = array(
				'id'       => get_the_ID(),
				'rendered' => $this->get_content( $post_type ),
			);

		}

		wp_reset_postdata();

		/**
		 * Filters the rest post content object.
		 *
		 * @param string $data The post rendered content.
		 */
		$data = apply_filters( 'flextension_rest_quick_view_content', $data, $post_id, $post_type );

		$response = rest_ensure_response( $data );

		return $response;
	}

	/**
	 * Returns the post content.
	 *
	 * @param string $post_type The post type.
	 * @return string The post content.
	 */
	protected function get_content( $post_type = '' ) {
		ob_start();
		flextension_get_template( plugin_dir_path( __DIR__ ) . 'templates/quick-view', $post_type );
		$content = ob_get_clean();
		return $content;
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

}
