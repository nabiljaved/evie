<?php
/**
 * Theme Options Module
 *
 * Adds additional options for the post and page.
 *
 * @package    Evie_XT
 * @subpackage Modules/Theme_Options
 * @version    1.0.0
 */

/**
 * Registers the Theme Options module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Theme Options', 'evie-xt' ),
		'description'   => esc_html__( 'Adds additional options for the post and page.', 'evie-xt' ),
		'dependencies'  => array( 'meta-box' ),
		'enabled'       => true,
		'load_callback' => 'evie_theme_options_module_load',
	)
);

/**
 * Loads the Theme Options module.
 */
function evie_theme_options_module_load() {
	add_action( 'init', 'evie_theme_options_register_meta' );

	if ( is_admin() ) {
		add_filter( 'flextension_featured_media_meta_fields', 'evie_theme_options_featured_media_meta_fields', 10, 2 );
		// Initialize the meta box on the edit screen.
		add_action( 'load-post.php', 'evie_theme_options_init_meta_box' );
		add_action( 'load-post-new.php', 'evie_theme_options_init_meta_box' );

		add_action( 'enqueue_block_editor_assets', 'evie_theme_options_enqueue_scripts' );
	}
}

/**
 * Registers post metadata.
 */
function evie_theme_options_register_meta() {
	register_post_meta(
		'post',
		'_evie_layout',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_key',
			'auth_callback'     => 'evie_theme_options_can_edit_posts',
		)
	);
}

/**
 * Loads setting panel scripts and stylesheets.
 */
function evie_theme_options_enqueue_scripts() {
	wp_enqueue_style( 'evie-theme-options', plugins_url( 'css/style.css', __FILE__ ), array(), EVIE_XT_VERSION );

	wp_enqueue_script(
		'evie-theme-options',
		plugins_url( 'js/index.js', __FILE__ ),
		array(
			'wp-components',
			'wp-compose',
			'wp-core-data',
			'wp-data',
			'wp-edit-post',
			'wp-i18n',
			'wp-plugins',
		),
		EVIE_XT_VERSION,
		true
	);

	$screen = get_current_screen();

	if ( in_array( $screen->post_type, array( 'post' ), true ) ) {

		wp_enqueue_style( 'evie-theme-options-post-settings', plugins_url( 'css/post.css', __FILE__ ), array(), EVIE_XT_VERSION );

		wp_enqueue_script(
			'evie-theme-options-post-settings',
			plugins_url( 'js/post.js', __FILE__ ),
			array(
				'wp-components',
				'wp-compose',
				'wp-core-data',
				'wp-data',
				'wp-edit-post',
				'wp-i18n',
				'wp-plugins',
			),
			EVIE_XT_VERSION,
			true
		);

	}

}

/**
 * Initializes the meta box on the project edit screen.
 */
function evie_theme_options_init_meta_box() {
	add_filter( 'flextension_meta_box_sections', 'evie_theme_options_register_meta_box_sections' );
}

/**
 * Returns the list of the setting fields for the featured media.
 *
 * - Adds a media type field.
 * - Sets the dependencies for the exisiting fields.
 *
 * @param array   $fields An array list of the setting fields.
 * @param WP_Post $post   Post object.
 * @return array An array list of the setting fields.
 */
function evie_theme_options_featured_media_meta_fields( $fields = array(), $post = 0 ) {
	if ( 'post' === $post->post_type ) {
		$fields[0]['options'] = array(
			'' => esc_html__( 'Slider', 'evie-xt' ),
		);
	}
	return $fields;
}

/**
 * Returns whether the current user can edit posts.
 *
 * @return bool Whether the current user can edit posts.
 */
function evie_theme_options_can_edit_posts() {
	return current_user_can( 'edit_posts' );
}

/**
 * Registers the meta box sections on the page edit screen.
 *
 * @param array $sections An array list of the meta box sections.
 */
