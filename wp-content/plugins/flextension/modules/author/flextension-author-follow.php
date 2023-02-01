<?php
/**
 * Follow
 *
 * @package    Flextension
 * @subpackage Modules/Author
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns whether the 'Follow' button is enabled.
 *
 * @return bool Whether the 'Follow' button is enabled.
 */
function flextension_author_follow_enabled() {
	$settings = flextension_author_settings();
	$enabled  = (bool) $settings['follow'];
	/**
	 * Filters whether the Follow feature is enabled.
	 *
	 * @param bool $enabled Whether the Follow feature is enabled.
	 */
	return apply_filters( 'flextension_author_follow_enabled', $enabled );
}

/**
 * Returns whether the Follow button can be displayed.
 *
 * @param int $author_id The author ID.
 * @return bool Whether the Follow button can be displayed.
 */
function flextension_author_can_follow( $author_id ) {
	$user_id = get_current_user_id();
	if ( 0 !== $user_id && absint( $author_id ) === $user_id ) {
		return false;
	}
	return flextension_author_follow_enabled() && flextension_author_can_be_followed( $author_id );
}

/**
 * Returns whether the author can be followed by other users.
 *
 * @param int $author_id The author ID.
 * @return bool Whether the author can be followed by other users.
 */
function flextension_author_can_be_followed( $author_id ) {
	return user_can( $author_id, 'edit_posts' );
}

/**
 * Returns whether the number of followers and following can be displayed.
 *
 * @return bool Whether the number of followers and following can be displayed.
 */
function flextension_author_show_followers() {
	$enabled = flextension_author_follow_enabled();
	if ( true === $enabled ) {
		$settings = flextension_author_settings();
		$enabled  = (bool) $settings['followers'];
	}
	return $enabled;
}

/**
 * Returns the list of followers.
 *
 * @param int $author_id The author ID to retreive the followers.
 * @return array The user IDs of the followers.
 */
function flextension_author_get_followers( $author_id = 0 ) {

	$followers = array();

	$list = get_user_meta( $author_id, '_flext_followers', true );
	if ( ! empty( $list ) ) {
		$followers = explode( ',', $list );
	}

	return array_map( 'absint', $followers );
}

/**
 * Adds the author ID to the followers list.
 *
 * @param int $author_id The author ID.
 * @param int $user_id   The user ID to add to the followers list.
 */
function flextension_author_add_follower( $author_id = 0, $user_id = 0 ) {

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$followers = flextension_author_get_followers( $author_id );

	// Add new user ID to the list.
	$followers[] = $user_id;

	update_user_meta( $author_id, '_flext_followers', implode( ',', $followers ) );
}

/**
 * Removes the user ID from the followers list of the author.
 *
 * @param int $author_id The author ID to retreive the followers list.
 * @param int $user_id   The user ID to remove from the followers list.
 * @return int The number of followers.
 */
function flextension_author_remove_follower( $author_id = 0, $user_id = 0 ) {

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$followers = flextension_author_get_followers( $author_id );

	$pos = array_search( absint( $user_id ), $followers, true );
	if ( false !== $pos ) {
		array_splice( $followers, $pos, 1 );
	}

	update_user_meta( $author_id, '_flext_followers', implode( ',', $followers ) );

	return count( $followers );
}

/**
 * Returns the list of author IDs that user has already followed.
 *
 * @param int $user_id The user ID to retreive the following list.
 * @return array An array of following author IDs.
 */
function flextension_author_get_following( $user_id = 0 ) {

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$following = array();

	$list = get_user_meta( $user_id, '_flext_following', true );
	if ( ! empty( $list ) ) {
		$following = explode( ',', $list );
	}

	return array_map( 'absint', $following );
}

/**
 * Adds an author ID to the following list of the user.
 *
 * @param int $user_id   The user ID.
 * @param int $author_id The author ID to follow.
 */
function flextension_author_add_following( $user_id = 0, $author_id = 0 ) {

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$following = flextension_author_get_following( $user_id );

	$following[] = $author_id;

	update_user_meta( $user_id, '_flext_following', implode( ',', $following ) );
}

/**
 * Removes the author ID from the following list of the user.
 *
 * @param int $user_id   The user ID to retreive the following list.
 * @param int $author_id The author ID to remove from the following list.
 * @return int The number of following.
 */
