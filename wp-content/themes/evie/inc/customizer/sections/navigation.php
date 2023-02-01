<?php
/**
 * Navigation Settings
 *
 * @package    Evie
 * @subpackage Customizer
 * @version    1.0.0
 */

/**
 * Renders the partial header navigation.
 */
function evie_customizer_get_navigation() {
	evie_header_navigation();
}

/**
 * Returns whether the Top Menu is selected.
 *
 * @return bool Whether the Top Menu is selected.
 */
function evie_customizer_is_top_menu() {
	return '' === get_theme_mod( 'nav_type', '' );
}

/**
 * Registers the theme settings and controls in the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function evie_customizer_register_navigation_settings( $wp_customize ) {

	/**
	 * Navigation Section
	 */
	$wp_customize->add_section(
		'navigation_section',
		array(
			'title'    => esc_html__( 'Navigation', 'evie' ),
			'priority' => 40, // Before Menus.
		)
	);

	// Navigation -> Desktop Menu Breakpoint.
	$wp_customize->add_setting(
		'nav_menu_breakpoint',
		array(
			'default'           => 1080,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_number',
		)
	);

	$wp_customize->add_control(
		'nav_menu_breakpoint',
		array(
			'section'     => 'navigation_section',
			'label'       => esc_html__( 'Desktop Menu Breakpoint', 'evie' ),
			'description' => esc_html__( 'The viewport width in pixels for when the mobile menu will become the desktop menu.', 'evie' ),
			'type'        => 'number',
			'input_attrs' => array(
				'min' => 1024,
				'max' => 1920,
			),
		)
	);

	// Navigation -> Menu Type.
	$wp_customize->add_setting(
		'nav_type',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'nav_type',
		array(
			'section'     => 'navigation_section',
			'label'       => esc_html__( 'Menu Type', 'evie' ),
			'description' => esc_html__( 'The navigation menu type.', 'evie' ),
			'type'        => 'select',
			'choices'     => array(
				''     => esc_html__( 'Top Menu', 'evie' ),
				'full' => esc_html__( 'Fullscreen Menu', 'evie' ),
			),
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'nav_type',
		array(
			'selector'            => '#site-header',
			'render_callback'     => 'evie_customizer_get_navigation',
			'container_inclusive' => true,
		)
	);

	// Navigation -> Align.
	$wp_customize->add_setting(
		'nav_align',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'nav_align',
		array(
			'section'         => 'navigation_section',
			'label'           => esc_html__( 'Menu Align', 'evie' ),
			'description'     => esc_html__( 'The alignment of menu items.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				''       => esc_html__( 'Left', 'evie' ),
				'center' => esc_html__( 'Center', 'evie' ),
				'right'  => esc_html__( 'Right', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_top_menu',
		)
	);

	// Navigation -> Sticky Menu.
	$wp_customize->add_setting(
		'nav_sticky_menu',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'nav_sticky_menu',
		array(
			'section'     => 'navigation_section',
			'label'       => esc_html__( 'Sticky Menu', 'evie' ),
			'description' => esc_html__( 'Displays sticky menu when scrolling.', 'evie' ),
			'type'        => 'checkbox',
		)
	);

	// Navigation -> Extra Menu Buttons.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'nav_menu_buttons',
			array(
				'section' => 'navigation_section',
				'label'   => esc_html__( 'Menu Buttons', 'evie' ),
			)
		)
	);

	// Navigation -> Search Button.
	$wp_customize->add_setting(
		'nav_search_button',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'nav_search_button',
		array(
			'section'     => 'navigation_section',
			'label'       => esc_html__( 'Search Button', 'evie' ),
			'description' => esc_html__( 'Displays a search button.', 'evie' ),
			'type'        => 'checkbox',
		)
	);

	// Navigation -> Login Button.
	$wp_customize->add_setting(
		'nav_login_button',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'nav_login_button',
		array(
			'section'     => 'navigation_section',
			'label'       => esc_html__( 'Log In Button', 'evie' ),
			'description' => esc_html__( 'Displays a Log In button.', 'evie' ),
			'type'        => 'checkbox',
		)
	);

	// Navigation -> Off-Canvas Sidebar Button.
	$wp_customize->add_setting(
		'nav_sidebar_button',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'nav_sidebar_button',
		array(
			'section'     => 'navigation_section',
			'label'       => esc_html__( 'Off-Canvas Sidebar Button', 'evie' ),
			'description' => esc_html__( 'Displays a toggle button to show or hide a slide-out sidebar.', 'evie' ),
			'type'        => 'checkbox',
		)
	);

	/**
	 * To improve user experience:
	 *
	 * - Move the Menus panel before the Header section.
	 */
	$wp_customize->get_panel( 'nav_menus' )->priority = 50;
}

add_action( 'customize_register', 'evie_customizer_register_navigation_settings', 20 );
