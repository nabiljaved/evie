<?php
/**
 * Quick View
 *
 * @package    Flextension
 * @subpackage Modules/Quick_View
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns Quick View button HTML.
 *
 * @param int|WP_Post $post  Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string      $css_class Additional CSS class for the  button.
 * @param string      $mode  Request mode between 'rest' and 'legacy'. Default is 'rest'.
 * @return string Quick View button HTML.
 */
function flextension_quick_view_button( $post = 0, $css_class = '', $mode = '' ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$classes = array( 'flext-quick-view-button' );

	$classes[] = 'quick-view-type-' . $post->post_type;

	if ( ! empty( $css_class ) ) {
		$classes[] = $css_class;
	}

	return sprintf(
		'<button class="%1$s" aria-label="' . esc_attr( esc_html__( 'Quick View', 'flextension' ) ) . '" data-post-id="%2$s" data-post-type="%3$s"%4$s><i class="flext-ico-view"></i></button>',
		esc_attr( implode( ' ', $classes ) ),
		esc_attr( $post->ID ),
		esc_attr( $post->post_type ),
		! empty( $mode ) ? ' data-mode="' . esc_attr( $mode ) . '"' : ''
	);
}

/**
 * Returns the Quick View content for the post.
 */
function flextension_quick_view_get_content() {

	check_ajax_referer( 'flextension_ajax', 'ajaxNonce' );

	if ( ! isset( $_GET['id'] ) ) {
		die();
	}

	$post_id = absint( wp_unslash( $_GET['id'] ) );

	$post_type = '';

	if ( isset( $_GET['postType'] ) ) {
		$post_type = sanitize_key( wp_unslash( $_GET['postType'] ) );
	}

	if ( empty( $post_type ) ) {
		$post = get_post( $post_id );
		if ( $post ) {
			$post_type = $post->post_type;
		}
	}

	// Set the main wp query for the product.
	wp( 'p=' . $post_id . '&post_type=' . $post_type );

	if ( 'post' === $post_type ) {
		$post_type = '';
	}

	$data = array();

	if ( have_posts() ) {

		the_post();

		ob_start();
		flextension_get_template( plugin_dir_path( __FILE__ ) . 'templates/quick-view', $post_type );
		$content = ob_get_clean();

		$data = array(
			'id'       => get_the_ID(),
			'rendered' => $content,
		);
	}

	wp_reset_postdata();

	wp_send_json( $data );
}

// Quick view AJAX.
add_action( 'wp_ajax_flextension_quick_view', 'flextension_quick_view_get_content' );

add_action( 'wp_ajax_nopriv_flextension_quick_view', 'flextension_quick_view_get_content' );

/**
 * Registers the scripts and stylesheets.
 */
function flextension_quick_view_register_scripts() {

	wp_register_style( 'flextension-quick-view', plugins_url( 'css/style.css', __FILE__ ), array(), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-quick-view', 'rtl', 'replace' );

	wp_register_script( 'flextension-quick-view', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension-lightbox' ), flextension_get_setting( 'version' ), true );

}

add_action( 'init', 'flextension_quick_view_register_scripts' );

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_quick_view_enqueue_scripts() {

	wp_enqueue_style( 'flextension-quick-view' );

	wp_enqueue_script( 'flextension-quick-view' );

}

add_action( 'wp_enqueue_scripts', 'flextension_quick_view_enqueue_scripts' );

/**
 * Enqueues block editor scripts and stylesheets.
 */
function flextension_quick_view_enqueue_block_editor_assets() {

	wp_enqueue_style( 'flextension-quick-view' );

}

add_action( 'enqueue_block_editor_assets', 'flextension_quick_view_enqueue_block_editor_assets' );