function evie_theme_options_register_meta_box_sections( $sections = array() ) {

	$prefix = '_evie_';

	$post_types = array( 'post', 'page', 'project' );

	/**
	 * Navigation options.
	 */
	$sections[] = array(
		'id'         => 'navigation',
		'title'      => esc_html__( 'Navigation', 'evie-xt' ),
		'post_types' => $post_types,
		'context'    => 'side',
		'fields'     => array(
			array(
				'name'        => $prefix . 'hide_menu',
				'description' => esc_html__( 'Hide main menu', 'evie-xt' ),
				'type'        => 'checkbox',
			),
			array(
				'name'         => $prefix . 'transparent_menu',
				'description'  => esc_html__( 'Transparent background', 'evie-xt' ),
				'type'         => 'checkbox',
				'dependencies' => array(
					array(
						'name'  => $prefix . 'hide_menu',
						'value' => false,
					),
				),
			),
			array(
				'name'         => $prefix . 'menu_color',
				'label'        => esc_html__( 'Text mode', 'evie-xt' ),
				'type'         => 'select',
				'options'      => array(
					''      => esc_html__( 'Default', 'evie-xt' ),
					'dark'  => esc_html__( 'Dark', 'evie-xt' ),
					'light' => esc_html__( 'Light', 'evie-xt' ),
				),
				'dependencies' => array(
					array(
						'name'  => $prefix . 'hide_menu',
						'value' => false,
					),
					array(
						'name'  => $prefix . 'transparent_menu',
						'value' => true,
					),
				),
			),
		),
	);

	/**
	 * Header options.
	 */
	$sections[] = array(
		'id'         => 'header',
		'title'      => esc_html__( 'Header', 'evie-xt' ),
		'post_types' => 'page',
		'context'    => 'side',
		'fields'     => array(
			array(
				'name'    => $prefix . 'header',
				'label'   => esc_html__( 'Header', 'evie-xt' ),
				'type'    => 'image_select',
				'options' => array(
					''           => plugins_url( 'images/header/description.svg', __FILE__ ),
					'breadcrumb' => plugins_url( 'images/header/breadcrumb.svg', __FILE__ ),
					'none'       => plugins_url( 'images/header/none.svg', __FILE__ ),
				),
			),
			array(
				'name'         => $prefix . 'header_desc',
				'label'        => esc_html__( 'Description', 'evie-xt' ),
				'type'         => 'text',
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '===',
						'value'    => '',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_size',
				'label'        => esc_html__( 'Header size', 'evie-xt' ),
				'type'         => 'select',
				'options'      => array(
					''      => esc_html__( 'Default', 'evie-xt' ),
					'short' => esc_html__( 'Short', 'evie-xt' ),
					'tall'  => esc_html__( 'Tall', 'evie-xt' ),
					'full'  => esc_html__( 'Full screen', 'evie-xt' ),
				),
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_width',
				'label'        => esc_html__( 'Content width', 'evie-xt' ),
				'type'         => 'select',
				'options'      => array(
					''     => esc_html__( 'Default', 'evie-xt' ),
					'wide' => esc_html__( 'Wide width', 'evie-xt' ),
					'full' => esc_html__( 'Full width', 'evie-xt' ),
				),
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_align',
				'label'        => esc_html__( 'Text align', 'evie-xt' ),
				'type'         => 'select',
				'options'      => array(
					''       => esc_html__( 'Default', 'evie-xt' ),
					'left'   => esc_html__( 'Left', 'evie-xt' ),
					'center' => esc_html__( 'Center', 'evie-xt' ),
					'right'  => esc_html__( 'Right', 'evie-xt' ),
				),
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_bg',
				'label'        => esc_html__( 'Background', 'evie-xt' ),
				'type'         => 'select',
				'options'      => array(
					''      => esc_html__( 'Default', 'evie-xt' ),
					'color' => esc_html__( 'Color', 'evie-xt' ),
					'image' => esc_html__( 'Image', 'evie-xt' ),
				),
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_bg_color',
				'label'        => esc_html__( 'Background color', 'evie-xt' ),
				'type'         => 'color',
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
					array(
						'name'     => $prefix . 'header_bg',
						'operator' => '===',
						'value'    => 'color',
					),
				),
			),
			array(
				'label'        => esc_html__( 'Background image', 'evie-xt' ),
				'text'         => esc_html__( 'You can set the background image in the Featured Image panel above.', 'evie-xt' ),
				'type'         => 'label',
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
					array(
						'name'     => $prefix . 'header_bg',
						'operator' => '===',
						'value'    => 'image',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_bg_position',
				'label'        => esc_html__( 'Background image position', 'evie-xt' ),
				'type'         => 'select',
				'options'      => array(
					'left top'      => esc_html__( 'Left Top', 'evie-xt' ),
					'left center'   => esc_html__( 'Left Center', 'evie-xt' ),
					'left bottom'   => esc_html__( 'Left Bottom', 'evie-xt' ),
					'right top'     => esc_html__( 'Right Top', 'evie-xt' ),
					'right center'  => esc_html__( 'Right Center', 'evie-xt' ),
					'right bottom'  => esc_html__( 'Right Bottom', 'evie-xt' ),
					'center top'    => esc_html__( 'Center Top', 'evie-xt' ),
					''              => esc_html__( 'Center Center', 'evie-xt' ),
					'center bottom' => esc_html__( 'Center Bottom', 'evie-xt' ),
				),
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
					array(
						'name'     => $prefix . 'header_bg',
						'operator' => '===',
						'value'    => 'image',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_bg_size',
				'label'        => esc_html__( 'Background image size', 'evie-xt' ),
				'type'         => 'select',
				'options'      => array(
					'auto'    => esc_html__( 'Original', 'evie-xt' ),
					'contain' => esc_html__( 'Fit to Screen', 'evie-xt' ),
					''        => esc_html__( 'Fill Screen', 'evie-xt' ),
				),
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
					array(
						'name'     => $prefix . 'header_bg',
						'operator' => '===',
						'value'    => 'image',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_bg_attachment',
				'description'  => esc_html__( 'Fixed background', 'evie-xt' ),
				'type'         => 'checkbox',
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
					array(
						'name'     => $prefix . 'header_bg',
						'operator' => '===',
						'value'    => 'image',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_bg_repeat',
				'description'  => esc_html__( 'Repeated background', 'evie-xt' ),
				'type'         => 'checkbox',
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
					array(
						'name'     => $prefix . 'header_bg',
						'operator' => '===',
						'value'    => 'image',
					),
					array(
						'name'     => $prefix . 'header_bg_size',
						'operator' => '!==',
						'value'    => 'cover',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_bg_overlay',
				'label'        => esc_html__( 'Background Overlay', 'evie-xt' ),
				'type'         => 'select',
				'options'      => array(
					''      => esc_html__( 'Gradient', 'evie-xt' ),
					'color' => esc_html__( 'Color', 'evie-xt' ),
				),
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
					array(
						'name'     => $prefix . 'header_bg',
						'operator' => '===',
						'value'    => 'image',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_overlay_color',
				'label'        => esc_html__( 'Overlay color', 'evie-xt' ),
				'type'         => 'color',
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
					array(
						'name'     => $prefix . 'header_bg',
						'operator' => '===',
						'value'    => 'image',
					),
					array(
						'name'     => $prefix . 'header_bg_overlay',
						'operator' => '===',
						'value'    => 'color',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_overlay_opacity',
				'label'        => esc_html__( 'Overlay opacity', 'evie-xt' ),
				'type'         => 'range',
				'default'      => 75,
				'attributes'   => array(
					'min' => 0,
					'max' => 100,
				),
				'marks'        => array(
					0   => 0,
					25  => 25,
					50  => 50,
					75  => 75,
					100 => 100,
				),
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
					array(
						'name'     => $prefix . 'header_bg',
						'operator' => '===',
						'value'    => 'image',
					),
					array(
						'name'     => $prefix . 'header_bg_overlay',
						'operator' => '===',
						'value'    => 'color',
					),
				),
			),
			array(
				'name'         => $prefix . 'header_text_mode',
				'label'        => esc_html__( 'Text mode', 'evie-xt' ),
				'type'         => 'select',
				'options'      => array(
					''      => esc_html__( 'Default', 'evie-xt' ),
					'dark'  => esc_html__( 'Dark', 'evie-xt' ),
					'light' => esc_html__( 'Light', 'evie-xt' ),
				),
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
					array(
						'name'     => $prefix . 'header_bg',
						'operator' => '!==',
						'value'    => '',
					),
					array(
						'name'     => $prefix . 'header_bg_overlay',
						'operator' => '===',
						'value'    => 'color',
					),
				),
			),
			array(
				'name'         => $prefix . 'hide_header_gap',
				'description'  => esc_html__( 'Remove header gap', 'evie-xt' ),
				'type'         => 'checkbox',
				'dependencies' => array(
					array(
						'name'     => $prefix . 'header',
						'operator' => '!==',
						'value'    => 'none',
					),
				),
			),
		),
	);

	/**
	 * Footer options.
	 */
	$sections[] = array(
		'id'         => 'footer',
		'title'      => esc_html__( 'Footer', 'evie-xt' ),
		'post_types' => $post_types,
		'context'    => 'side',
		'fields'     => array(
			array(
				'name'        => $prefix . 'hide_footer',
				'description' => esc_html__( 'Hide footer', 'evie-xt' ),
				'type'        => 'checkbox',
			),
			array(
				'name'         => $prefix . 'hide_footer_gap',
				'description'  => esc_html__( 'Remove footer gap', 'evie-xt' ),
				'type'         => 'checkbox',
				'dependencies' => array(
					array(
						'name'  => $prefix . 'hide_footer',
						'value' => false,
					),
				),
			),
		),
	);

	return $sections;
}
