<?php
/**
 * Background Settings
 *
 * @package    Evie
 * @subpackage Customizer
 * @version    1.0.0
 */

/**
 * Registers the theme settings and controls in the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function evie_customizer_register_background_settings( $wp_customize ) {

	/**
	 * Background Section
	 *
	 * - Change the title of Background Image section from "Background Image" to "Background".
	 * - Move the Background Color control from "Colors" section to the "Background" section.
	 * - Set the Background Color control description.
	 */
	$wp_customize->get_section( 'background_image' )->title       = esc_html__( 'Background', 'evie' );
	$wp_customize->get_control( 'background_color' )->section     = 'background_image';
	$wp_customize->get_control( 'background_color' )->description = esc_html__( 'The background color for your site.', 'evie' );
}

add_action( 'customize_register', 'evie_customizer_register_background_settings', 20 );
