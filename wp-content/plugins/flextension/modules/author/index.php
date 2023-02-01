<?php
/**
 * Author Module
 *
 * @package    Flextension
 * @subpackage Modules/Author
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Author module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Advanced Author', 'flextension' ),
		'description'   => esc_html__( 'Allows you to follow authors and see their posts in your feed, displays number of followers in the Author block and widget.', 'flextension' ),
		'category'      => esc_html__( 'Widget', 'flextension' ),
		'enabled'       => true,
		'dependencies'  => array( 'lightbox' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( 'author' ),
				esc_html__( 'Settings', 'flextension' )
			),
		),
		'load_callback' => 'flextension_author_module_load',
	)
);

/**
 * Loads the Author module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_author_module_load( $module ) {
	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-author.php';
	require_once plugin_dir_path( __FILE__ ) . 'flextension-author-follow.php';

	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-flextension-followers-api.php';
	new Flextension_Followers_API();

	// Registers all widgets in the folder 'widgets'.
	flextension_load_files( 'widgets/*.php', plugin_dir_path( __FILE__ ) );

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-author-admin.php';
		new Flextension_Author_Admin( $module->name );

		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-author-cover-image.php';
		new Flextension_Author_Cover_Image();
	}
}
