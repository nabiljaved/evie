<?php
/**
 * Post Views
 *
 * @package    Flextension
 * @subpackage Modules/Post_Views
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the post meta key for the number of views.
 *
 * @return string The post meta key for the number of views.
 */
function flextension_post_views_meta_key() {
	/**
	 * Filters the post meta key for the number of views.
	 *
	 * @param string $mata_key The post meta key for the number of views.
	 */
	return apply_filters( 'flextension_post_views_meta_key', '_flext_views' );
}

/**
 * Retrieves the number of views for the post.
 *
 * @param string $post_id The post ID.
 * @return int The number of views for the post.
 */
function flextension_post_views_get_views( $post_id = '' ) {
	$views = get_post_meta( $post_id, flextension_post_views_meta_key(), true );
	$views = ( empty( $views ) ) ? 0 : $views;
	return intval( $views );
}

/**
 * Adds the number of views for the post.
 *
 * @param int $post_id The post ID.
 * @return int The number of views.
 */
function flextension_post_views_add_view( $post_id = 0 ) {

	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$views = flextension_post_views_get_views( $post_id );

	// Update new views.
	$new_views = $views + 1;
	update_post_meta( $post_id, flextension_post_views_meta_key(), $new_views );

	return $new_views;
}

/**
 * Prints HTML content for the View button.
 *
 * @param int|WP_Post $post      Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string      $css_class Optional. CSS class to use for the button. Default empty.
 */
function flextension_post_views_button( $post = 0, $css_class = '' ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$views = flextension_post_views_get_views( $post->ID );
	$title = sprintf(
		/* translators: %s: Number of views. */
		_n( '%s view', '%s views', $views, 'flextension' ),
		number_format_i18n( $views )
	);

	$classes = array( 'post-views' );

	if ( ! empty( $css_class ) ) {
		$classes[] = $css_class;
	}

	echo sprintf(
		'<a class="%1$s" href="%2$s" title="%3$s" data-post-id="%4$s"><i class="flext-ico-view"></i><span>%5$s</span></a><!-- .post-views -->',
		esc_attr( implode( ' ', $classes ) ),
		esc_url( get_permalink( $post->ID ) ),
		esc_attr( $title ),
		esc_attr( $post->ID ),
		esc_html( flextension_number_format( $views ) )
	);
}

/**
 * Sets posts query for the posts page.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function flextension_post_views_set_posts_query( $query ) {

	if ( 'views' === $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'meta_value_num date' );
		$query->set( 'meta_key', flextension_post_views_meta_key() );
	}

}

add_action( 'pre_get_posts', 'flextension_post_views_set_posts_query', 100 );

/**
 * Adds the number of views when displaying the post on the single page.
 *
 * @param string $content The post content.
 * @return string The post content.
 */
function flextension_post_views_add_content_view( $content = '' ) {

	if ( is_single() ) {
		flextension_post_views_add_view();
	}

	return $content;
}

add_filter( 'the_content', 'flextension_post_views_add_content_view' );

/**
 * Adds number of views to the post meta.
 *
 * The value will not be added if it already exists.
 *
 * @param int $post_id The post ID.
 */
function flextension_post_views_add_meta_field( $post_id = 0 ) {
	if ( ! wp_is_post_revision( $post_id ) ) {
		update_post_meta( $post_id, flextension_post_views_meta_key(), 0, true );
	}
}

add_action( 'publish_post', 'flextension_post_views_add_meta_field', 10, 2 );
