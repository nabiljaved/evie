<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * Removes all options from the plugin and all modules.
 *
 * @since 1.1.3
 *
 * @package Evie_XT
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Removes the plugin data and settings.
 */
function evie_uninstall() {
	global $wpdb;

	/*
	* Only remove the plugin data if EVIE_REMOVE_ALL_DATA constant is set to true in user's
	* wp-config.php. This is to prevent data loss when deleting the plugin from the backend
	* and to ensure only the site owner can perform this action.
	*/
	if ( defined( 'EVIE_REMOVE_ALL_DATA' ) && true === EVIE_REMOVE_ALL_DATA ) {

		// Delete options.
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'evie\_%';" );

		// Delete user meta.
		$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'evie\_%';" );

		// Delete term meta.
		$wpdb->query( "DELETE FROM $wpdb->termmeta WHERE meta_key LIKE 'evie\_%';" );

		// Delete our data from the post and post meta tables.
		$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ( 'project' );" );
		$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );

		// Delete term taxonomies.
		/** This filter is documented in modules/portfolio/evie-portfolio.php */
		$taxonomies = apply_filters( 'evie_portfolio_attribute_taxonomies', array() );
		$taxonomies = array_merge( array_keys( $taxonomies ), array( 'project_category', 'project_attribute', 'project_tag' ) );
		foreach ( $taxonomies as $taxonomy ) {
			$wpdb->delete(
				$wpdb->term_taxonomy,
				array(
					'taxonomy' => $taxonomy,
				)
			);
		}

		// Delete orphan relationships.
		$wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );

		// Delete orphan terms.
		$wpdb->query( "DELETE t FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.term_id IS NULL;" );

		// Delete orphan term meta.
		if ( ! empty( $wpdb->termmeta ) ) {
			$wpdb->query( "DELETE tm FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->term_taxonomy} tt ON tm.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
		}

		// Clear any cached data that has been removed.
		wp_cache_flush();
	}
}

evie_uninstall();