function flextension_author_remove_following( $user_id = 0, $author_id = 0 ) {

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$following = flextension_author_get_following( $user_id );

	$pos = array_search( absint( $author_id ), $following, true );
	if ( false !== $pos ) {
		array_splice( $following, $pos, 1 );
	}

	update_user_meta( $user_id, '_flext_following', implode( ',', $following ) );

	return count( $following );
}

/**
 * Returns whether user has already followed the author.
 *
 * @param int $author_id The author ID.
 * @param int $user_id   The user ID.
 * @return bool Whether user has already followed this author.
 */
function flextension_author_has_followed( $author_id = 0, $user_id = 0 ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$following = flextension_author_get_following( $user_id );
	return in_array( absint( $author_id ), $following, true );
}

/**
 * Follows the author by doing 2 steps:
 *
 * 1. Add the author ID to the following list of the user.
 * 2. Add the user ID to the followers list of the author.
 *
 * @param int $author_id The author ID.
 * @param int $user_id   The current user ID.
 */
function flextension_author_follow( $author_id = 0, $user_id = 0 ) {
	flextension_author_add_following( $user_id, $author_id );
	$count = flextension_author_add_follower( $author_id, $user_id );
	return $count;
}

/**
 * Unfollows the author by doing 2 steps:
 *
 * 1. Remove the user ID from the followers list of the author.
 * 2. Remove the author ID from the following list of the user.
 *
 * @param int $author_id The author ID.
 * @param int $user_id   The current user ID.
 */
function flextension_author_unfollow( $author_id = 0, $user_id = 0 ) {
	flextension_author_remove_follower( $author_id, $user_id );
	$count = flextension_author_remove_following( $user_id, $author_id );
	return $count;
}

/**
 * Returns the button title.
 *
 * @param int  $author_id The author ID.
 * @param int  $user_id   The current user ID.
 * @param bool $followed  Whether user has already followed this author.
 * @return string The title for the follow button.
 */
function flextension_author_follow_button_title( $author_id = 0, $user_id = 0, $followed = null ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}
	if ( is_null( $followed ) ) {
		$followed = flextension_author_has_followed( $author_id, $user_id );
	}
	return $followed ? esc_html__( 'Unfollow', 'flextension' ) : esc_html__( 'Follow', 'flextension' );
}

/**
 * Returns HTML markup for the Follow button.
 *
 * @param int    $author_id Optional. Author ID to follow. Default 0.
 * @param string $css_class Optional. CSS class to use for the button. Default empty.
 * @return string HTML markup for the Follow button.
 */
function flextension_author_follow_button( $author_id = 0, $css_class = '' ) {

	if ( ! $author_id ) {
		return;
	}

	if ( ! flextension_author_can_follow( $author_id ) ) {
		return;
	}

	$followed = flextension_author_has_followed( $author_id );

	$classes = array();

	if ( ! empty( $css_class ) ) {
		$classes[] = $css_class;
	}

	if ( true !== $followed ) {
		$classes[] = 'flext-follow-button';
	} else {
		$classes[] = 'flext-unfollow-button';
	}

	return sprintf(
		'<button class="flext-author-follow %1$s" title="%2$s" data-author-id="%3$s"></button><!-- .flext-author-follow -->',
		esc_attr( implode( ' ', $classes ) ),
		esc_attr( flextension_author_follow_button_title( $author_id ) ),
		esc_attr( $author_id )
	);
}

/**
 * Returns number of the followers, wraps it with the link.
 *
 * @param int    $author_id Optional. The author ID. Default 0.
 * @param string $css_class Optional. CSS class to use for the link. Default empty.
 * @return string HTML markup for the link with number of followers.
 */
function flextension_author_followers_link( $author_id = 0, $css_class = '' ) {
	if ( true !== flextension_author_show_followers() || true !== flextension_author_can_be_followed( $author_id ) ) {
		return '';
	}

	$followers = count( flextension_author_get_followers( $author_id ) );

	/**
	 * Filters the URL of the followers page.
	 *
	 * @param string $url       The URL of the followers page.
	 * @param int    $author_id The author ID. Default 0.
	 */
	$url = apply_filters( 'flextension_author_followers_link', '#followers', $author_id );

	$classes = array( 'flext-author-followers' );

	if ( ! empty( $css_class ) ) {
		$classes[] = $css_class;
	}

	if ( 0 === $followers ) {
		$classes[] = 'is-empty';
	}

	return sprintf(
		'<a href="%1$s" class="%2$s" data-author-id="%3$s"><strong>%4$s</strong> %5$s</a>',
		esc_attr( esc_url( $url ) ),
		esc_attr( implode( ' ', $classes ) ),
		esc_attr( $author_id ),
		flextension_number_format( $followers ),
		_n( 'Follower', 'Followers', $followers, 'flextension' )
	);
}

