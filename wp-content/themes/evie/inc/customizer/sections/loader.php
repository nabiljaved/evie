<?php
/**
 * Loader Settings
 *
 * @package    Evie
 * @subpackage Customizer
 * @version    1.0.0
 */

/**
 * Renders the partial page loader.
 */
function evie_customizer_get_loader() {
	evie_loader();
}

/**
 * Returns whether the loader logo is enabled.
 *
 * @return bool Whether the loader logo is enabled.
 */
function evie_customizer_is_loader_logo_enabled() {
	return 'logo' === get_theme_mod( 'loader', '' );
}

/**
 * Returns whether the loader background is enabled.
 *
 * @return bool Whether the loader background is enabled.
 */
function evie_customizer_is_loader_background_enabled() {
	return '' !== get_theme_mod( 'loader', '' );
}

/**
 * Registers the theme settings and controls in the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function evie_customizer_register_loader_settings( $wp_customize ) {

	/**
	 * Loader Section.
	 */
	$wp_customize->add_section(
		'loader_section',
		array(
			'title'    => esc_html__( 'Loader', 'evie' ),
			'priority' => 100, // Before Widgets.
		)
	);

	// Loader -> Loader.
	$wp_customize->add_setting(
		'loader',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'loader',
		array(
			'section'     => 'loader_section',
			'label'       => esc_html__( 'Loader', 'evie' ),
			'description' => esc_html__( 'A loading animation (spinner) to display while loading the content.', 'evie' ),
			'type'        => 'select',
			'choices'     => array(
				''           => esc_html__( 'None', 'evie' ),
				'water-drop' => esc_html__( 'Water Drop', 'evie' ),
				'windmill'   => esc_html__( 'Windmill', 'evie' ),
				'moon'       => esc_html__( 'Moon', 'evie' ),
				'ripple'     => esc_html__( 'Ripple', 'evie' ),
				'logo'       => esc_html__( 'Custom Logo', 'evie' ),
			),
			'priority'    => 5,
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'loader',
		array(
			'selector'            => '#site-loader',
			'render_callback'     => 'evie_customizer_get_loader',
			'container_inclusive' => true,
		)
	);

	// Loader -> Logo.
	$wp_customize->add_setting(
		'loader_logo',
		array(
			'theme_supports'    => array( 'custom-logo' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Cropped_Image_Control(
			$wp_customize,
			'loader_logo',
			array(
				'label'           => esc_html__( 'Logo', 'evie' ),
				'description'     => esc_html__( 'A logo to display while loading the content.', 'evie' ),
				'section'         => 'loader_section',
				'priority'        => 8,
				'height'          => 300,
				'width'           => 300,
				'flex_height'     => true,
				'flex_width'      => true,
				'button_labels'   => array(
					'select'       => esc_html__( 'Select logo', 'evie' ),
					'change'       => esc_html__( 'Change logo', 'evie' ),
					'remove'       => esc_html__( 'Remove', 'evie' ),
					'default'      => esc_html__( 'Default', 'evie' ),
					'placeholder'  => esc_html__( 'No logo selected', 'evie' ),
					'frame_title'  => esc_html__( 'Select logo', 'evie' ),
					'frame_button' => esc_html__( 'Choose logo', 'evie' ),
				),
				'active_callback' => 'evie_customizer_is_loader_logo_enabled',
			)
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'loader_logo',
		array(
			'selector'            => '#site-loader',
			'render_callback'     => 'evie_customizer_get_loader',
			'container_inclusive' => true,
		)
	);

	// Loader -> Background Overlay.
	$wp_customize->add_setting(
		'loader_overlay',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'loader_overlay',
			array(
				'section'         => 'loader_section',
				'label'           => esc_html__( 'Background', 'evie' ),
				'description'     => esc_html__( 'The loader background color. Default value: Empty.', 'evie' ),
				'active_callback' => 'evie_customizer_is_loader_background_enabled',
			)
		)
	);

	// Loader -> Background Overlay Opacity.
	$wp_customize->add_setting(
		'loader_overlay_opacity',
		array(
			'default'           => 75,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_number',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Range_Control(
			$wp_customize,
			'loader_overlay_opacity',
			array(
				'section'         => 'loader_section',
				'label'           => esc_html__( 'Background Opacity', 'evie' ),
				'description'     => esc_html__( 'The background overlay opacity.', 'evie' ),
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
				'active_callback' => 'evie_customizer_is_loader_background_enabled',
			)
		)
	);
}

add_action( 'customize_register', 'evie_customizer_register_loader_settings', 20 );
