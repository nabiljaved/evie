<?php
/**
 * Single Post REST API
 *
 * @package    Evie_XT
 * @subpackage Modules/AJAX_Pagination/REST_API
 * @version    1.0.0
 */

/**
 * Controller which provides REST endpoint for rendering a single post.
 *
 * @see WP_REST_Controller
 */
class Evie_REST_Post_Controller extends WP_REST_Controller {

	/**
	 * Constructs the controller.
	 */
	public function __construct() {
		$this->namespace = 'evie/v1';
		$this->rest_base = 'post';
	}

	/**
	 * Registers the necessary REST API routes, one for each dynamic block.
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
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
		if ( null !== $post ) {
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
		} else {
			return new WP_Error(
				'rest_post_invalid_post',
				__( 'Invalid post ID or post name.', 'evie-xt' ),
				array( 'status' => 404 )
			);
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
	 * Retrieves a single post.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_item( $request ) {
		global $numpages;

		$query_args = evie_ajax_pagination_prepare_items_query( $request );

		evie_ajax_pagination_get_posts( $query_args );

		$data = array();

		$alternate_link = '';
		$next_link      = '';

		if ( have_posts() ) {

			the_post();

			$content = get_the_content();

			/** This filter is documented in wp-includes/post-template.php */
			$content = apply_filters( 'the_content', $content ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			$content = str_replace( ']]>', ']]&gt;', $content );

			$data = array(
				'rendered' => $content,
			);

			if ( is_post_type_viewable( get_post_type_object( $post ) ) ) {
				$alternate_link = get_permalink( $post );
			}

			$next = max( 1, (int) $query_args['page'] ) + 1;
			if ( $next <= $numpages ) {
				$next_link = $this->get_link_page( $next, $post );
			}
		}

		$response = rest_ensure_response( $data );

		if ( ! empty( $alternate_link ) ) {
			$response->link_header( 'alternate', $alternate_link, array( 'type' => 'text/html' ) );
		}

		if ( ! empty( $next_link ) ) {
			$response->link_header( 'next', $next_link );
		}

		wp_reset_postdata();

		return $response;
	}

	/**
	 * Returns the page link for the post.
	 *
	 * @access private
	 *
	 * @global WP_Rewrite $wp_rewrite WordPress rewrite component.
	 *
	 * @param int $number Page number.
	 * @param int $post   The post object.
	 * @return string The page link for the post.
	 */
	private function get_link_page( $number, $post ) {
		global $wp_rewrite;
		$query_args = array();

		if ( 1 === $number ) {
			$url = get_permalink();
		} else {
			if ( ! get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending' ), true ) ) {
				$url = add_query_arg( 'page', $number, get_permalink() );
			} elseif ( 'page' === get_option( 'show_on_front' ) && absint( get_option( 'page_on_front' ) ) === absint( $post->ID ) ) {
				$url = trailingslashit( get_permalink() ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $number, 'single_paged' );
			} else {
				$url = trailingslashit( get_permalink() ) . user_trailingslashit( $number, 'single_paged' );
			}
		}

		return $url;
	}

	/**
	 * Retrieves posts output schema, conforming to JSON Schema.
	 *
	 * @return array Item schema data.
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->schema;
		}

		$this->schema = array(
			'$schema'    => 'http://json-schema.org/schema#',
			'title'      => 'rendered-post',
			'type'       => 'object',
			'properties' => array(
				'rendered' => array(
					'description' => esc_html__( 'The rendered post.', 'evie-xt' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view' ),
				),
			),
		);

		return $this->schema;
	}
}
