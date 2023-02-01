<?php
/**
 * Demo Content
 *
 * @package    Evie_XT
 * @subpackage Modules/Demo_Content
 * @version    1.0.0
 */

/**
 * Returns the list of demo content files to import.
 *
 * @return array An array list of demo content files to import.
 */
function evie_demo_content_menu_setup() {
	return array(
		'parent_slug' => 'themes.php',
		'menu_title'  => esc_html__( 'Demo Content', 'evie-xt' ),
		'page_title'  => esc_html__( 'Import Demo Content', 'evie-xt' ),
		'capability'  => 'import',
		'menu_slug'   => 'evie-demo-content',
	);
}

add_filter( 'ocdi/plugin_page_setup', 'evie_demo_content_menu_setup' );

/**
 * Returns the page header content.
 *
 * @return string The page header content.
 */
function evie_demo_content_page_title() {
	return '<div class="ocdi__title-container">
			<h1 class="ocdi__title-container-title">' . esc_html__( 'Import Demo Content', 'evie-xt' ) . '</h1>
		</div>';
}

add_filter( 'ocdi/plugin_page_title', 'evie_demo_content_page_title' );

/**
 * Returns the page intro text.
 *
 * @return string The page intro text.
 */
function evie_demo_content_intro_text() {
	return '<div class="ocdi__intro-text">
			<p class="about-description">

			</p>
		</div>';
}

add_filter( 'ocdi/plugin_intro_text', 'evie_demo_content_intro_text' );

/**
 * Returns the list of demo content files to import.
 *
 * @return array An array list of demo content files to import.
 */
function evie_demo_content_import_files() {
	/**
	 * Filters the demo content data directory URI.
	 *
	 * @param string $url The demo content data directory URI.
	 */
	$data_dir_url = trailingslashit( apply_filters( 'evie_demo_content_data_dir_url', 'https://evietheme.com/demo-content/' ) );

	return array(
		array(
			'id'                         => 'full-1',
			'categories'                 => array( esc_html__( 'Full Demo', 'evie-xt' ) ),
			'import_file_name'           => esc_html__( 'Creative Agency - Full Demo', 'evie-xt' ),
			'import_file_url'            => $data_dir_url . '1/content.xml',
			'import_widget_file_url'     => $data_dir_url . '1/widgets.json',
			'import_customizer_file_url' => $data_dir_url . '1/customizer.txt',
			'import_settings_file_url'   => $data_dir_url . '1/settings.json',
			'import_preview_image_url'   => $data_dir_url . '1/screenshot.jpg',
			'preview_url'                => 'https://evietheme.com/evie1/',
		),
		array(
			'id'                         => 'full-2',
			'categories'                 => array( esc_html__( 'Full Demo', 'evie-xt' ) ),
			'import_file_name'           => esc_html__( 'Creative Portfolio - Full Demo', 'evie-xt' ),
			'import_file_url'            => $data_dir_url . '2/content.xml',
			'import_widget_file_url'     => $data_dir_url . '2/widgets.json',
			'import_customizer_file_url' => $data_dir_url . '2/customizer.txt',
			'import_settings_file_url'   => $data_dir_url . '2/settings.json',
			'import_preview_image_url'   => $data_dir_url . '2/screenshot.jpg',
			'preview_url'                => 'https://evietheme.com/evie2/',
		),
		array(
			'id'                         => 'full-3',
			'categories'                 => array( esc_html__( 'Full Demo', 'evie-xt' ) ),
			'import_file_name'           => esc_html__( 'Creative Network - Full Demo', 'evie-xt' ),
			'import_file_url'            => $data_dir_url . '3/content.xml',
			'import_widget_file_url'     => $data_dir_url . '3/widgets.json',
			'import_customizer_file_url' => $data_dir_url . '3/customizer.txt',
			'import_settings_file_url'   => $data_dir_url . '3/settings.json',
			'import_preview_image_url'   => $data_dir_url . '3/screenshot.jpg',
			'preview_url'                => 'https://evietheme.com/evie3/',
		),
		array(
			'id'                         => 'full-4',
			'categories'                 => array( esc_html__( 'Full Demo', 'evie-xt' ) ),
			'import_file_name'           => esc_html__( 'Creative Blog - Full Demo', 'evie-xt' ),
			'import_file_url'            => $data_dir_url . '4/content.xml',
			'import_widget_file_url'     => $data_dir_url . '4/widgets.json',
			'import_customizer_file_url' => $data_dir_url . '4/customizer.txt',
			'import_settings_file_url'   => $data_dir_url . '4/settings.json',
			'import_preview_image_url'   => $data_dir_url . '4/screenshot.jpg',
			'preview_url'                => 'https://evietheme.com/evie4/',
		),
		array(
			'id'                         => 'settings-1',
			'categories'                 => array( esc_html__( 'Theme Presets', 'evie-xt' ) ),
			'import_file_name'           => esc_html__( 'Creative Agency - Theme Settings', 'evie-xt' ),
			'import_customizer_file_url' => $data_dir_url . '1/customizer.txt',
			'import_settings_file_url'   => $data_dir_url . '1/settings.json',
			'import_preview_image_url'   => $data_dir_url . '1/screenshot.jpg',
			'preview_url'                => 'https://evietheme.com/evie1/',
		),
		array(
			'id'                         => 'settings-2',
			'categories'                 => array( esc_html__( 'Theme Presets', 'evie-xt' ) ),
			'import_file_name'           => esc_html__( 'Creative Portfolio - Theme Settings', 'evie-xt' ),
			'import_customizer_file_url' => $data_dir_url . '2/customizer.txt',
			'import_settings_file_url'   => $data_dir_url . '2/settings.json',
			'import_preview_image_url'   => $data_dir_url . '2/screenshot.jpg',
			'preview_url'                => 'https://evietheme.com/evie2/',
		),
		array(
			'id'                         => 'settings-3',
			'categories'                 => array( esc_html__( 'Theme Presets', 'evie-xt' ) ),
			'import_file_name'           => esc_html__( 'Creative Network - Theme Settings', 'evie-xt' ),
			'import_customizer_file_url' => $data_dir_url . '3/customizer.txt',
			'import_settings_file_url'   => $data_dir_url . '3/settings.json',
			'import_preview_image_url'   => $data_dir_url . '3/screenshot.jpg',
			'preview_url'                => 'https://evietheme.com/evie3/',
		),
		array(
			'id'                         => 'settings-4',
			'categories'                 => array( esc_html__( 'Theme Presets', 'evie-xt' ) ),
			'import_file_name'           => esc_html__( 'Creative Blog - Theme Settings', 'evie-xt' ),
			'import_customizer_file_url' => $data_dir_url . '4/customizer.txt',
			'import_settings_file_url'   => $data_dir_url . '4/settings.json',
			'import_preview_image_url'   => $data_dir_url . '4/screenshot.jpg',
			'preview_url'                => 'https://evietheme.com/evie4/',
		),
	);
}

