<?php
/**
 * Elements
 *
 * @package    Evie_XT
 * @subpackage Modules/Elements
 * @version    1.0.0
 */

/**
 * Retrieves the path of the highest priority template file that exists.
 *
 * This is the load order:
 *
 * yourtheme/<template_path>/$template_name
 * $default_path/$template_name
 *
 * @param string $block_name    The block name.
 * @param string $slug          The slug name for the generic template.
 * @param string $name          The name of the specialized template.
 * @param string $default_path  Default path if the template file doesn't exist. (default: '').
 * @return string The template filename if one is located.
 */
function evie_block_locate_template( $block_name, $slug, $name = null, $default_path = '' ) {
	/**
	 * Filters the block template directory path.
	 *
	 * @param string $path The template directory path. Default 'template-parts/blocks'.
	 */
	$template_path = apply_filters( 'evie_block_template_dir', 'template-parts/blocks' );

	$template_path = "$template_path/{$block_name}";

	$templates = array();
	if ( ! empty( $name ) ) {
		$templates[] = trailingslashit( $template_path ) . "{$slug}-{$name}.php";
	}

	$templates[] = trailingslashit( $template_path ) . "{$slug}.php";

	$template = locate_template( $templates );

	if ( ! $template ) {
		$template = evie_block_default_template( $slug, $name, $default_path );
	}

	$template = wp_normalize_path( $template );

	/**
	 * Filters the path of the template file.
	 *
	 * @param string $template      The path of the template file.
	 * @param string $slug          The slug name for the generic template.
	 * @param string $name          The name of the specialized template.
	 * @param string $default_path  Default path if the template file doesn't exist.
	 */
	return apply_filters( 'evie_block_locate_template', $template, $slug, $name, $default_path );
}

/**
 * Loads the template file for the block.
 *
 * @param string $block_name    The block name.
 * @param string $template_slug The slug name for the generic template.
 * @param string $template_name The name of the specialized template.
 * @param array  $attributes    The attributes list for the block.
 * @param string $default_path Default path. Default: ''.
 */
function evie_block_template( $block_name, $template_slug, $template_name = null, $attributes = array(), $default_path = '' ) {
	$cache_key = sanitize_key( implode( '-', array( 'template', $block_name, $template_slug, $template_name, EVIE_XT_VERSION ) ) );

	$template = (string) wp_cache_get( $cache_key, 'evie' );
	if ( empty( $template ) ) {

		if ( empty( $default_path ) ) {
			$default_path = plugin_dir_path( __FILE__ ) . "blocks/{$block_name}/templates";
		}

		$template = evie_block_locate_template( $block_name, $template_slug, $template_name, $default_path );
		wp_cache_set( $cache_key, $template, 'evie', HOUR_IN_SECONDS );
	}

	if ( ! empty( $template ) ) {
		include $template;
	}
}

/**
 * Retrieves default template path.
 *
 * @param string $slug          The slug name for the generic template.
 * @param string $name          The name of the specialized template.
 * @param string $default_path  Default path if the template file doesn't exist. (default: '').
 */
function evie_block_default_template( $slug, $name = null, $default_path = '' ) {

	if ( empty( $default_path ) ) {
		$default_path = trailingslashit( EVIE_XT_PATH ) . 'templates';
	}

	$templates = array();

	if ( ! empty( $name ) ) {
		$templates[] = trailingslashit( $default_path ) . "{$slug}-{$name}.php";
	}

	$templates[] = trailingslashit( $default_path ) . "{$slug}.php";

	$template = '';

	foreach ( $templates as $template_file ) {
		if ( file_exists( $template_file ) ) {
			$template = $template_file;
			break;
		}
	}

	return $template;
}

/**
 * Determines whether blocks can be loaded.
 *
 * @since 1.0.4
 *
 * @return bool Whether blocks can be loaded.
 */
function evie_can_load_blocks() {
	return function_exists( 'evie_setup' );
}

/**
 * Registers all widgets in the folder 'widgets'.
 */
function evie_elements_register_widgets() {
	flextension_load_files( 'widgets/*.php', plugin_dir_path( __FILE__ ) );
}

/**
 * Registers all blocks in the folder 'blocks/src'.
 */
function evie_elements_register_blocks() {
	flextension_load_files( 'blocks/*.php', plugin_dir_path( __FILE__ ) );
}

/**
 * Registers block patterns and categories.
 */
