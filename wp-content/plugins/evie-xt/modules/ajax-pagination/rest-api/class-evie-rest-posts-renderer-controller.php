<?php
/**
 * Posts Renderer REST API
 *
 * @package    Evie_XT
 * @subpackage Modules/AJAX_Pagination/REST_API
 * @version    1.0.0
 */

/**
 * Controller which provides REST endpoint for rendering a list of posts.
 *
 * @see WP_REST_Controller
 */
class Evie_REST_Posts_Renderer_Controller extends WP_REST_Controller {

	/**
	 * Constructs the controller.
	 */
	public function __construct() {
		$this->namespace = 'evie/v1';
		$this->rest_base = 'posts-renderer';
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
					'permission_callback' => '__return_true',
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Returns a response object for the posts section.
	 *
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_item( $request ) {

		$query_args = evie_ajax_pagination_prepare_items_query( $request );

		evie_ajax_pagination_get_posts( $query_args );

		add_filter( 'get_pagenum_link', 'evie_ajax_pagination_get_pagenum_link', 10, 2 );

		ob_start();

		evie_posts();

		$output = ob_get_clean();

		$data = array(
			'rendered' => $output,
		);

		wp_reset_postdata();

		return rest_ensure_response( $data );

	}

	/**
	 * Retrieves posts's section output schema, conforming to JSON Schema.
	 *
	 * @return array Item schema data.
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->schema;
		}

		$this->schema = array(
			'$schema'    => 'http://json-schema.org/schema#',
			'title'      => 'rendered-posts-section',
			'type'       => 'object',
			'properties' => array(
				'rendered' => array(
					'description' => esc_html__( 'The rendered posts section.', 'evie-xt' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view' ),
				),
			),
		);

		return $this->schema;
	}
}
