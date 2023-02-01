<?php
/**
 * Footer Settings
 *
 * @package    Evie
 * @subpackage Customizer
 * @version    1.0.0
 */

/**
 * Returns the footer widgets section.
 */
function evie_customizer_get_footer_widgets_section() {
	evie_footer_widgets();
}

/**
 * Returns the footer social links.
 */
function evie_customizer_get_footer_social_links() {
	evie_footer_social_links();
}

/**
 * Registers the theme settings and controls in the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function evie_customizer_register_footer_settings( $wp_customize ) {

	/**
	 * Footer Section.
	 */
	$wp_customize->add_section(
		'footer_section',
		array(
			'title'    => esc_html__( 'Footer', 'evie' ),
			'priority' => 101, // Before Widgets.
		)
	);

	// Footer Widgets.
	$wp_customize->add_setting(
		'footer_widgets',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$footer_widgets_description = sprintf(
		wp_kses(
			/* translators: %s: URL of the Menus panel. */
			__( 'You can setup footer widgets in the <a href="%s" class="link-panel-button">Widgets</a> panel.', 'evie' ),
			array(
				'a' => array(
					'href'  => array(),
					'class' => array(),
				),
			)
		),
		esc_url(
			add_query_arg(
				array(
					'autofocus[panel]' => 'widgets',
				),
				admin_url( 'customize.php' )
			)
		)
	);

	$wp_customize->add_control(
		'footer_widgets',
		array(
			'section'     => 'footer_section',
			'label'       => esc_html__( 'Footer Widgets', 'evie' ),
			'description' => $footer_widgets_description,
			'type'        => 'select',
			'choices'     => array(
				''  => esc_html__( 'Hide', 'evie' ),
				'1' => esc_html__( '1 column', 'evie' ),
				'2' => esc_html__( '2 columns', 'evie' ),
				'3' => esc_html__( '3 columns', 'evie' ),
				'4' => esc_html__( '4 columns', 'evie' ),
			),
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'footer_widgets',
		array(
			'selector'            => '#site-footer .footer-widgets',
			'render_callback'     => 'evie_customizer_get_footer_widgets_section',
			'container_inclusive' => true,
		)
	);

	// Footer Text.
	$wp_customize->add_setting(
		'footer_text',
		array(
			'default'           => evie_default_site_info(),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		new Evie_Customize_HTML_Control(
			$wp_customize,
			'footer_text',
			array(
				'section'     => 'footer_section',
				'label'       => esc_html__( 'Footer Text', 'evie' ),
				'description' => esc_html__( 'Minimal HTML tags are allowed.', 'evie' ),
			)
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'footer_text',
		array(
			'selector'         => '#site-footer .footer-text',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Footer Menu.
	$wp_customize->add_setting(
		'footer_menu',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$footer_menu_description = sprintf(
		wp_kses(
			/* translators: %s: URL of the Menus panel. */
			__( 'You can setup a footer menu in the <a href="%s" class="link-panel-button">Menus</a> panel.', 'evie' ),
			array(
				'a' => array(
					'href'  => array(),
					'class' => array(),
				),
			)
		),
		esc_url(
			add_query_arg(
				array(
					'autofocus[panel]' => 'nav_menus',
				),
				admin_url( 'customize.php' )
			)
		)
	);

	$wp_customize->add_control(
		'footer_menu',
		array(
			'section'     => 'footer_section',
			'label'       => esc_html__( 'Footer Menu', 'evie' ),
			'description' => $footer_menu_description,
			'type'        => 'select',
			'choices'     => array(
				''     => esc_html__( 'Hide', 'evie' ),
				'show' => esc_html__( 'Show', 'evie' ),
			),
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'footer_menu',
		array(
			'selector'         => '#site-footer .footer-menu-wrapper',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Footer Social Links.
	$wp_customize->add_setting(
		'footer_links',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$footer_links_description = '';

	if ( function_exists( 'flextension_social_links_list' ) ) {

		$footer_links_description = sprintf(
			wp_kses(
				/* translators: %s: URL of the Menus panel. */
				__( 'You can setup social links in the <a href="%s" target="_blank">Social Links</a> settings.', 'evie' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			),
			esc_url( flextension_get_admin_page_url( 'social-links', 'general' ) )
		);

	} else {

		$footer_links_description = sprintf(
			wp_kses(
				/* translators: %s: URL of the Menus panel. */
				__( 'The <a href="%s">Social Links</a> module must be enabled.', 'evie' ),
				array(
					'a' => array(
						'href' => array(),
					),
				)
			),
			esc_url(
				add_query_arg(
					array(
						'page' => 'flextension-manager',
					),
					admin_url( 'admin.php' )
				)
			)
		);

	}

	$wp_customize->add_control(
		'footer_links',
		array(
			'section'     => 'footer_section',
			'label'       => esc_html__( 'Social Links', 'evie' ),
			'description' => $footer_links_description,
			'type'        => 'select',
			'choices'     => array(
				''      => esc_html__( 'None', 'evie' ),
				'icons' => esc_html__( 'Icons', 'evie' ),
				'names' => esc_html__( 'Names', 'evie' ),
			),
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'footer_links',
		array(
			'selector'            => '#site-footer .footer-social-links',
			'render_callback'     => 'evie_customizer_get_footer_social_links',
			'container_inclusive' => false,
		)
	);
}

add_action( 'customize_register', 'evie_customizer_register_footer_settings', 20 );