/**
 * Returns number of the following, wraps it with the link.
 *
 * @param int    $author_id Optional. The author ID. Default 0.
 * @param string $css_class Optional. CSS class to use for the link. Default empty.
 * @return string HTML markup for the link with number of following.
 */
function flextension_author_following_link( $author_id = 0, $css_class = '' ) {
	if ( true !== flextension_author_show_followers() ) {
		return '';
	}

	$following = count( flextension_author_get_following( $author_id ) );

	/**
	 * Filters the URL of the following page.
	 *
	 * @param string $url       The URL of the following page.
	 * @param int    $author_id The author ID. Default 0.
	 */
	$url = apply_filters( 'flextension_author_following_link', '#following', $author_id );

	$classes = array( 'flext-author-following' );

	if ( ! empty( $css_class ) ) {
		$classes[] = $css_class;
	}

	if ( 0 === $following ) {
		$classes[] = 'is-empty';
	}

	return sprintf(
		'<a href="%1$s" class="%2$s" data-author-id="%3$s"><strong>%4$s</strong> %5$s</a>',
		esc_attr( esc_url( $url ) ),
		esc_attr( implode( ' ', $classes ) ),
		esc_attr( $author_id ),
		flextension_number_format( $following ),
		esc_html__( 'Following', 'flextension' )
	);
}

/**
 * Returns HTML markup for the links of the follower and following numbers.
 *
 * @param int    $author_id The author ID. Default 0.
 * @param string $separator Separator between the follower and following numbers. Default value: ''.
 * @return string HTML markup for the links of the follower and following numbers.
 */
function flextension_author_follow_numbers( $author_id = 0, $separator = '' ) {
	$links = array();

	$followers_link = flextension_author_followers_link( $author_id );
	if ( ! empty( $followers_link ) ) {
		$links[] = $followers_link;
	}

	$following_link = flextension_author_following_link( $author_id );
	if ( ! empty( $following_link ) ) {
		$links[] = $following_link;
	}

	$numbers = '';
	if ( ! empty( $links ) ) {
		$numbers = '<span class="flext-author-follow-numbers">' . implode( $separator, $links ) . '</span>';
	}

	/**
	 * Filters the HTML markup for the links of the follower and following numbers.
	 *
	 * @param string $numbers   HTML markup for the links of the follower and following numbers.
	 * @param string $separator Separator between the follower and following numbers.
	 */
	return apply_filters( 'flextension_author_follow_numbers', $numbers, $separator );
}

/**
 * Returns HTML markup for the followers or following list.
 *
 * @since 1.0.7 Fix an AJAX pagination issue.
 *
 * @param string $type    A type of list. Accepts 'followers' and 'following'.
 * @param int    $user_id User ID.
 * @param int    $page    Page number.
 * @return string HTML markup for the followers or following list.
 */
function flextension_author_get_followers_list( $type = 'followers', $user_id = 0, $page = 0 ) {
	$output = '';

	$all_items = 'following' === $type ? flextension_author_get_following( $user_id ) : flextension_author_get_followers( $user_id );
	if ( ! empty( $all_items ) ) {

		if ( ! $page ) {
			$page = absint( max( 1, get_query_var( 'paged' ) ) );
		}

		/**
		 * Filters number of followers or following items per page.
		 *
		 * @param int $per_page Number of items per page.
		 */
		$per_page = apply_filters( 'flextension_author_followers_per_page', 20 );

		$total  = count( $all_items );
		$offset = ( $page - 1 ) * $per_page;
		if ( $offset < $total ) {
			$items = array_slice( $all_items, $offset, $per_page );
			if ( ! empty( $items ) ) {
				$update_required = false;

				foreach ( $items as $item ) {
					if ( false !== get_user_by( 'id', $item ) ) {
						$output .= flextension_author_follow_list_item( $item );
					} else {
						$pos = array_search( absint( $item ), $all_items, true );
						if ( false !== $pos ) {
							array_splice( $all_items, $pos, 1 );
							$update_required = true;
						}
					}
				}

				if ( true === $update_required ) {
					$total    = count( $all_items );
					$meta_key = 'following' === $type ? '_flext_following' : '_flext_followers';
					update_user_meta( $user_id, $meta_key, implode( ',', $all_items ) );
				}

				$total_pages = ceil( $total / $per_page );

				$output .= flextension_author_follow_list_pagination( $total_pages, $page );
			}
		}
	}

	return $output;
}

