<?php
/**
 * Cursor
 *
 * @package    Evie_XT
 * @subpackage Modules/Cursor
 * @version    1.0.0
 */

/**
 * Returns the custom cursor type.
 *
 * @return string The custom cursor type.
 */
function evie_cursor_get_type() {
	return get_theme_mod( 'cursor', '' );
}

/**
 * Prints out a custom cursor HTML.
 */
function evie_cursor() {
	$cursor = evie_cursor_get_type();
	if ( ! empty( $cursor ) || is_customize_preview() ) {
		if ( empty( $cursor ) ) {
			$cursor = 'none';
		}
		echo '<div id="evie-cursor" class="evie-cursor is-style-' . esc_attr( $cursor ) . '">
				<span class="cursor-text-wrapper">
					<span class="cursor-text"></span>
				</span>
			</div><!-- #evie-cursor -->';
	}
}

add_action( 'wp_footer', 'evie_cursor' );

/**
 * Adds a custom cursor class to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array An array list of classes for the body element.
 */
function evie_cursor_body_classes( $classes = array() ) {
	if ( evie_cursor_get_type() ) {
		$classes[] = 'has-custom-cursor';
		if ( true === get_theme_mod( 'cursor_hide_default', false ) ) {
			$classes[] = 'has-no-cursor';
		}
	}
	return $classes;
}

add_filter( 'body_class', 'evie_cursor_body_classes' );

/**
 * Returns whether the cursor is enabled.
 *
 * @return bool Whether the cursor is enabled.
 */
function evie_cursor_customizer_is_cursor_enabled() {
	return '' !== evie_cursor_get_type();
}

/**
 * Registers the theme settings and controls in the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function evie_cursor_register_settings( $wp_customize ) {

	/**
	 * Cursor Section.
	 */
	$wp_customize->add_section(
		'cursor_section',
		array(
			'title'    => esc_html__( 'Cursor', 'evie-xt' ),
			'priority' => 100, // Before Widgets.
		)
	);

	// Cursor -> Custom Cursor.
	$wp_customize->add_setting(
		'cursor',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'cursor',
		array(
			'section'     => 'cursor_section',
			'label'       => esc_html__( 'Cursor', 'evie-xt' ),
			'description' => esc_html__( 'Displays an animated cursor.', 'evie-xt' ),
			'type'        => 'select',
			'choices'     => array(
				''       => esc_html__( 'None', 'evie-xt' ),
				'circle' => esc_html__( 'Circle', 'evie-xt' ),
				'dot'    => esc_html__( 'Dot', 'evie-xt' ),
			),
		)
	);

	// Cursor -> Hide Pointer.
	$wp_customize->add_setting(
		'cursor_hide_default',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'cursor_hide_default',
		array(
			'section'         => 'cursor_section',
			'label'           => esc_html__( 'Hide Default Cursor', 'evie-xt' ),
			'description'     => esc_html__( 'Displays only custom cursor and hides default cursor.', 'evie-xt' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_cursor_customizer_is_cursor_enabled',
		)
	);
}

add_action( 'customize_register', 'evie_cursor_register_settings', 20 );

/**
 * Loads dynamic logic for the customizer controls area.
 */
function evie_cursor_customizer_controls_scripts() {

	wp_enqueue_script( 'evie-cursor-customize-controls', plugins_url( 'js/customize-controls.js', __FILE__ ), array( 'customize-base', 'customize-controls', 'underscore' ), EVIE_XT_VERSION, true );

}

add_action( 'customize_controls_enqueue_scripts', 'evie_cursor_customizer_controls_scripts' );

/**
 * Binds JS handlers to instantly live-preview changes.
 */
function evie_cursor_customizer_preview_scripts() {

	wp_enqueue_script( 'evie-cursor-customize-preview', plugins_url( 'js/customize-preview.js', __FILE__ ), array( 'customize-preview', 'customize-selective-refresh' ), EVIE_XT_VERSION, true );

}

add_action( 'customize_preview_init', 'evie_cursor_customizer_preview_scripts' );

/**
 * Enqueues the scripts and stylesheets.
 */
function evie_cursor_enqueue_scripts() {

	wp_enqueue_style( 'evie-cursor', plugins_url( 'css/style.css', __FILE__ ), array( 'evie' ), EVIE_XT_VERSION );
	wp_style_add_data( 'evie-cursor', 'rtl', 'replace' );

	if ( ! wp_script_is( 'gsap', 'registered' ) ) {
		// GSAP.
		wp_register_script( 'gsap', plugins_url( 'js/vendor/gsap.min.js', __FILE__ ), array(), '3.6.0', true );
	}

	wp_enqueue_script( 'evie-cursor', plugins_url( 'js/index.js', __FILE__ ), array( 'gsap', 'evie' ), EVIE_XT_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'evie_cursor_enqueue_scripts' );