add_filter( 'ocdi/import_files', 'evie_demo_content_import_files' );

/**
 * Filters recommended plugins based on which demo is selected by the user.
 *
 * @param array $plugins An array of the plugins.
 * @return array An array of the plugins.
 */
function evie_demo_content_register_plugins( $plugins ) {
	// Check if user is on the theme recommeneded plugins step and a demo was selected.
	$step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( 'import' === $step ) {
		$demo = isset( $_GET['import'] ) ? sanitize_key( $_GET['import'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( '' !== $demo && absint( $demo ) < 4 ) {
			if ( '0' === $demo ) {
				$plugins[] = array(
					'name' => esc_html__( 'WooCommerce', 'evie-xt' ),
					'slug' => 'woocommerce',
				);
			}

			if ( in_array( $demo, array( '0', '2', '3' ), true ) ) {
				$plugins[] = array(
					'name' => esc_html__( 'Co-Authors Plus', 'evie-xt' ),
					'slug' => 'co-authors-plus',
				);
			}

			$plugins[] = array(
				'name' => esc_html__( 'Contact Form 7', 'evie-xt' ),
				'slug' => 'contact-form-7',
			);

			$plugins[] = array(
				'name' => esc_html__( 'Icon Block', 'evie-xt' ),
				'slug' => 'icon-block',
			);
		}
	}

	return $plugins;
}

add_filter( 'ocdi/register_plugins', 'evie_demo_content_register_plugins' );

/**
 * Imports WordPress site settings.
 *
 * @param string $url A settings file URL.
 */
function evie_demo_content_import_settings( $url = '' ) {
	if ( ! empty( $url ) ) {
		$response = wp_safe_remote_get( $url );
		$body     = wp_remote_retrieve_body( $response );
		if ( ! empty( $body ) ) {
			$settings = json_decode( $body, true );
			if ( ! empty( $settings ) ) {
				foreach ( $settings as $name => $value ) {
					if ( ! empty( $name ) ) {
						update_option( $name, $value );
					}
				}
			}
		}
	}
}

/**
 * Assigns Front page, Posts page and menu locations after the importer is done.
 *
 * @param array $selected_item The selected demo.
 */
function evie_demo_content_after_import( $selected_item = array() ) {
	if ( isset( $selected_item['id'] ) ) {

		if ( in_array( $selected_item['id'], array( 'full-1', 'full-2', 'full-3', 'full-4' ), true ) ) {
			// Assign the Front page.
			update_option( 'show_on_front', 'page' );

			$front_page = get_page_by_title( 'Home' );
			if ( null !== $front_page ) {
				update_option( 'page_on_front', $front_page->ID );
			}
		}

		$menu_locations = array();

		if ( 'full-1' === $selected_item['id'] ) {

			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
			if ( false !== $main_menu ) {
				$menu_locations['primary'] = $main_menu->term_id;
				$menu_locations['mobile']  = $main_menu->term_id;
			}

			$footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
			if ( false !== $footer_menu ) {
				$menu_locations['footer'] = $footer_menu->term_id;
			}

			// Assign the Posts page.
			$blog_page = get_page_by_title( 'Blog' );
			if ( null !== $blog_page ) {
				update_option( 'page_for_posts', $blog_page->ID );
			}

			// Assign the Portfolio page.
			$portfolio_page = get_page_by_title( 'Work' );
			if ( null !== $portfolio_page ) {
				update_option(
					'evie_portfolio',
					array(
						'portfolio_page' => $portfolio_page->ID,
						'comments'       => false,
					)
				);
			}
		} elseif ( 'full-2' === $selected_item['id'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
			if ( false !== $main_menu ) {
				$menu_locations['primary'] = $main_menu->term_id;
				$menu_locations['mobile']  = $main_menu->term_id;
			}

			// Assign the Posts page.
			$blog_page = get_page_by_title( 'Blog' );
			if ( null !== $blog_page ) {
				update_option( 'page_for_posts', $blog_page->ID );
			}

			// Assign the Portfolio page.
			$portfolio_page = get_page_by_title( 'Featured Projects' );
			if ( null !== $portfolio_page ) {
				update_option(
					'evie_portfolio',
					array(
						'portfolio_page' => $portfolio_page->ID,
						'comments'       => false,
					)
				);
			}
		} elseif ( 'full-3' === $selected_item['id'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
			if ( false !== $main_menu ) {
				$menu_locations['primary'] = $main_menu->term_id;
				$menu_locations['mobile']  = $main_menu->term_id;
			}

			// Assign the Posts page.
			$blog_page = get_page_by_title( 'Trends, Tips & More' );
			if ( null !== $blog_page ) {
				update_option( 'page_for_posts', $blog_page->ID );
			}

			// Assign the Portfolio page.
			$portfolio_page = get_page_by_title( 'Creative Projects' );
			if ( null !== $portfolio_page ) {
				update_option(
					'evie_portfolio',
					array(
						'portfolio_page' => $portfolio_page->ID,
						'comments'       => false,
					)
				);
			}
		} elseif ( 'full-4' === $selected_item['id'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
			if ( false !== $main_menu ) {
				$menu_locations['primary'] = $main_menu->term_id;
				$menu_locations['mobile']  = $main_menu->term_id;
			}

			$footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
			if ( false !== $footer_menu ) {
				$menu_locations['footer'] = $footer_menu->term_id;
			}

			// Assign the Posts page.
			$blog_page = get_page_by_title( 'Trends, Tips & More' );
			if ( null !== $blog_page ) {
				update_option( 'page_for_posts', $blog_page->ID );
			}
		}

		// Assign menus to their locations.
		if ( ! empty( $menu_locations ) ) {
			set_theme_mod( 'nav_menu_locations', $menu_locations );
		}

		// Import settings.
		if ( isset( $selected_item['import_settings_file_url'] ) ) {
			evie_demo_content_import_settings( $selected_item['import_settings_file_url'] );
		}
	}
}

add_action( 'ocdi/after_import', 'evie_demo_content_after_import' );

/**
 * Enqueues the scripts and stylesheets.
 */
function evie_demo_content_admin_scripts() {
	global $pagenow;

	$page_args = evie_demo_content_menu_setup();

	if ( $page_args['parent_slug'] === $pagenow && isset( $_GET['page'] ) && sanitize_title( wp_unslash( $_GET['page'] ) ) === $page_args['menu_slug'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		wp_enqueue_style( 'evie-demo-content', plugins_url( 'css/style.css', __FILE__ ), array(), EVIE_XT_VERSION );
	}
}

add_action( 'admin_enqueue_scripts', 'evie_demo_content_admin_scripts' );
