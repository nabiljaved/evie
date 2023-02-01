<?php
/**
 * Colors Settings
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
function evie_customizer_register_colors_settings( $wp_customize ) {

	/**
	 * Colors Section
	 */

	// Colors -> Color Scheme.
	$wp_customize->add_setting(
		'color_scheme',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'color_scheme',
		array(
			'section'     => 'colors',
			'label'       => esc_html__( 'Color Scheme', 'evie' ),
			'description' => esc_html__( 'Default color scheme for your site.', 'evie' ),
			'type'        => 'radio',
			'choices'     => array(
				'light' => esc_html__( 'Light', 'evie' ),
				'dark'  => esc_html__( 'Dark', 'evie' ),
				''      => esc_html__( 'Auto', 'evie' ),
			),
		)
	);

	// Colors -> Allow users to switch color schemes.
	$wp_customize->add_setting(
		'user_color_support',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'user_color_support',
		array(
			'section' => 'colors',
			'label'   => esc_html__( 'Allow users to switch color schemes.', 'evie' ),
			'type'    => 'checkbox',
		)
	);

	// Colors -> Learn more about Dark Mode.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'about_dark_mode_support',
			array(
				'section'     => 'colors',
				'description' => sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( esc_html__( 'https://evietheme.com/documentation/#dark-mode', 'evie' ) ),
					esc_html__( 'Learn more about Dark Mode.', 'evie' )
				),
			)
		)
	);

	// Colors -> Separator.
	$wp_customize->add_control(
		new Evie_Customize_Separator_Control(
			$wp_customize,
			'color_setting_separator',
			array(
				'section' => 'colors',
			)
		)
	);

	// Colors -> Primary Color.
	$wp_customize->add_setting(
		'primary_color',
		array(
			'default'           => evie_default_color( 'primary' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'primary_color',
			array(
				'section'     => 'colors',
				'label'       => esc_html__( 'Primary Color', 'evie' ),
				'description' => esc_html__( 'The color for buttons, hover and active states of the links and menu items.', 'evie' ),
			)
		)
	);

	// Colors -> On Primary Color.
	$wp_customize->add_setting(
		'on_primary_color',
		array(
			'default'           => evie_default_color( 'on_primary' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_number',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Range_Control(
			$wp_customize,
			'on_primary_color',
			array(
				'section'     => 'colors',
				'label'       => esc_html__( 'On Primary Color', 'evie' ),
				'description' => esc_html__( 'The color for text and icons that appear in front of surfaces using the Primary Color.', 'evie' ),
				'input_attrs' => array(
					'min' => 0,
					'max' => 255,
				),
				'marks'       => array(
					0   => 0,
					255 => 255,
				),
			)
		)
	);

	// Colors -> Secondary Colors.
	$wp_customize->add_setting(
		'secondary_color',
		array(
			'default'           => evie_default_color( 'secondary' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'secondary_color',
			array(
				'section'     => 'colors',
				'label'       => esc_html__( 'Secondary Color', 'evie' ),
				'description' => esc_html__( 'The color for buttons, input fields and the Back to Top button.', 'evie' ),
			)
		)
	);

	// Colors -> On Secondary Color.
	$wp_customize->add_setting(
		'on_secondary_color',
		array(
			'default'           => evie_default_color( 'on_secondary' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_number',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Range_Control(
			$wp_customize,
			'on_secondary_color',
			array(
				'section'     => 'colors',
				'label'       => esc_html__( 'On Secondary Color', 'evie' ),
				'description' => esc_html__( 'The color for text and icons that appear in front of surfaces using the Secondary Color.', 'evie' ),
				'input_attrs' => array(
					'min' => 0,
					'max' => 255,
				),
				'marks'       => array(
					0   => 0,
					255 => 255,
				),
			)
		)
	);

	// Colors -> Text Color.
	$wp_customize->add_setting(
		'on_surface_color',
		array(
			'default'           => evie_default_color( 'on_surface' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_number',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Range_Control(
			$wp_customize,
			'on_surface_color',
			array(
				'section'     => 'colors',
				'label'       => esc_html__( 'Text Color', 'evie' ),
				'description' => sprintf(
					/* translators: %s: Default value */
					esc_html__( 'Base color for text that appear in the whole site. Default value: %s.', 'evie' ),
					evie_default_color( 'on_surface' )
				),
				'input_attrs' => array(
					'min' => 0,
					'max' => 255,
				),
				'marks'       => array(
					0   => 0,
					255 => 255,
				),
			)
		)
	);

	// Colors -> Text Color (Dark Mode).
	$wp_customize->add_setting(
		'on_surface_dark_color',
		array(
			'default'           => evie_default_color( 'on_surface_dark' ),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_number',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Range_Control(
			$wp_customize,
			'on_surface_dark_color',
			array(
				'section'     => 'colors',
				'label'       => esc_html__( 'Text Color (Dark Mode)', 'evie' ),
				'description' => sprintf(
					/* translators: %s: Default value */
					esc_html__( 'Base color for text that appear in the whole site. Default value: %s.', 'evie' ),
					evie_default_color( 'on_surface_dark' )
				),
				'input_attrs' => array(
					'min' => 0,
					'max' => 255,
				),
				'marks'       => array(
					0   => 0,
					255 => 255,
				),
			)
		)
	);

}

add_action( 'customize_register', 'evie_customizer_register_colors_settings', 20 );
