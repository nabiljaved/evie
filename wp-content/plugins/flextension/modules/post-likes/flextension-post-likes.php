<?php
/**
 * Post Likes
 *
 * @package    Flextension
 * @subpackage Modules/Post_Likes
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the post meta key for the number of likes.
 *
 * @return string The post meta key for the number of likes.
 */
function flextension_post_likes_meta_key() {
	/**
	 * Filters the post meta key for the number of likes.
	 *
	 * @param string The post meta key for the number of likes.
	 */
	$meta_key = apply_filters( 'flextension_post_likes_meta_key', '_flext_likes' );

	return $meta_key;
}

/**
 * Returns the name of meta key for the list of likes.
 *
 * @return string The name of meta key for the list of likes.
 */
function flextension_post_likes_list_meta_key() {
	/**
	 * Filters the name of meta key for the list of likes.
	 *
	 * @param string The name of meta key for the list of likes.
	 */
	$meta_key = apply_filters( 'flextension_post_likes_list_meta_key', '_flext_likes_list' );

	return $meta_key;
}

/**
 * Retrieves the number of likes for the post.
 *
 * @param string $post_id The post ID.
 * @return int The number of likes for the post.
 */
function flextension_post_likes_get_likes( $post_id = '' ) {

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	$likes = get_post_meta( $post_id, flextension_post_likes_meta_key(), true );
	$likes = ( empty( $likes ) ) ? 0 : $likes;
	return intval( $likes );
}

/**
 * Gets whether user has already liked the post.
 *
 * @param string $post_id The post ID.
 * @param int    $user_id The current user ID.
 * @return bool Whether user has already liked this post.
 */
function flextension_post_likes_has_liked( $post_id = '', $user_id = 0 ) {
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$liked_posts = flextension_post_likes_get_likes_list( $user_id );
	return in_array( absint( $post_id ), $liked_posts, true );
}

/**
 * Returns the list of post IDs that user has already liked.
 *
 * @param int $user_id The current user ID.
 * @return array An array of post IDs.
 */
function flextension_post_likes_get_likes_list( $user_id = 0 ) {

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$liked_posts = array();

	$list = get_user_meta( $user_id, flextension_post_likes_list_meta_key(), true );
	if ( ! empty( $list ) ) {
		$liked_posts = explode( ',', $list );
	}

	return array_map( 'absint', $liked_posts );
}

/**
 * Adds the post ID to the list of liked items.
 *
 * @param string $post_id The post ID.
 * @param int    $user_id The current user ID.
 */
function flextension_post_likes_add_to_likes_list( $post_id = '', $user_id = 0 ) {

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$liked_posts = flextension_post_likes_get_likes_list( $user_id );

	// Add new post to the list.
	$liked_posts[] = $post_id;

	update_user_meta( $user_id, flextension_post_likes_list_meta_key(), implode( ',', $liked_posts ) );
}

/**
 * Removes the post ID from the list of liked items.
 *
 * @param string $post_id The post ID.
 * @param int    $user_id The current user ID.
 */
function flextension_post_likes_remove_from_likes_list( $post_id = '', $user_id = 0 ) {

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$liked_posts = flextension_post_likes_get_likes_list( $user_id );

	$pos = array_search( absint( $post_id ), $liked_posts, true );
	if ( false !== $pos ) {
		array_splice( $liked_posts, $pos, 1 );
	}

	update_user_meta( $user_id, flextension_post_likes_list_meta_key(), implode( ',', $liked_posts ) );
}

/**
 * Adds the number of likes for the post.
 *
 * @param string $post_id The post ID.
 * @param int    $user_id The current user ID.
 * @return int The new number of likes for the post.
 */
function flextension_post_likes_add_like( $post_id = '', $user_id = 0 ) {

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$likes = flextension_post_likes_get_likes( $post_id );

	update_post_meta( $post_id, flextension_post_likes_meta_key(), ++$likes );

	// Add to the liked list to prevent duplication.
	flextension_post_likes_add_to_likes_list( $post_id, $user_id );

	return $likes;
}

/**
 * Removes the number of likes for the post.
 *
 * @param string $post_id The post ID.
 * @param int    $user_id The current user ID.
 * @return int The new number of likes for the post.
 */