/**
 * Prints out HTML markup for the followers or following list.
 *
 * @param string $type    A type of list. Accepts 'followers' and 'following'.
 * @param int    $user_id User ID.
 * @param int    $page    Page number.
 */
function flextension_author_followers_list( $type = 'followers', $user_id = 0, $page = 0 ) {
	echo '<div class="flext-author-follow-list" data-user-id="' . esc_attr( $user_id ) . '">' . flextension_author_get_followers_list( $type, $user_id, $page ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Returns HTML markup for the item of the followers list.
 *
 * @param int $id User ID of the item.
 * @return string HTML markup for the item of the followers list.
 */
function flextension_author_follow_list_item( $id ) {
	$item = '';

	$author_posts_url = '';
	if ( true === user_can( $id, 'edit_posts' ) ) {
		$author_posts_url = get_author_posts_url( $id );
	}

	$item = '<div class="flext-author-follow-item">';

	$avatar = get_avatar( get_the_author_meta( 'email', $id ), 40 );
	if ( ! empty( $avatar ) ) {
		if ( ! empty( $author_posts_url ) ) {
			$item .= '<a class="flext-author-avatar" href="' . esc_url( $author_posts_url ) . '">' . $avatar . '</a>';
		} else {
			$item .= '<span class="flext-author-avatar">' . $avatar . '</span>';
		}
	}

	$item .= '<strong>';

	if ( ! empty( $author_posts_url ) ) {
		$item .= '<a href="' . esc_url( $author_posts_url ) . '">' . esc_html( get_the_author_meta( 'display_name', $id ) ) . '</a>';
	} else {
		$item .= esc_html( get_the_author_meta( 'display_name', $id ) );
	}

	$item .= '</strong>';

	$item .= flextension_author_follow_button( $id );

	$item .= '</div>';

	return $item;
}

/**
 * Returns the next list items pagination.
 *
 * @param int $total   Total pages.
 * @param int $current The current page.
 * @return string The HTML output for the pagination.
 */
function flextension_author_follow_list_pagination( $total = 1, $current = 1 ) {
	if ( ! $current ) {
		$current = max( 1, get_query_var( 'paged' ) );
	}

	$pagination = '';

	$next_page = absint( $current ) + 1;
	if ( $next_page <= $total ) {
		$pagination = '<nav class="navigation pagination flext-author-more-items"><a href="#more" class="next" data-page="' . esc_attr( $next_page ) . '">' . esc_html__( 'Load more', 'flextension' ) . '</a></nav>';
	}

	return $pagination;
}

/**
 * Processes the current request.
 */
function flextension_author_follow_process_request() {

	check_ajax_referer( 'flextension_ajax', 'ajaxNonce' );

	$user_id = get_current_user_id();

	if ( 0 === $user_id ) {

		wp_send_json(
			array(
				'status'  => 401,
				'message' => sprintf(
					wp_kses(
					/* translators: %s: The login URL */
						__( 'You must <a href="%1$s">Login</a> or <a href="%2$s">Register</a> to follow.', 'flextension' ),
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

		$author_id = isset( $_POST['author'] ) ? absint( wp_unslash( $_POST['author'] ) ) : 0;

		if ( ! empty( $author_id ) ) {

			if ( flextension_author_has_followed( $author_id, $user_id ) ) {
				$results = array(
					'status'  => 200,
					'message' => flextension_number_format( flextension_author_unfollow( $author_id, $user_id ) ),
					'button'  => array(
						'status' => 0,
						'title'  => flextension_author_follow_button_title( $author_id, $user_id, false ),
					),
				);
				wp_send_json( $results );
			} else {
				$results = array(
					'status'  => 200,
					'message' => flextension_number_format( flextension_author_follow( $author_id, $user_id ) ),
					'button'  => array(
						'status' => 1,
						'title'  => flextension_author_follow_button_title( $author_id, $user_id, true ),
					),
				);
				wp_send_json( $results );
			}
		}
	}
}

add_action( 'wp_ajax_flextension_author_follow', 'flextension_author_follow_process_request' );

add_action( 'wp_ajax_nopriv_flextension_author_follow', 'flextension_author_follow_process_request' );
