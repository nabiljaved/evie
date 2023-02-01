<?php
/**
 * Posts REST API
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
class Evie_REST_Posts_Controller extends WP_REST_Controller {

	/**
	 * Constructs the controller.
	 */
	public function __construct() {
		$this->namespace = 'evie/v1';
		$this->rest_base = 'posts';
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
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => '__return_true',
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
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
		$query_args = evie_ajax_pagination_prepare_items_query( $request );

		$page = max( 1, (int) $query_args['paged'] );

		evie_ajax_pagination_get_posts( $query_args );

		$total_posts = $GLOBALS['wp_query']->found_posts;

		if ( $total_posts < 1 ) {
			// Out-of-bounds, run the query again without LIMIT for total count.
			unset( $query_args['paged'] );

			$count_query = new WP_Query();
			$count_query->query( $query_args );
			$total_posts = $count_query->found_posts;
		}

		$max_pages = ceil( $total_posts / (int) $GLOBALS['wp_query']->query_vars['posts_per_page'] );

		if ( $page > $max_pages && $total_posts > 0 ) {
			return new WP_Error( 'rest_post_invalid_page_number', esc_html__( 'There are no more posts to show right now.', 'evie-xt' ), array( 'status' => 200 ) );
		}

		add_filter( 'get_pagenum_link', 'evie_ajax_pagination_get_pagenum_link', 10, 2 );

		$items = array();

		if ( have_posts() ) {

			$args = evie_posts_args();

			while ( have_posts() ) {

				the_post();

				$items[] = array(
					'rendered' => $this->get_post_content( '', get_post_type( get_the_ID() ), $args ),
				);

			}
		} else {

			$items[] = array(
				'rendered' => $this->get_post_content( 'none' ),
			);
		}

		if ( ! is_archive() && ! is_search() ) {

			$GLOBALS['wp_query']->is_home = true;

			if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) ) {
				$GLOBALS['wp_query']->is_posts_page = true;
			}
		}

		$response = rest_ensure_response( $items );

		$response->header( 'X-WP-Total', (int) $total_posts );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		if ( $page > 1 ) {
			$prev_page = $page - 1;

			if ( $prev_page > $max_pages ) {
				$prev_page = $max_pages;
			}

			$prev_link = get_pagenum_link( $prev_page );
			$response->link_header( 'prev', $prev_link );
		}

		if ( $max_pages > 1 && $max_pages > $page ) {
			$next_page = $page + 1;

			$next_link = get_pagenum_link( $next_page );
			$response->link_header( 'next', $next_link );
		}

		wp_reset_postdata();

		return $response;
	}

	/**
	 * Returns the post content.
	 *
	 * @access protected
	 *
	 * @param string $name The template name to display.
	 * @param string $type The type of the content template.
	 * @param array  $args Additional arguments passed to the template.
	 * @return string The post content.
	 */
	protected function get_post_content( $name = '', $type = '', $args = array() ) {
		return evie_get_content_template( $name, $type, $args );
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
			'title'      => 'rendered-posts',
			'type'       => 'object',
			'properties' => array(
				'rendered' => array(
					'description' => esc_html__( 'The rendered posts.', 'evie-xt' ),
					'type'        => 'string',
					'required'    => true,
					'context'     => array( 'view' ),
				),
			),
		);

		return $this->schema;
	}
}