function flextension_post_likes_remove_like( $post_id = '', $user_id = 0 ) {

	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$likes = flextension_post_likes_get_likes( $post_id );

	// Update new likes.
	$new_likes = $likes - 1;
	if ( $new_likes < 0 ) {
		$new_likes = 0;
	}
	update_post_meta( $post_id, flextension_post_likes_meta_key(), $new_likes );

	// Remove from the liked list.
	flextension_post_likes_remove_from_likes_list( $post_id, $user_id );

	return $new_likes;
}

/**
 * Returns the button title.
 *
 * @param string $post_id The post ID.
 * @param int    $user_id The current user ID.
 * @param bool   $liked Whether user has already liked this post.
 * @return string The title for the like button.
 */
function flextension_post_likes_button_title( $post_id = '', $user_id = 0, $liked = null ) {
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}
	if ( is_null( $liked ) ) {
		$liked = flextension_post_likes_has_liked( $post_id, $user_id );
	}
	return $liked ? esc_html__( 'Unlike this', 'flextension' ) : esc_html__( 'Like this', 'flextension' );
}

/**
 * Prints HTML content for the Like button.
 *
 * @param int|WP_Post $post      Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string      $css_class Optional. CSS class to use for the button. Default empty.
 */
function flextension_post_likes_button( $post = 0, $css_class = '' ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$post_id = $post->ID;

	$liked = flextension_post_likes_has_liked( $post_id );

	$likes = flextension_post_likes_get_likes( $post_id );

	$classes = array( 'flext-post-likes' );

	if ( ! empty( $css_class ) ) {
		$classes[] = $css_class;
	}

	if ( true !== $liked ) {
		$classes[] = 'flext-like-button';

		if ( 0 === $likes ) {
			$classes[] = 'is-empty';
		}
	} else {
		$classes[] = 'flext-unlike-button';
	}

	echo sprintf(
		'<a class="%1$s" href="#" title="%2$s" data-post-id="%3$s"><i class="flext-ico-like"></i><span>%4$s</span></a><!-- .post-likes -->',
		esc_attr( implode( ' ', $classes ) ),
		esc_attr( flextension_post_likes_button_title( $post_id ) ),
		esc_attr( $post_id ),
		esc_html( flextension_number_format( $likes ) )
	);
}

/**
 * Sets posts query for the posts page.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function flextension_post_likes_set_posts_query( $query ) {

	if ( 'likes' === $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'meta_value_num date' );
		$query->set( 'meta_key', flextension_post_likes_meta_key() );
	}

}

add_action( 'pre_get_posts', 'flextension_post_likes_set_posts_query', 100 );

/**
 * Processes the current request.
 */
function flextension_post_likes_process_request() {

	check_ajax_referer( 'flextension_ajax', 'ajaxNonce' );

	$user_id = get_current_user_id();

	if ( 0 === $user_id ) {

		wp_send_json(
			array(
				'status'  => 401,
				'message' => sprintf(
					wp_kses(
					/* translators: %s: The login URL */
						__( 'You must <a href="%1$s">Login</a> or <a href="%2$s">Register</a> to like a post.', 'flextension' ),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					),
					wp_login_url( esc_url( get_permalink() ) ),
					wp_registration_url()
				),
			)
		);

	} else {

		$post_id = isset( $_POST['post'] ) ? absint( wp_unslash( $_POST['post'] ) ) : 0;

		if ( ! empty( $post_id ) ) {

			if ( flextension_post_likes_has_liked( $post_id, $user_id ) ) {
				$results = array(
					'status'  => 200,
					'message' => flextension_number_format( flextension_post_likes_remove_like( $post_id, $user_id ) ),
					'button'  => array(
						'status' => 0,
						'title'  => flextension_post_likes_button_title( $post_id, $user_id, false ),
					),
				);
				wp_send_json( $results );
			} else {
				$results = array(
					'status'  => 200,
					'message' => flextension_number_format( flextension_post_likes_add_like( $post_id, $user_id ) ),
					'button'  => array(
						'status' => 1,
						'title'  => flextension_post_likes_button_title( $post_id, $user_id, true ),
					),
				);
				wp_send_json( $results );
			}
		}
	}
}

add_action( 'wp_ajax_flextension_post_likes', 'flextension_post_likes_process_request' );

add_action( 'wp_ajax_nopriv_flextension_post_likes', 'flextension_post_likes_process_request' );

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_post_likes_enqueue_scripts() {

	wp_enqueue_style( 'flextension-post-likes', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );

	wp_enqueue_script( 'flextension-post-likes', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension-lightbox' ), flextension_get_setting( 'version' ), true );

}

add_action( 'wp_enqueue_scripts', 'flextension_post_likes_enqueue_scripts' );
