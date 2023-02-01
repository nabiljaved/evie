<?php
/**
 * Categories Checklist REST API
 *
 * @package    Flextension
 * @subpackage Modules/Featured_Categories/REST_API
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Categories Checklist API class.
 *
 * @since 1.1.3
 */
class Flextension_Categories_Checklist_API {

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
			'/categories-checklist',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_item' ),
				'permission_callback' => '__return_true',
			)
		);

	}

	/**
	 * Retrieves terms checklist.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response Response object.
	 */
	public function get_item( $request ) {
		if ( ! function_exists( 'wp_terms_checklist' ) ) {
			include_once ABSPATH . 'wp-admin/includes/template.php';
		}

		$taxonomy = isset( $request['taxonomy'] ) ? $request['taxonomy'] : 'category';
		$name     = isset( $request['name'] ) ? $request['name'] : 'terms';

		$data = array(
			'taxonomy' => $taxonomy,
			'rendered' => flextension_terms_checklist(
				array(
					'taxonomy'      => $taxonomy,
					'checkbox_name' => $name,
				)
			),
		);

		return rest_ensure_response( $data );
	}

}