function evie_elements_register_block_patterns() {
	$block_pattern_categories = array(
		'evie-1'        => array( 'label' => esc_html__( 'Evie - Creative Agency', 'evie-xt' ) ),
		'evie-2'        => array( 'label' => esc_html__( 'Evie - Creative Portfolio', 'evie-xt' ) ),
		'evie-3'        => array( 'label' => esc_html__( 'Evie - Creative Network', 'evie-xt' ) ),
		'evie-4'        => array( 'label' => esc_html__( 'Evie - Creative Blog', 'evie-xt' ) ),
		'evie-pages'    => array( 'label' => esc_html__( 'Evie - Page Templates', 'evie-xt' ) ),
		'evie-posts'    => array( 'label' => esc_html__( 'Evie - Post Templates', 'evie-xt' ) ),
		'evie-projects' => array( 'label' => esc_html__( 'Evie - Project Templates', 'evie-xt' ) ),
	);

	/**
	 * Filters the theme block pattern categories.
	 *
	 * @param array[] $block_pattern_categories {
	 *     An associative array of block pattern categories, keyed by category name.
	 *
	 *     @type array[] $properties {
	 *         An array of block category properties.
	 *
	 *         @type string $label A human-readable label for the pattern category.
	 *     }
	 * }
	 */
	$block_pattern_categories = apply_filters( 'evie_block_pattern_categories', $block_pattern_categories );

	foreach ( $block_pattern_categories as $name => $properties ) {
		if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $name ) ) {
			register_block_pattern_category( $name, $properties );
		}
	}

	$block_patterns = array(
		'evie-1-about',
		'evie-1-blog',
		'evie-1-contact',
		'evie-1-home',
		'evie-2-about',
		'evie-2-contact',
		'evie-2-home',
		'evie-3-about',
		'evie-3-authors',
		'evie-3-contact',
		'evie-3-home',
		'evie-4-about',
		'evie-4-contact',
		'evie-4-home',
		'evie-post-1',
		'evie-post-2',
		'evie-project-1',
		'evie-project-2',
		'evie-project-3',
		'evie-project-4',
		'evie-project-5',
		'evie-project-6',
	);

	/**
	 * Filters the theme block patterns.
	 *
	 * @param array $block_patterns An array list of block patterns by name.
	 */
	$block_patterns = apply_filters( 'evie_block_patterns', $block_patterns );

	foreach ( $block_patterns as $block_pattern ) {
		$pattern_file = plugin_dir_path( __FILE__ ) . 'patterns/' . $block_pattern . '.php';

		register_block_pattern(
			'evie/' . $block_pattern,
			require $pattern_file
		);
	}
}

add_action( 'init', 'evie_elements_register_block_patterns' );

/**
 * Returns a block pattern markup for the Contact Form 7.
 *
 * @return string A block pattern markup.
 */
function evie_block_pattern_contact_form_template() {
	$template = '';
	if ( class_exists( 'WPCF7_ContactForm', false ) ) {
		$forms = WPCF7_ContactForm::find(
			array(
				'posts_per_page' => 1,
			)
		);
		if ( ! empty( $forms ) && isset( $forms[0] ) ) {
			$form     = $forms[0];
			$template = '<!-- wp:contact-form-7/contact-form-selector --><div class="wp-block-contact-form-7-contact-form-selector">[contact-form-7 id="' . esc_attr( $form->id() ) . '" title="' . esc_attr( $form->title() ) . '"]</div><!-- /wp:contact-form-7/contact-form-selector -->';
		}
	}
	return $template;
}

/**
 * Registers scripts.
 */
function evie_block_register_scripts() {

	wp_register_style( 'evie-blocks', plugins_url( 'css/style.css', __FILE__ ), array(), EVIE_XT_VERSION );

	wp_style_add_data( 'evie-blocks', 'rtl', 'replace' );

	wp_register_style( 'evie-edit-blocks', plugins_url( 'css/editor.css', __FILE__ ), array( 'evie-blocks' ), EVIE_XT_VERSION );

	wp_style_add_data( 'evie-edit-blocks', 'rtl', 'replace' );

	// GSAP.
	wp_register_script( 'gsap', plugins_url( 'js/vendor/gsap.min.js', __FILE__ ), array(), '3.6.0', true );

	// Blocks.
	wp_register_script( 'evie-blocks', plugins_url( 'js/index.js', __FILE__ ), array( 'imagesloaded', 'gsap', 'scrollmagic', 'flextension-carousel', 'evie' ), EVIE_XT_VERSION, true );

	// Edit blocks.
	wp_register_script(
		'evie-edit-blocks',
		plugins_url( 'js/blocks.js', __FILE__ ),
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-components', 'wp-element', 'wp-keycodes', 'wp-i18n', 'gsap', 'flextension-carousel', 'flextension-editor', 'evie' ),
		EVIE_XT_VERSION,
		true
	);
}

add_action( 'init', 'evie_block_register_scripts' );
