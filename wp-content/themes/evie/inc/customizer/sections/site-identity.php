<?php
/**
 * Site Identity Settings
 *
 * @package    Evie
 * @subpackage Customizer
 * @version    1.0.0
 */

/**
 * Returns the site title for the selective refresh partial.
 *
 * @return string The site title.
 */
function evie_customizer_get_site_name() {
	return get_bloginfo( 'name', 'display' );
}

/**
 * Registers the theme settings and controls in the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function evie_customizer_register_site_identity_settings( $wp_customize ) {

	/**
	 * Site Identity section
	 */
	$custom_logo_args = get_theme_support( 'custom-logo' );

	$wp_customize->selective_refresh->add_partial(
		'custom_logo',
		array(
			'selector'            => '#site-header .site-logo',
			'render_callback'     => 'evie_site_logo',
			'container_inclusive' => true,
		)
	);

	// Add Retina Logo setting.
	$wp_customize->add_setting(
		'custom_logo_retina',
		array(
			'theme_supports'    => array( 'custom-logo' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Cropped_Image_Control(
			$wp_customize,
			'custom_logo_retina',
			array(
				'label'         => esc_html__( 'Logo (Retina version)', 'evie' ),
				'description'   => esc_html__( 'A high-res version of the logo (2x of the logo size).', 'evie' ),
				'section'       => 'title_tagline',
				'priority'      => 8,
				'height'        => (int) $custom_logo_args[0]['height'] * 2,
				'width'         => (int) $custom_logo_args[0]['width'] * 2,
				'flex_height'   => $custom_logo_args[0]['flex-height'],
				'flex_width'    => $custom_logo_args[0]['flex-width'],
				'button_labels' => array(
					'select'       => esc_html__( 'Select logo', 'evie' ),
					'change'       => esc_html__( 'Change logo', 'evie' ),
					'remove'       => esc_html__( 'Remove', 'evie' ),
					'default'      => esc_html__( 'Default', 'evie' ),
					'placeholder'  => esc_html__( 'No logo selected', 'evie' ),
					'frame_title'  => esc_html__( 'Select logo', 'evie' ),
					'frame_button' => esc_html__( 'Choose logo', 'evie' ),
				),
			)
		)
	);

	// Light Logo Separator.
	$wp_customize->add_control(
		new Evie_Customize_Separator_Control(
			$wp_customize,
			'light_logo_separator',
			array(
				'section'  => 'title_tagline',
				'priority' => 8,
			)
		)
	);

	// Light Logo.
	$wp_customize->add_setting(
		'light_logo',
		array(
			'theme_supports'    => array( 'custom-logo' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Cropped_Image_Control(
			$wp_customize,
			'light_logo',
			array(
				'label'         => esc_html__( 'Light Logo', 'evie' ),
				'description'   => esc_html__( 'A light version of the logo to display on a dark background.', 'evie' ),
				'section'       => 'title_tagline',
				'priority'      => 8,
				'height'        => (int) $custom_logo_args[0]['height'],
				'width'         => (int) $custom_logo_args[0]['width'],
				'flex_height'   => $custom_logo_args[0]['flex-height'],
				'flex_width'    => $custom_logo_args[0]['flex-width'],
				'button_labels' => array(
					'select'       => esc_html__( 'Select logo', 'evie' ),
					'change'       => esc_html__( 'Change logo', 'evie' ),
					'remove'       => esc_html__( 'Remove', 'evie' ),
					'default'      => esc_html__( 'Default', 'evie' ),
					'placeholder'  => esc_html__( 'No logo selected', 'evie' ),
					'frame_title'  => esc_html__( 'Select logo', 'evie' ),
					'frame_button' => esc_html__( 'Choose logo', 'evie' ),
				),
			)
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'light_logo',
		array(
			'selector'            => '#site-menu .site-logo',
			'render_callback'     => 'evie_site_logo',
			'container_inclusive' => true,
		)
	);

	// Logo Logo (Retina version).
	$wp_customize->add_setting(
		'light_logo_retina',
		array(
			'theme_supports'    => array( 'custom-logo' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Cropped_Image_Control(
			$wp_customize,
			'light_logo_retina',
			array(
				'label'         => esc_html__( 'Light Logo (Retina version)', 'evie' ),
				'description'   => esc_html__( 'A high-res version of the Light Logo (2x of the logo size).', 'evie' ),
				'section'       => 'title_tagline',
				'priority'      => 8,
				'height'        => (int) $custom_logo_args[0]['height'] * 2,
				'width'         => (int) $custom_logo_args[0]['width'] * 2,
				'flex_height'   => $custom_logo_args[0]['flex-height'],
				'flex_width'    => $custom_logo_args[0]['flex-width'],
				'button_labels' => array(
					'select'       => esc_html__( 'Select logo', 'evie' ),
					'change'       => esc_html__( 'Change logo', 'evie' ),
					'remove'       => esc_html__( 'Remove', 'evie' ),
					'default'      => esc_html__( 'Default', 'evie' ),
					'placeholder'  => esc_html__( 'No logo selected', 'evie' ),
					'frame_title'  => esc_html__( 'Select logo', 'evie' ),
					'frame_button' => esc_html__( 'Choose logo', 'evie' ),
				),
			)
		)
	);

	// Site Title Separator.
	$wp_customize->add_control(
		new Evie_Customize_Separator_Control(
			$wp_customize,
			'site_title_separator',
			array(
				'section'  => 'title_tagline',
				'priority' => 8,
			)
		)
	);

	/**
	 * Adds postMessage support for site title and description for the Theme Customizer.
	 */
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage'; // @phpstan-ignore-line. Assume that this setting exists.
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage'; // @phpstan-ignore-line. Assume that this setting exists.

	$wp_customize->selective_refresh->add_partial(
		'blogname',
		array(
			'selector'        => '.site-title a',
			'render_callback' => 'evie_customizer_get_site_name',
		)
	);

}

add_action( 'customize_register', 'evie_customizer_register_site_identity_settings', 20 );
