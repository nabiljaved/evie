<?php
/**
 * Comments Renderer REST API
 *
 * @package    Evie_XT
 * @subpackage Modules/AJAX_Pagination/REST_API
 * @version    1.0.0
 */

/**
 * Controller which provides REST endpoint for rendering comments in Single Page Application.
 *
 * @see WP_REST_Controller
 */
class Evie_REST_Comments_Renderer_Controller extends WP_REST_Controller {

	/**
	 * Instance of a comment meta fields object.
	 *
	 * @var WP_REST_Comment_Meta_Fields
	 */
	protected $meta;

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		$this->namespace = 'evie/v1';
		$this->rest_base = 'comments';

		$this->meta = new WP_REST_Comment_Meta_Fields();
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
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),

			)
		);

	}

	/**
	 * Checks if a given request has access to read posts.
	 *
	 * @access public
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function get_items_permissions_check( $request ) {

		$post = null;

		if ( ! empty( $request['post'] ) ) {
			$post = get_post( $request['post'] );
		} elseif ( ! empty( $request['path'] ) ) {
			$post = get_page_by_path( $request['path'], OBJECT, array( 'post', 'page' ) );
		}

		if ( $post && ! $this->check_read_post_permission( $post, $request ) ) {
			return new WP_Error( 'rest_cannot_read_post', esc_html__( 'Sorry, you are not allowed to read the post for this comment.', 'evie-xt' ), array( 'status' => rest_authorization_required_code() ) );
		} elseif ( ! $post && ! current_user_can( 'moderate_comments' ) ) {
			return new WP_Error( 'rest_cannot_read', esc_html__( 'Sorry, you are not allowed to read comments without a post.', 'evie-xt' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Checks if the post can be read.
	 *
	 * Correctly handles posts with the inherit status.
	 *
	 * @access protected
	 *
	 * @param WP_Post         $post    Post object.
	 * @param WP_REST_Request $request Request data to check.
	 * @return bool Whether post can be read.
	 */
	protected function check_read_post_permission( $post, $request ) {
		$posts_controller = new WP_REST_Posts_Controller( $post->post_type );
		$post_type        = get_post_type_object( $post->post_type );

		if ( post_password_required( $post ) ) {
			$result = current_user_can( $post_type->cap->edit_post, $post->ID );
		} else {
			$result = $posts_controller->check_read_permission( $post );
		}

		return $result;
	}

	/**
	 * Retrieves a collection of posts.
	 *
	 * @access public
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_items( $request ) {

		$args = array();

		// Set author name query.
		if ( isset( $request['post'] ) ) {
			$args['id'] = $request['post'];
		}

		if ( isset( $request['path'] ) ) {
			$args['path'] = $request['path'];
		}

		$page = isset( $request['page'] ) ? $request['page'] : 0;

		$query_args = evie_ajax_pagination_prepare_items_query( $args );

		evie_ajax_pagination_get_posts( $query_args );

		$data = array();

		$total_comments = 0;
		$max_pages      = 0;

		$comment_args = array();

		if ( have_posts() ) {

			the_post();

			// Set page number query.
			$comment_args['page'] = $page;

			$comment_args['echo'] = false;

			// Response data array.
			$data = wp_list_comments( $comment_args );

			$total_comments = get_comments_number();
			$max_pages      = ceil( $total_comments / (int) get_option( 'comments_per_page' ) );
		}

		$response = rest_ensure_response( $data );

		$response->header( 'X-WP-Total', $total_comments );
		$response->header( 'X-WP-TotalPages', $max_pages );
		$response->header( 'X-WP-Order', get_option( 'comment_order' ) );

		if ( $page > 1 ) {
			$prev_page = $page - 1;

			$prev_link = get_comments_pagenum_link( $prev_page );
			$response->link_header( 'prev', $prev_link );
		}

		if ( $max_pages > $page ) {
			$next_page = $page + 1;

			$next_link = get_comments_pagenum_link( $next_page, $max_pages );
			$response->link_header( 'next', $next_link );
		}

		return $response;
	}

	/**
	 * Checks if a given request has access to create a comment.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|bool True if the request has access to create items, error object otherwise.
	 */
	public function create_item_permissions_check( $request ) {
		if ( ! is_user_logged_in() ) {
			if ( get_option( 'comment_registration' ) ) {
				return new WP_Error( 'rest_comment_login_required', esc_html__( 'Sorry, you must be logged in to comment.', 'evie-xt' ), array( 'status' => 401 ) );
			}

			/**
			 * Filter whether comments can be created without authentication.
			 *
			 * Enables creating comments for anonymous users.
			 *
			 * @param bool $allow_anonymous Whether to allow anonymous comments to
			 *                              be created. Default `true`.
			 * @param WP_REST_Request $request Request used to generate the
			 *                                 response.
			 */
			$allow_anonymous = apply_filters( 'evie_rest_allow_anonymous_comments', true, $request );
			if ( ! $allow_anonymous ) {
				return new WP_Error( 'rest_comment_login_required', esc_html__( 'Sorry, you must be logged in to comment.', 'evie-xt' ), array( 'status' => 401 ) );
			}
		}

		// Limit who can set comment `author`, `author_ip` or `status` to anything other than the default.
		if ( isset( $request['author'] ) && get_current_user_id() !== $request['author'] && ! current_user_can( 'moderate_comments' ) ) {
			return new WP_Error(
				'rest_comment_invalid_author',
				/* translators: %s: request parameter */
				sprintf( esc_html__( "Sorry, you are not allowed to edit '%s' for comments.", 'evie-xt' ), 'author' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		if ( isset( $request['author_ip'] ) && ! current_user_can( 'moderate_comments' ) ) {
			if ( empty( $_SERVER['REMOTE_ADDR'] ) || $request['author_ip'] !== $_SERVER['REMOTE_ADDR'] ) {
				return new WP_Error(
					'rest_comment_invalid_author_ip',
					/* translators: %s: request parameter */
					sprintf( esc_html__( "Sorry, you are not allowed to edit '%s' for comments.", 'evie-xt' ), 'author_ip' ),
					array( 'status' => rest_authorization_required_code() )
				);
			}
		}

		if ( isset( $request['status'] ) && ! current_user_can( 'moderate_comments' ) ) {
			return new WP_Error(
				'rest_comment_invalid_status',
				/* translators: %s: request parameter */
				sprintf( esc_html__( "Sorry, you are not allowed to edit '%s' for comments.", 'evie-xt' ), 'status' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		$query_args = array();

		// Set author name query.
		if ( isset( $request['post'] ) ) {
			$query_args['id'] = $request['post'];
		}

		if ( isset( $request['path'] ) ) {
			$query_args['path'] = $request['path'];
		}

		$post = evie_ajax_pagination_get_post( $query_args );

		if ( ! $post ) {
			return new WP_Error( 'rest_comment_invalid_post_id', esc_html__( 'Sorry, you are not allowed to create this comment without a post.', 'evie-xt' ), array( 'status' => 403 ) );
		}

		if ( 'draft' === $post->post_status ) {
			return new WP_Error( 'rest_comment_draft_post', esc_html__( 'Sorry, you are not allowed to create a comment on this post.', 'evie-xt' ), array( 'status' => 403 ) );
		}

		if ( 'trash' === $post->post_status ) {
			return new WP_Error( 'rest_comment_trash_post', esc_html__( 'Sorry, you are not allowed to create a comment on this post.', 'evie-xt' ), array( 'status' => 403 ) );
		}

		if ( ! $this->check_read_post_permission( $post, $request ) ) {
			return new WP_Error( 'rest_cannot_read_post', esc_html__( 'Sorry, you are not allowed to read the post for this comment.', 'evie-xt' ), array( 'status' => rest_authorization_required_code() ) );
		}

		if ( ! comments_open( $post->ID ) ) {
			return new WP_Error( 'rest_comment_closed', esc_html__( 'Sorry, comments are closed for this item.', 'evie-xt' ), array( 'status' => 403 ) );
		}

		return true;
	}

	/**
	 * Prepares a single comment to be inserted into the database.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return array|WP_Error Prepared comment, otherwise WP_Error object.
	 */
	protected function prepare_item_for_database( $request ) {
		$prepared_comment = array();

		/*
		 * Allow the comment_content to be set via the 'content' or
		 * the 'content.raw' properties of the Request object.
		 */
		if ( isset( $request['content'] ) && is_string( $request['content'] ) ) {
			$prepared_comment['comment_content'] = $request['content'];
		} elseif ( isset( $request['content']['raw'] ) && is_string( $request['content']['raw'] ) ) {
			$prepared_comment['comment_content'] = $request['content']['raw'];
		}

		if ( isset( $request['post'] ) ) {
			$prepared_comment['comment_post_ID'] = (int) $request['post'];
		}

		if ( isset( $request['parent'] ) ) {
			$prepared_comment['comment_parent'] = $request['parent'];
		}

		if ( isset( $request['author'] ) ) {
			$user = new WP_User( $request['author'] );

			if ( $user->exists() ) {
				$prepared_comment['user_id']              = $user->ID;
				$prepared_comment['comment_author']       = $user->display_name;
				$prepared_comment['comment_author_email'] = $user->user_email;
				$prepared_comment['comment_author_url']   = $user->user_url;
			} else {
				return new WP_Error( 'rest_comment_author_invalid', esc_html__( 'Invalid comment author ID.', 'evie-xt' ), array( 'status' => 400 ) );
			}
		}

		if ( isset( $request['author_name'] ) ) {
			$prepared_comment['comment_author'] = $request['author_name'];
		}

		if ( isset( $request['author_email'] ) ) {
			$prepared_comment['comment_author_email'] = $request['author_email'];
		}

		if ( isset( $request['author_url'] ) ) {
			$prepared_comment['comment_author_url'] = $request['author_url'];
		}

		if ( isset( $request['author_ip'] ) && current_user_can( 'moderate_comments' ) ) {
			$prepared_comment['comment_author_IP'] = $request['author_ip'];
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) && rest_is_ip_address( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$prepared_comment['comment_author_IP'] = wp_unslash( $_SERVER['REMOTE_ADDR'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		} else {
			$prepared_comment['comment_author_IP'] = '127.0.0.1';
		}

		if ( ! empty( $request['author_user_agent'] ) ) {
			$prepared_comment['comment_agent'] = $request['author_user_agent'];
		} elseif ( $request->get_header( 'user_agent' ) ) {
			$prepared_comment['comment_agent'] = $request->get_header( 'user_agent' );
		}

		if ( ! empty( $request['date'] ) ) {
			$date_data = rest_get_date_with_gmt( $request['date'] );

			if ( ! empty( $date_data ) ) {
				list( $prepared_comment['comment_date'], $prepared_comment['comment_date_gmt'] ) = $date_data;
			}
		} elseif ( ! empty( $request['date_gmt'] ) ) {
			$date_data = rest_get_date_with_gmt( $request['date_gmt'], true );

			if ( ! empty( $date_data ) ) {
				list( $prepared_comment['comment_date'], $prepared_comment['comment_date_gmt'] ) = $date_data;
			}
		}

		/** This filter is documented in wp-includes/rest-api/endpoints/class-wp-rest-comments-controller.php */
		return apply_filters( 'rest_preprocess_comment', $prepared_comment, $request ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound, WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/**
	 * Creates a comment.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response Response object on success, or error object on failure.
	 */
	public function create_item( $request ) {

		if ( empty( $request['post'] ) && ! empty( $request['path'] ) ) {
			$post = get_page_by_path( $request['path'], OBJECT, array( 'post', 'page' ) );
			if ( $post ) {
				$request['post'] = $post->ID;
			}
		}

		if ( empty( $request['post'] ) ) {
			return new WP_Error( 'rest_comment_invalid_post_id', esc_html__( 'Sorry, you are not allowed to create this comment without a post.', 'evie-xt' ), array( 'status' => 403 ) );
		}

		// Do not allow comments to be created with a non-default type.
		if ( ! empty( $request['type'] ) && 'comment' !== $request['type'] ) {
			return new WP_Error( 'rest_invalid_comment_type', esc_html__( 'Cannot create a comment with that type.', 'evie-xt' ), array( 'status' => 400 ) );
		}

		$prepared_comment = $this->prepare_item_for_database( $request );
		if ( is_wp_error( $prepared_comment ) ) {
			return $prepared_comment;
		}

		$prepared_comment['comment_type'] = '';

		/*
		 * Do not allow a comment to be created with missing or empty
		 * comment_content. See wp_handle_comment_submission().
		 */
		if ( empty( $prepared_comment['comment_content'] ) ) {
			return new WP_Error( 'rest_comment_content_invalid', esc_html__( 'Invalid comment content.', 'evie-xt' ), array( 'status' => 400 ) );
		}

		// Setting remaining values before wp_insert_comment so we can use wp_allow_comment().
		if ( ! isset( $prepared_comment['comment_date_gmt'] ) ) {
			$prepared_comment['comment_date_gmt'] = current_time( 'mysql', true );
		}

		// Set author data if the user's logged in.
		$missing_author = empty( $prepared_comment['user_id'] )
			&& empty( $prepared_comment['comment_author'] )
			&& empty( $prepared_comment['comment_author_email'] )
			&& empty( $prepared_comment['comment_author_url'] );

		if ( is_user_logged_in() && $missing_author ) {
			$user = wp_get_current_user();

			$prepared_comment['user_id']              = $user->ID;
			$prepared_comment['comment_author']       = $user->display_name;
			$prepared_comment['comment_author_email'] = $user->user_email;
			$prepared_comment['comment_author_url']   = $user->user_url;
		}

		// Honor the discussion setting that requires a name and email address of the comment author.
		if ( get_option( 'require_name_email' ) ) {
			if ( empty( $prepared_comment['comment_author'] ) || empty( $prepared_comment['comment_author_email'] ) ) {
				return new WP_Error( 'rest_comment_author_data_required', esc_html__( 'Creating a comment requires valid author name and email values.', 'evie-xt' ), array( 'status' => 400 ) );
			}
		}

		if ( ! isset( $prepared_comment['comment_author_email'] ) ) {
			$prepared_comment['comment_author_email'] = '';
		}

		if ( ! isset( $prepared_comment['comment_author_url'] ) ) {
			$prepared_comment['comment_author_url'] = '';
		}

		if ( ! isset( $prepared_comment['comment_agent'] ) ) {
			$prepared_comment['comment_agent'] = '';
		}

		$check_comment_lengths = wp_check_comment_data_max_lengths( $prepared_comment );
		if ( is_wp_error( $check_comment_lengths ) ) {
			$error_code = $check_comment_lengths->get_error_code();
			return new WP_Error( $error_code, esc_html__( 'Comment field exceeds maximum length allowed.', 'evie-xt' ), array( 'status' => 400 ) );
		}

		$prepared_comment['comment_approved'] = wp_allow_comment( $prepared_comment, true );

		if ( is_wp_error( $prepared_comment['comment_approved'] ) ) {
			$error_code    = $prepared_comment['comment_approved']->get_error_code();
			$error_message = $prepared_comment['comment_approved']->get_error_message();

			if ( 'comment_duplicate' === $error_code ) {
				return new WP_Error( $error_code, $error_message, array( 'status' => 409 ) );
			}

			if ( 'comment_flood' === $error_code ) {
				return new WP_Error( $error_code, $error_message, array( 'status' => 400 ) );
			}

			return $prepared_comment['comment_approved'];
		}

		/** This filter is documented in wp-includes/rest-api/endpoints/class-wp-rest-comments-controller.php */
		$prepared_comment = apply_filters( 'rest_pre_insert_comment', $prepared_comment, $request ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound, WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
		if ( is_wp_error( $prepared_comment ) ) {
			return $prepared_comment;
		}

		$comment_id = wp_insert_comment( wp_filter_comment( wp_slash( (array) $prepared_comment ) ) );

		if ( ! $comment_id ) {
			return new WP_Error( 'rest_comment_failed_create', esc_html__( 'Creating comment failed.', 'evie-xt' ), array( 'status' => 500 ) );
		}

		if ( isset( $request['status'] ) ) {
			$this->handle_status_param( $request['status'], $comment_id );
		}

		$comment = get_comment( $comment_id );

		/** This filter is documented in wp-includes/rest-api/endpoints/class-wp-rest-comments-controller.php */
		do_action( 'rest_insert_comment', $comment, $request, true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound, WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound

		$fields_update = $this->update_additional_fields_for_object( $comment, $request );

		if ( is_wp_error( $fields_update ) ) {
			return $fields_update;
		}

		// Response data array.
		$data = wp_list_comments(
			array(
				'echo' => false,
			),
			array( $comment )
		);

		$response = rest_ensure_response( $data );

		$response->header( 'X-WP-Total', number_format_i18n( get_comments_number( (int) $request['post'] ) ) );

		return $response;
	}

	/**
	 * Checks a comment author email for validity.
	 *
	 * Accepts either a valid email address or empty string as a valid comment
	 * author email address. Setting the comment author email to an empty
	 * string is allowed when a comment is being updated.
	 *
	 * @param string          $value   Author email value submitted.
	 * @param WP_REST_Request $request Full details about the request.
	 * @param string          $param   The parameter name.
	 * @return WP_Error|string The sanitized email address, if valid,
	 *                         otherwise an error.
	 */
	public function check_comment_author_email( $value, $request, $param ) {
		$email = (string) $value;
		if ( empty( $email ) ) {
			return $email;
		}

		$check_email = rest_validate_request_arg( $email, $request, $param );
		if ( is_wp_error( $check_email ) ) {
			return $check_email;
		}

		return $email;
	}

	/**
	 * Retrieves the comment's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'comment',
			'type'       => 'object',
			'properties' => array(
				'id'                => array(
					'description' => esc_html__( 'Unique identifier for the object.', 'evie-xt' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
				'author'            => array(
					'description' => esc_html__( 'The ID of the user object, if author was a user.', 'evie-xt' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit', 'embed' ),
				),
				'author_email'      => array(
					'description' => esc_html__( 'Email address for the object author.', 'evie-xt' ),
					'type'        => 'string',
					'format'      => 'email',
					'context'     => array( 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => array( $this, 'check_comment_author_email' ),
						'validate_callback' => null, // skip built-in validation of 'email'.
					),
				),
				'author_ip'         => array(
					'description' => esc_html__( 'IP address for the object author.', 'evie-xt' ),
					'type'        => 'string',
					'format'      => 'ip',
					'context'     => array( 'edit' ),
				),
				'author_name'       => array(
					'description' => esc_html__( 'Display name for the object author.', 'evie-xt' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit', 'embed' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'author_url'        => array(
					'description' => esc_html__( 'URL for the object author.', 'evie-xt' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit', 'embed' ),
				),
				'author_user_agent' => array(
					'description' => esc_html__( 'User agent for the object author.', 'evie-xt' ),
					'type'        => 'string',
					'context'     => array( 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'content'           => array(
					'description' => esc_html__( 'The content for the object.', 'evie-xt' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit', 'embed' ),
					'arg_options' => array(
						'sanitize_callback' => null, // Note: sanitization implemented in self::prepare_item_for_database().
						'validate_callback' => null, // Note: validation implemented in self::prepare_item_for_database().
					),
					'properties'  => array(
						'raw'      => array(
							'description' => esc_html__( 'Content for the object, as it exists in the database.', 'evie-xt' ),
							'type'        => 'string',
							'context'     => array( 'edit' ),
						),
						'rendered' => array(
							'description' => esc_html__( 'HTML content for the object, transformed for display.', 'evie-xt' ),
							'type'        => 'string',
							'context'     => array( 'view', 'edit', 'embed' ),
							'readonly'    => true,
						),
					),
				),
				'date'              => array(
					'description' => esc_html__( "The date the object was published, in the site's timezone.", 'evie-xt' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit', 'embed' ),
				),
				'date_gmt'          => array(
					'description' => esc_html__( 'The date the object was published, as GMT.', 'evie-xt' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
				),
				'link'              => array(
					'description' => esc_html__( 'URL to the object.', 'evie-xt' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
				'parent'            => array(
					'description' => esc_html__( 'The ID for the parent of the object.', 'evie-xt' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit', 'embed' ),
					'default'     => 0,
				),
				'post'              => array(
					'description' => esc_html__( 'The ID of the associated post object.', 'evie-xt' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'default'     => 0,
				),
				'status'            => array(
					'description' => esc_html__( 'State of the object.', 'evie-xt' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_key',
					),
				),
				'type'              => array(
					'description' => esc_html__( 'Type of Comment for the object.', 'evie-xt' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
			),
		);

		if ( get_option( 'show_avatars' ) ) {
			$avatar_properties = array();

			$avatar_sizes = rest_get_avatar_sizes();
			foreach ( $avatar_sizes as $size ) {
				$avatar_properties[ $size ] = array(
					/* translators: %d: avatar image size in pixels */
					'description' => sprintf( esc_html__( 'Avatar URL with image size of %d pixels.', 'evie-xt' ), $size ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'embed', 'view', 'edit' ),
				);
			}

			$schema['properties']['author_avatar_urls'] = array(
				'description' => esc_html__( 'Avatar URLs for the object author.', 'evie-xt' ),
				'type'        => 'object',
				'context'     => array( 'view', 'edit', 'embed' ),
				'readonly'    => true,
				'properties'  => $avatar_properties,
			);
		}

		$schema['properties']['meta'] = $this->meta->get_field_schema();

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Retrieves the query params for the posts collection.
	 *
	 * @access public
	 *
	 * @return array Collection parameters.
	 */
	public function get_collection_params() {

		$query_params = array(
			'post' => array(
				'description' => esc_html__( 'Limit result set to comments assigned to specific post IDs.', 'evie-xt' ),
				'type'        => 'string',
			),
			'path' => array(
				'description' => esc_html__( 'The path of the post.', 'evie-xt' ),
				'type'        => 'string',
			),
			'page' => array(
				'description' => esc_html__( 'Current page of the collection.', 'evie-xt' ),
				'type'        => 'string',
				'default'     => '',
			),
		);

		/**
		 * Filter collection parameters for the comments controller.
		 *
		 * This filter registers the collection parameter, but does not map the
		 * collection parameter to an internal WP_Comment_Query parameter. Use the
		 * `rest_comment_query` filter to set WP_Comment_Query parameters.
		 *
		 * @param array $query_params JSON Schema-formatted collection parameters.
		 */
		return apply_filters( 'evie_rest_comment_collection_params', $query_params );
	}

}
