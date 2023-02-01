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
function evie_customizer_register_typography_settings( $wp_customize ) {

	/**
	 * Typography Panel
	 */
	$wp_customize->add_panel(
		'typography_panel',
		array(
			'title'       => esc_html__( 'Typography', 'evie' ),
			'description' => '',
			'capability'  => 'edit_theme_options',
			'priority'    => 120, // Before Homepage Settings.
		)
	);

	/**
	 * Typography -> Base Font.
	 */
	$wp_customize->add_section(
		'typography_base_section',
		array(
			'panel' => 'typography_panel',
			'title' => esc_html__( 'Base Font', 'evie' ),
		)
	);

	$font_options = array(
		array(
			'label'   => esc_html__( 'Theme Fonts', 'evie' ),
			'options' => array(
				''        => esc_html__( 'Default', 'evie' ),
				'Poppins' => 'Poppins',
				'Roboto'  => 'Roboto',
			),
		),
	);

	$font_types = evie_fonts();
	foreach ( $font_types as $key => $type ) {
		$font_options[] = array(
			'label'   => $type['title'],
			'options' => $type['fonts'],
		);
	}

	// Typography -> Base Font.
	$wp_customize->add_setting(
		'typography_font_base',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_dropdown',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Dropdown_Control(
			$wp_customize,
			'typography_font_base',
			array(
				'section' => 'typography_base_section',
				'label'   => esc_html__( 'Font Family', 'evie' ),
				'groups'  => $font_options,
			)
		)
	);

	// Typography -> Base Font Size.
	$wp_customize->add_setting(
		'typography_font_base_sizes',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_sizes',
		)
	);

	// Typography -> Base Font Size.
	$wp_customize->add_control(
		new Evie_Customize_Unit_Control(
			$wp_customize,
			'typography_font_base_sizes',
			array(
				'section'     => 'typography_base_section',
				'label'       => esc_html__( 'Font Size', 'evie' ),
				'description' => esc_html__( 'If no unit is specified, px will be used by default.', 'evie' ),
				'placeholder' => esc_html__( 'Default', 'evie' ),
			)
		)
	);

	/**
	 * Typography -> Headings.
	 */
	$wp_customize->add_section(
		'typography_headings_section',
		array(
			'panel' => 'typography_panel',
			'title' => esc_html__( 'Headings', 'evie' ),
		)
	);

	// Typography -> Headings.
	$wp_customize->add_setting(
		'typography_font_headings',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_dropdown',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Dropdown_Control(
			$wp_customize,
			'typography_font_headings',
			array(
				'section' => 'typography_headings_section',
				'label'   => esc_html__( 'Font Family', 'evie' ),
				'groups'  => $font_options,
			)
		)
	);

	// Typography -> Font Size.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'typography_font_size_heading',
			array(
				'section'     => 'typography_headings_section',
				'label'       => esc_html__( 'Font Size', 'evie' ),
				'description' => esc_html__( 'If no unit is specified, px will be used by default.', 'evie' ),
			)
		)
	);

	// Typography -> H1.
	$wp_customize->add_setting(
		'typography_font_h1_sizes',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_sizes',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Unit_Control(
			$wp_customize,
			'typography_font_h1_sizes',
			array(
				'section'     => 'typography_headings_section',
				'label'       => esc_html__( 'H1', 'evie' ),
				'placeholder' => esc_html__( 'Default', 'evie' ),
			)
		)
	);

	// Typography -> H2.
	$wp_customize->add_setting(
		'typography_font_h2_sizes',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_sizes',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Unit_Control(
			$wp_customize,
			'typography_font_h2_sizes',
			array(
				'section'     => 'typography_headings_section',
				'label'       => esc_html__( 'H2', 'evie' ),
				'placeholder' => esc_html__( 'Default', 'evie' ),
			)
		)
	);

	// Typography -> H3.
	$wp_customize->add_setting(
		'typography_font_h3_sizes',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_sizes',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Unit_Control(
			$wp_customize,
			'typography_font_h3_sizes',
			array(
				'section'     => 'typography_headings_section',
				'label'       => esc_html__( 'H3', 'evie' ),
				'placeholder' => esc_html__( 'Default', 'evie' ),
			)
		)
	);

	// Typography -> H4.
	$wp_customize->add_setting(
		'typography_font_h4_sizes',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_sizes',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Unit_Control(
			$wp_customize,
			'typography_font_h4_sizes',
			array(
				'section'     => 'typography_headings_section',
				'label'       => esc_html__( 'H4', 'evie' ),
				'placeholder' => esc_html__( 'Default', 'evie' ),
			)
		)
	);

	// Typography -> H5.
	$wp_customize->add_setting(
		'typography_font_h5_sizes',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_sizes',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Unit_Control(
			$wp_customize,
			'typography_font_h5_sizes',
			array(
				'section'     => 'typography_headings_section',
				'label'       => esc_html__( 'H5', 'evie' ),
				'placeholder' => esc_html__( 'Default', 'evie' ),
			)
		)
	);

	// Typography -> H6.
	$wp_customize->add_setting(
		'typography_font_h6_sizes',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_sizes',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_Unit_Control(
			$wp_customize,
			'typography_font_h6_sizes',
			array(
				'section'     => 'typography_headings_section',
				'label'       => esc_html__( 'H6', 'evie' ),
				'placeholder' => esc_html__( 'Default', 'evie' ),
			)
		)
	);
}

add_action( 'customize_register', 'evie_customizer_register_typography_settings', 20 );
