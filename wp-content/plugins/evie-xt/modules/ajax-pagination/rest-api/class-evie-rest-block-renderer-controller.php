<?php
/**
 * Block Renderer REST API
 *
 * @package    Evie_XT
 * @subpackage Modules/AJAX_Pagination/REST_API
 * @version    1.0.0
 */

/**
 * Controller which provides REST endpoint for rendering a block.
 *
 * @see WP_REST_Controller
 */
class Evie_REST_Block_Renderer_Controller extends WP_REST_Controller {

	/**
	 * Constructs the controller.
	 */
	public function __construct() {
		$this->namespace = 'evie/v1';
		$this->rest_base = 'block-renderer';
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
					'args'                => array(
						'context'    => $this->get_context_param( array( 'default' => 'view' ) ),
						'attributes' => array(
							'description' => esc_html__( 'Attributes for the block.', 'evie-xt' ),
							'type'        => 'object',
							'default'     => array(),
						),
						'block'      => array(
							'description' => esc_html__( 'Unique ID for the block.', 'evie-xt' ),
							'type'        => 'string',
						),
						'id'         => array(
							'description' => esc_html__( 'ID of the post.', 'evie-xt' ),
							'type'        => 'integer',
						),
						'path'       => array(
							'description' => esc_html__( 'Path of the post.', 'evie-xt' ),
							'type'        => 'string',
						),
					),
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
	 * Retrieves a block from the post content.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Block_Parser_Block|null Response object on success, or WP_Error object on failure.
	 */
	public function get_block( $request ) {
		$matched  = null;
		$block_id = isset( $request['block'] ) ? $request['block'] : '';
		if ( ! empty( $block_id ) ) {
			$post = evie_ajax_pagination_get_post( $request );
			if ( null !== $post ) {
				$blocks  = parse_blocks( $post->post_content );
				$matched = evie_ajax_pagination_get_block( $blocks, 'blockId', $block_id );
			} else {
				return new WP_Error(
					'rest_post_invalid_post',
					__( 'Invalid post ID or post name.', 'evie-xt' ),
					array( 'status' => 404 )
				);
			}
		}

		return $matched;
	}

	/**
	 * Returns block output from block's registered render_callback.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_item( $request ) {
		$block = $this->get_block( $request );

		if ( null === $block ) {
			return new WP_Error(
				'block_invalid',
				esc_html__( 'Invalid block.', 'evie-xt' ),
				array(
					'status' => 404,
				)
			);
		}

		$block['attrs'] = wp_parse_args( $request->get_param( 'attributes' ), $block['attrs'] );

		add_filter( 'get_pagenum_link', 'evie_ajax_pagination_get_pagenum_link', 10, 2 );

		// Render using render_block to ensure all relevant filters are used.
		$data = array(
			'rendered' => render_block( $block ),
		);

		return rest_ensure_response( $data );
	}

	/**
	 * Retrieves block's output schema, conforming to JSON Schema.
	 *
	 * @return array Item schema data.
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->schema;
		}

		$this->schema = array(
			'$schema'    => 'http://json-schema.org/schema#',
			'title'      => 'rendered-block',
			'type'       => 'object',
			'properties' => array(
				'rendered' => array(
					'description' => esc_html__( 'The rendered block.', 'evie-xt' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view' ),
				),
			),
		);

		return $this->schema;
	}
}
