<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * Removes all options from the plugin and all modules.
 *
 * @since 1.1.2
 *
 * @package Flextension
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Removes the plugin data and settings.
 */
function flextension_uninstall() {
	global $wpdb;

	/*
	* Only remove the plugin data if FLEXTENSION_REMOVE_ALL_DATA constant is set to true in user's
	* wp-config.php. This is to prevent data loss when deleting the plugin from the backend
	* and to ensure only the site owner can perform this action.
	*/
	if ( defined( 'FLEXTENSION_REMOVE_ALL_DATA' ) && true === FLEXTENSION_REMOVE_ALL_DATA ) {

		// Delete options.
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'flext\_%';" );
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'widget\_flext\_%';" );

		// Delete user meta.
		$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'flext\_%';" );

		// Delete post meta.
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key LIKE 'flext\_%';" );

		// Delete term meta.
		$wpdb->query( "DELETE FROM $wpdb->termmeta WHERE meta_key LIKE 'flext\_%';" );

		// Clear any cached data that has been removed.
		wp_cache_flush();
	}
}

flextension_uninstall();
