<?php
/**
 * Header Settings
 *
 * @package    Evie
 * @subpackage Customizer
 * @version    1.0.0
 */

/**
 * Renders the partial page header.
 */
function evie_customizer_get_header() {
	evie_page_header();
}

/**
 * Returns whether the header background color is enabled.
 *
 * @return bool Whether the header background color is enabled.
 */
function evie_customizer_is_header_color_enabled() {
	return '' === get_theme_mod( 'header_bg', '' );
}

/**
 * Returns whether the header background image is enabled.
 *
 * @return bool Whether the header background image is enabled.
 */
function evie_customizer_is_header_image_enabled() {
	return 'image' === get_theme_mod( 'header_bg', '' );
}

/**
 * Returns whether the header background image is enabled.
 *
 * @return bool Whether the header background image is enabled.
 */
function evie_customizer_is_header_overlay_enabled() {
	return 'image' === get_theme_mod( 'header_bg', '' ) && 'color' === get_theme_mod( 'header_bg_overlay', '' );
}

/**
 * Returns whether the header background color is enabled.
 *
 * @return bool Whether the header background color is enabled.
 */
function evie_customizer_is_header_text_mode_enabled() {
	return '' === get_theme_mod( 'header_bg', '' ) || ( 'image' === get_theme_mod( 'header_bg', '' ) && 'color' === get_theme_mod( 'header_bg_overlay', '' ) );
}

/**
 * Registers the theme settings and controls in the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function evie_customizer_register_header_settings( $wp_customize ) {

	/**
	 * Header Section
	 *
	 * - Change the title of Header Image section from "Header Image" to "Header".
	 */

	$wp_customize->get_section( 'header_image' )->title           = esc_html__( 'Header', 'evie' );
	$wp_customize->get_control( 'header_image' )->active_callback = 'evie_customizer_is_header_image_enabled';

	// Header -> Background.
	$wp_customize->add_setting(
		'header_bg',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'header_bg',
		array(
			'section'     => 'header_image',
			'label'       => esc_html__( 'Background', 'evie' ),
			'description' => esc_html__( 'The header background type.', 'evie' ),
			'type'        => 'select',
			'choices'     => array(
				''      => esc_html__( 'Color', 'evie' ),
				'image' => esc_html__( 'Image', 'evie' ),
			),
			'priority'    => 5,
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'header_bg',
		array(
			'selector'            => '#site-content .page-header.has-default-background',
			'render_callback'     => 'evie_customizer_get_header',
			'container_inclusive' => true,
		)
	);

	// Header -> Background Color.
	$wp_customize->add_setting(
		'header_bg_color',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'header_bg_color',
			array(
				'section'         => 'header_image',
				'label'           => esc_html__( 'Background Color', 'evie' ),
				'description'     => esc_html__( 'The default background color for the main header.', 'evie' ),
				'active_callback' => 'evie_customizer_is_header_color_enabled',
				'priority'        => 5,
			)
		)
	);

	// Header -> Image Position.
	$wp_customize->add_setting(
		'header_bg_position',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'header_bg_position',
		array(
			'section'         => 'header_image',
			'label'           => esc_html__( 'Image Position', 'evie' ),
			'description'     => esc_html__( 'The header background image position.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				'left top'      => esc_html__( 'Left Top', 'evie' ),
				'left center'   => esc_html__( 'Left Center', 'evie' ),
				'left bottom'   => esc_html__( 'Left Bottom', 'evie' ),
				'right top'     => esc_html__( 'Right Top', 'evie' ),
				'right center'  => esc_html__( 'Right Center', 'evie' ),
				'right bottom'  => esc_html__( 'Right Bottom', 'evie' ),
				'center top'    => esc_html__( 'Center Top', 'evie' ),
				''              => esc_html__( 'Center Center', 'evie' ),
				'center bottom' => esc_html__( 'Center Bottom', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_header_image_enabled',
		)
	);

	// Header -> Image Size.
	$wp_customize->add_setting(
		'header_bg_size',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'header_bg_size',
		array(
			'section'         => 'header_image',
			'label'           => esc_html__( 'Image Size', 'evie' ),
			'description'     => esc_html__( 'The header background image size.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				'auto'    => esc_html__( 'Original', 'evie' ),
				'contain' => esc_html__( 'Fit to Screen', 'evie' ),
				''        => esc_html__( 'Fill Screen', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_header_image_enabled',
		)
	);

	// Header -> Fixed background.
	$wp_customize->add_setting(
		'header_bg_attachment',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'header_bg_attachment',
		array(
			'section'         => 'header_image',
			'label'           => esc_html__( 'Fixed background.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_header_image_enabled',
		)
	);

	// Header -> Repeated background.
	$wp_customize->add_setting(
		'header_bg_repeat',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'header_bg_repeat',
		array(
			'section'         => 'header_image',
			'label'           => esc_html__( 'Repeated background.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_header_image_enabled',
		)
	);

	// Header -> Background Overlay.
	$wp_customize->add_setting(
		'header_bg_overlay',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'header_bg_overlay',
		array(
			'section'         => 'header_image',
			'label'           => esc_html__( 'Background Overlay', 'evie' ),
			'description'     => esc_html__( 'Adds an overlay to the background image.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				''      => esc_html__( 'Gradient', 'evie' ),
				'color' => esc_html__( 'Color', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_header_image_enabled',
		)
	);

	// Header -> Overlay Color.
	$wp_customize->add_setting(
		'header_bg_overlay_color',
		array(
			'default'           => '#000',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'header_bg_overlay_color',
			array(
				'section'         => 'header_image',
				'label'           => esc_html__( 'Overlay Color', 'evie' ),
				'description'     => esc_html__( 'The color overlay for the header background image.', 'evie' ),
				'active_callback' => 'evie_customizer_is_header_overlay_enabled',
			)
		)
	);

	// Header -> Background Overlay Opacity.
	$wp_customize->add_setting(
		'header_bg_overlay_opacity',
		array(
			'default'           => 75,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_number',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Range_Control(
			$wp_customize,
			'header_bg_overlay_opacity',
			array(
				'section'         => 'header_image',
				'label'           => esc_html__( 'Overlay Opacity', 'evie' ),
				'description'     => esc_html__( 'The header background overlay opacity.', 'evie' ),
				'input_attrs'     => array(
					'min' => 0,
					'max' => 100,
				),
				'marks'           => array(
					0   => 0,
					25  => 25,
					50  => 50,
					75  => 75,
					100 => 100,
				),
				'active_callback' => 'evie_customizer_is_header_overlay_enabled',
			)
		)
	);

	// Header -> Text Mode.
	$wp_customize->add_setting(
		'header_text_mode',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'header_text_mode',
		array(
			'section'         => 'header_image',
			'label'           => esc_html__( 'Text Mode', 'evie' ),
			'description'     => esc_html__( 'Text color for the header titles. Choose "Auto" to automatically adjust it depending on your site color scheme.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				''      => esc_html__( 'Auto', 'evie' ),
				'dark'  => esc_html__( 'Dark', 'evie' ),
				'light' => esc_html__( 'Light', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_header_text_mode_enabled',
		)
	);
}

add_action( 'customize_register', 'evie_customizer_register_header_settings', 20 );
