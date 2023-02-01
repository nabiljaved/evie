<?php
/**
 * Evie functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Evie
 * @version 1.0.0
 */

/**
 * Evie only works in WordPress 5.8 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '5.8', '<' ) ) {
	// Prevent Evie from running on WordPress versions prior to 5.8.
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

if ( ! function_exists( 'evie_setup' ) ) {

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function evie_setup() {
		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'evie', get_parent_theme_file_path( 'languages' ) );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		set_post_thumbnail_size( 330, 220, true );

		add_image_size( 'evie-square', 360, 360, true );

		add_image_size( 'evie-portrait', 360, 540, true );

		add_image_size( 'evie-landscape', 600, 400, true );

		add_image_size( 'evie-wide', 1200, 9999 );

		add_image_size( 'evie-fullwidth', 1920, 9999 );

		// Register navigation locations.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'evie' ),
				'mobile'  => esc_html__( 'Mobile Menu', 'evie' ),
				'footer'  => esc_html__( 'Footer Menu', 'evie' ),
			)
		);

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			)
		);

		/**
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'audio',
				'gallery',
				'image',
				'link',
				'quote',
				'video',
				'status',
			)
		);

		// Set up the WordPress core Logo feature.
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 75,
				'width'       => 75,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		// Set up the WordPress core Header feature.
		add_theme_support(
			'custom-header',
			array(
				'header-text' => false,
				'width'       => 1920,
				'height'      => 300,
				'flex-height' => true,
			)
		);

		// Add theme support for Custom Background.
		add_theme_support( 'custom-background' );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for Dark editor style.
		add_theme_support( 'dark-editor-style' );

		// This theme styles the visual editor to resemble the theme style.
		add_editor_style( 'assets/css/editor.css' );

		// Add support and settings for the Flextension plugin.
		add_theme_support(
			'flextension',
			array(
				'editor'              => array( 'posts_page' => true ),
				'featured-categories' => array( 'category', 'project_category' ),
				'featured-media'      => array( 'post', 'project' ),
			)
		);
	}
}

add_action( 'after_setup_theme', 'evie_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower Priority callbacks.
 *
 * @global int $content_width
 */
function evie_content_width() {
	/**
	 * Filter Evie content width of the theme.
	 *
	 * @param int $content_width Content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters( 'evie_content_width', 768 ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound, WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedVariableFound
}

add_action( 'after_setup_theme', 'evie_content_width', 0 );

/**
 * Registers sidebars and widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function evie_register_sidebars() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'Main Sidebar', 'evie' ),
			'id'            => 'main',
			'description'   => esc_html__( 'Add widgets here to appear in the main sidebar on your site.', 'evie' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h2>',
			'after_title'   => '</h2></div>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Navigation Menu', 'evie' ),
			'id'            => 'menu',
			'description'   => esc_html__( 'Add widgets here to appear in the navigation menu.', 'evie' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h2>',
			'after_title'   => '</h2></div>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer #1', 'evie' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here to appear in the first column of the footer.', 'evie' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h2>',
			'after_title'   => '</h2></div>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer #2', 'evie' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Add widgets here to appear in the second column of the footer.', 'evie' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h2>',
			'after_title'   => '</h2></div>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer #3', 'evie' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Add widgets here to appear in the third column of the footer.', 'evie' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h2>',
			'after_title'   => '</h2></div>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer #4', 'evie' ),
			'id'            => 'footer-4',
			'description'   => esc_html__( 'Add widgets here to appear in the fourth column of the footer.', 'evie' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h2>',
			'after_title'   => '</h2></div>',
		)
	);

}

add_action( 'widgets_init', 'evie_register_sidebars' );

/**
 * Registers styles and scripts.
 */
function evie_register_assets() {
	$theme_version = wp_get_theme()->get( 'Version' );

	/**
	 * CSS Styles
	 */

	$style_deps = array();

	if ( wp_style_is( 'flextension-extensions', 'registered' ) ) {
		$style_deps[] = 'flextension-extensions';
	}

	if ( wp_style_is( 'flextension-elements', 'registered' ) ) {
		$style_deps[] = 'flextension-elements';
	}

	/**
	 * Filters an array of registered style handles this style depends on.
	 *
	 * @since 0.2.0
	 *
	 * @param string[] $style_deps An array of registered style handles this style depends on.
	 */
	$style_deps = apply_filters( 'evie_style_deps', $style_deps );

	// Main CSS.
	wp_register_style( 'evie', get_template_directory_uri() . '/style.css', $style_deps, $theme_version );
	wp_style_add_data( 'evie', 'rtl', 'replace' );

	/**
	 * JavaScrips
	 */

	// Colcade.
	wp_register_script( 'colcade', get_theme_file_uri( 'assets/js/vendor/colcade.min.js' ), array(), '0.2.0', true );

	// ScrollMagic.
	wp_register_script( 'scrollmagic', get_theme_file_uri( 'assets/js/vendor/ScrollMagic.min.js' ), array(), '2.0.7', true );

	$script_deps = array( 'colcade', 'scrollmagic' );

	if ( wp_script_is( 'flextension', 'registered' ) ) {
		$style_deps[] = 'flextension';
	}

	/**
	 * Filters an array of registered script handles this script depends on.
	 *
	 * @since 0.2.0
	 *
	 * @param string[] $script_deps An array of registered script handles this script depends on.
	 */
	$script_deps = apply_filters( 'evie_script_deps', $script_deps );

	// Main App.
	wp_register_script( 'evie', get_theme_file_uri( 'assets/js/main.js' ), $script_deps, $theme_version, true );

	// Admin App.
	wp_register_script( 'evie-editor', get_theme_file_uri( 'assets/js/editor.js' ), array( 'wp-data' ), $theme_version, true );
}

add_action( 'init', 'evie_register_assets', 20 );

/**
 * Enqueues styles and scripts.
 */
function evie_enqueue_assets() {
	wp_enqueue_style( 'evie' );

	evie_add_custom_styles( 'evie' );

	$settings = array(
		'ajaxUrl'               => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
		'ajaxNonce'             => wp_create_nonce( 'evie_ajax' ),
		'desktopMenuBreakpoint' => evie_menu_breakpoint(),
	);

	/**
	 * Filters the theme settings.
	 *
	 * @param array $settings The current settings for the theme.
	 */
	$settings = apply_filters( 'evie_settings', $settings );

	wp_localize_script( 'evie', 'evieSettings', $settings );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'evie' );

}

