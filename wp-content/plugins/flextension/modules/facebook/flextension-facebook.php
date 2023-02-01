<?php
/**
 * Facebook
 *
 * @package    Flextension
 * @subpackage Modules/Facebook
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers scripts and stylesheets.
 */
function flextension_facebook_register_scripts() {

	wp_register_style( 'flextension-facebook', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );

	wp_register_script( 'flextension-facebook', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );

}

add_action( 'init', 'flextension_facebook_register_scripts' );

/**
 * Renders Facebook Page widget.
 *
 * @param array $attributes The attributes list for the widget.
 * @return string The HTML content for the widget.
 */
function flextension_facebook_page_widget( $attributes = array() ) {

	wp_enqueue_style( 'flextension-facebook' );

	$defaults = array(
		'title'         => esc_html__( 'Find us on Facebook', 'flextension' ),
		'page_url'      => '',
		'width'         => '240',
		'height'        => '500',
		'show_facepile' => true,
		'small_header'  => false,
		'timeline'      => true,
		'events'        => false,
		'messages'      => false,
	);

	$attributes = wp_parse_args( $attributes, $defaults );

	/**
	 * Filters Facebook Page widget attributes.
	 *
	 * @param array $attributes The widget attributes.
	 */
	$attributes = apply_filters( 'flextension_facebook_page_widget_attributes', $attributes );

	if ( empty( $attributes['page_url'] ) ) {
		return '';
	}

	$attrs = array();

	$classes = array();

	$styles = array();

	$classes[] = 'flext-facebook';

	if ( ! empty( $alignment ) ) {
		$classes[] = 'align' . $alignment;
	}

	$attrs['class'] = implode( ' ', $classes );

	if ( ! empty( $attributes['width'] ) ) {
		$width               = absint( $attributes['width'] );
		$attrs['data-width'] = $width;
		$styles[]            = "width:{$width}px";
	}

	$height               = absint( $attributes['height'] );
	$attrs['data-height'] = $height;
	$styles[]             = "height:{$height}px";

	$attrs['data-show-facepile'] = $attributes['show_facepile'];
	$attrs['data-small-header']  = $attributes['small_header'];
	$attrs['data-page-url']      = esc_url( $attributes['page_url'] );
	$attrs['data-timeline']      = (bool) $attributes['timeline'];
	$attrs['data-events']        = (bool) $attributes['events'];
	$attrs['data-messages']      = (bool) $attributes['messages'];

	$attrs['style'] = implode( ';', $styles );

	return '<div' . flextension_get_attributes( $attrs ) . '></div>';
}
