<?php
/**
 * Followers REST API
 *
 * Displays the list of followers and following.
 *
 * @package    Flextension
 * @subpackage Modules/Author/REST_API
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Followers API class.
 */
class Flextension_Followers_API {

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
			'/followers',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_followers' ),
				'permission_callback' => array( $this, 'get_followers_permissions_check' ),
			)
		);

		register_rest_route(
			'flextension/v1',
			'/following',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_following' ),
				'permission_callback' => array( $this, 'get_following_permissions_check' ),
			)
		);

	}

	/**
	 * Checks if a given request has access to view the content.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return bool|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function get_followers_permissions_check( $request ) {
		$author_id = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		return flextension_author_can_follow( $author_id );
	}

	/**
	 * Checks if a given request has access to view the content.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return bool|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function get_following_permissions_check( $request ) {
		$author_id = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		return 0 !== $author_id && flextension_author_follow_enabled();
	}

	/**
	 * Retrieves a list of followers.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_followers( $request ) {

		$author_id = isset( $request['id'] ) ? absint( $request['id'] ) : 0;

		if ( 0 === $author_id ) {
			return new WP_Error( 'rest_author_invalid_author_id', esc_html__( 'Invalid author ID.', 'flextension' ), array( 'status' => 200 ) );
		}

		$data = array(
			'id' => $author_id,
		);

		$page = isset( $request['page'] ) ? absint( $request['page'] ) : 1;
		if ( $page > 1 ) {
			$data['rendered'] = flextension_author_get_followers_list( 'followers', $author_id, $page );
		} else {
			$data['rendered'] = $this->get_content( 'followers', $author_id );
		}

		return rest_ensure_response( $data );
	}

	/**
	 * Retrieves a list of following.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_following( $request ) {

		$user_id = isset( $request['id'] ) ? absint( $request['id'] ) : 0;

		if ( 0 === $user_id ) {
			return new WP_Error( 'rest_author_invalid_user_id', esc_html__( 'Invalid user ID.', 'flextension' ), array( 'status' => 200 ) );
		}

		$data = array(
			'id' => $user_id,
		);

		$page = isset( $request['page'] ) ? absint( $request['page'] ) : 1;
		if ( $page > 1 ) {
			$data['rendered'] = flextension_author_get_followers_list( 'following', $author_id, $page );
		} else {
			$data['rendered'] = $this->get_content( 'following', $user_id );
		}

		return rest_ensure_response( $data );
	}

	/**
	 * Returns the rendered content.
	 *
	 * @param string $template A template name.
	 * @param int    $id       User ID.
	 * @return string The rendered content.
	 */
	protected function get_content( $template = 'followers', $id = 0 ) {
		ob_start();
		flextension_get_template( plugin_dir_path( __DIR__ ) . 'templates/' . $template, null, array( 'id' => $id ) );
		$content = ob_get_clean();
		return $content;
	}

}