add_action( 'wp_enqueue_scripts', 'evie_enqueue_assets' );

/**
 * Enqueues admin scripts and styles.
 */
function evie_login_enqueue_assets() {
	// Admin CSS.
	wp_enqueue_style( 'evie-login-page', get_theme_file_uri( 'assets/css/login-page.css' ), array(), wp_get_theme()->get( 'Version' ) );
	wp_style_add_data( 'evie-login-page', 'rtl', 'replace' );

	evie_add_custom_styles( 'evie-login-page' );
}

add_action( 'login_enqueue_scripts', 'evie_login_enqueue_assets' );

/**
 * Enqueues block editor scripts and styles.
 */
function evie_enqueue_block_editor_assets() {
	// Block Editor CSS.
	wp_enqueue_style( 'evie-editor', get_theme_file_uri( 'assets/css/block-editor.css' ), array( 'wp-block-editor' ), wp_get_theme()->get( 'Version' ) );
	wp_style_add_data( 'evie-editor', 'rtl', 'replace' );

	// Admin App.
	wp_enqueue_script( 'evie-editor' );

	evie_add_custom_styles( 'evie-editor' );
}

add_action( 'enqueue_block_editor_assets', 'evie_enqueue_block_editor_assets' );

/**
 * Loads the portfolio template functions if the Portfolio module is active.
 */
function evie_load_portfolio_template_functions() {
	if ( function_exists( 'evie_portfolio_post_type_name' ) ) {
		require get_parent_theme_file_path( 'inc/portfolio-template-functions.php' );
		require get_parent_theme_file_path( 'inc/customizer/customizer-portfolio.php' );
	}
}

add_action( 'init', 'evie_load_portfolio_template_functions' );

/**
 * Additional template functions.
 */
require get_parent_theme_file_path( 'inc/template-functions.php' );

/**
 * Loads co-authors template tags if the Co-Authors Plus plugin is active.
 */
if ( function_exists( 'get_coauthors' ) ) {
	require get_parent_theme_file_path( 'inc/co-authors-template-tags.php' );
}

/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( 'inc/template-tags.php' );

/**
 * Template hooks.
 */
require get_parent_theme_file_path( 'inc/template-hooks.php' );

/**
 * WooCommerce template functions and hooks.
 */
if ( class_exists( 'WooCommerce', false ) ) {
	require get_parent_theme_file_path( 'inc/wc-template-functions.php' );
}

/**
 * Breadcrumb Generator.
 */
require get_parent_theme_file_path( 'inc/class-evie-breadcrumb-generator.php' );

/**
 * Theme Customizer.
 */
require get_parent_theme_file_path( 'inc/customizer/customizer.php' );

/**
 * TGM Plugin Activation
 */
require get_parent_theme_file_path( 'inc/vendor/class-tgm-plugin-activation.php' );

/**
 * Registers the recommended plugins for this theme.
 */
function evie_register_plugins() {
	// Recommended plugins.
	$plugins = array(
		array(
			'name'    => esc_html__( 'Flextension', 'evie' ),
			'slug'    => 'flextension',
			'version' => '1.1.2',
			'source'  => 'https://evietheme.com/assets/plugins/flextension.zip',
		),
		array(
			'name'    => esc_html__( 'Evie XT', 'evie' ),
			'slug'    => 'evie-xt',
			'version' => '1.1.2',
			'source'  => 'https://evietheme.com/assets/plugins/evie-xt.zip',
		),
	);

	/**
	 * Filters the recommended plugins for the theme.
	 *
	 * @param array $plugins An array list of the recommended plugins.
	 */
	$plugins = apply_filters( 'evie_plugins', $plugins );

	// Configuration settings.
	$config = array(
		'id'      => 'evie-1.1.1',
		'menu'    => 'install-plugins',
		'strings' => array(
			'page_title' => esc_html__( 'Install Plugins', 'evie' ),
		),
	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'evie_register_plugins' );

/**
 * Note: Do not add any custom code here. Please use a Child theme or a custom plugin so that your customizations aren't lost during updates.
 * https://developer.wordpress.org/themes/advanced-topics/child-themes/
 */
