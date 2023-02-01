<?php
/**
 * Lightbox Login REST API
 *
 * Adds a lightbox login button to the post thumbnail.
 * Displays the lightbox login content in the lightbox when clicking on a lightbox login button.
 *
 * @package    Flextension
 * @subpackage Modules/Lightbox_Login/REST_API
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lightbox Login API class.
 */
class Flextension_Lightbox_Login_API {

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
			'/lightbox-login',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_item' ),
				'permission_callback' => '__return_true',
			)
		);

	}

	/**
	 * Retrieves a single project.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_item( $request ) {

		$form = isset( $request['form'] ) ? $request['form'] : 'login';

		$redirect = isset( $request['redirect_to'] ) ? $request['redirect_to'] : home_url();

		$data = array(
			'rendered' => $this->get_form( $form, $redirect ),
		);

		$response = rest_ensure_response( $data );

		return $response;
	}

	/**
	 * Returns the form content.
	 *
	 * @param string $form     The form name.
	 * @param string $redirect URL to redirect to.
	 * @return string The form content.
	 */
	protected function get_form( $form = '', $redirect = '' ) {
		ob_start();
		flextension_get_template( plugin_dir_path( __DIR__ ) . 'templates/' . $form, '', array( 'redirect' => $redirect ) );
		$content = ob_get_clean();
		return $content;
	}

}
