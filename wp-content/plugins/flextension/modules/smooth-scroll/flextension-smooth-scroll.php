<?php
/**
 * Smooth Scroll
 *
 * @package    Flextension
 * @subpackage Modules/Smooth_Scroll
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the settings values of the Smooth Scroll module.
 *
 * @return array An array object of the settings.
 */
function flextension_smooth_scroll_settings() {
	return wp_parse_args(
		get_option( 'flext_smooth_scroll', array() ),
		array(
			'duration' => '',
		)
	);
}

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_smooth_scroll_enqueue_scripts() {

	if ( is_customize_preview() ) {
		return;
	}

	wp_enqueue_script( 'smooth-scroll', plugins_url( 'js/smooth-scroll.js', __FILE__ ), array(), '1.4.10', true, 50 );

	$settings = flextension_smooth_scroll_settings();

	$options = array();

	if ( ! empty( $settings['duration'] ) ) {
		$options['animationTime'] = absint( $settings['duration'] );
	}

	/**
	 * Filters the Smooth Scroll module settings.
	 *
	 * @param array $options An array options for the Smooth Scroll.
	 */
	$options = apply_filters( 'flextension_smooth_scroll_options', $options );

	if ( ! empty( $options ) ) {
		wp_localize_script( 'smooth-scroll', 'SmoothScrollOptions', $options );
	}

}

add_action( 'wp_enqueue_scripts', 'flextension_smooth_scroll_enqueue_scripts' );
