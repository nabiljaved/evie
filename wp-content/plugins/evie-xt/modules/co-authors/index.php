<?php
/**
 * Co-Authors Module
 *
 * Enables multiple authors for posts and projects.
 * You might be promted to install the Co-Authors Plus plugin after you activate this module.
 *
 * @package    Evie_XT
 * @subpackage Modules/Co_Authors
 * @version    1.0.0
 */

/**
 * Returns whether the Co-Authors Plus plugin is active.
 *
 * @return bool Whether the Co-Authors Plus plugin is active.
 */
function evie_is_co_authors_plugin_active() {
	return class_exists( 'CoAuthors_Plus', false );
}

/**
 * Registers the Co Authors module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'               => esc_html__( 'Co-Authors', 'evie-xt' ),
		'description'         => esc_html__( 'Adds and displays multiple authors for posts and projects.', 'evie-xt' ),
		'category'            => esc_html__( 'Content', 'evie-xt' ),
		'enabled'             => evie_is_co_authors_plugin_active(),
		'actions'             => evie_co_authors_actions(),
		'load_callback'       => 'evie_co_authors_module_load',
		'activate_callback'   => 'evie_co_authors_module_activate',
		'deactivate_callback' => 'evie_co_authors_module_deactivate',
	)
);

/**
 * Loads the Co Authors module.
 */
function evie_co_authors_module_load() {
	add_filter( 'evie_plugins', 'evie_co_authors_add_plugin' );

	if ( evie_is_co_authors_plugin_active() ) {
		add_action( 'pre_get_posts', 'evie_co_authors_pre_get_posts', 20 );
	}
}

/**
 * Parses the taxonomy queries for the co-authors posts.
 *
 * By default, Co-Authors Plus supports only a query with an 'author_name' query var.
 * We need to add support for an 'author__in' to make it work with our block filters.
 *
 * @param WP_Query $query The WP_Query instance.
 */
function evie_co_authors_pre_get_posts( $query ) {
	global $coauthors_plus;

	if ( ! $coauthors_plus ) {
		return;
	}

	$authors = $query->get( 'author__in', array() );
	if ( ! empty( $authors ) ) {
		$terms = array();
		foreach ( $authors as $user_id ) {
			$coauthor = $coauthors_plus->get_coauthor_by( 'user_nicename', get_the_author_meta( 'user_nicename', $user_id ) );
			if ( ! empty( $coauthor ) ) {
				$author_term = $coauthors_plus->get_author_term( $coauthor );
				if ( ! empty( $author_term ) ) {
					$terms[] = $author_term->term_id;
				}
				// If this co-author has a linked account, we also need to get posts with those terms.
				if ( ! empty( $coauthor->linked_account ) ) {
					$linked_account    = get_user_by( 'login', $coauthor->linked_account );
					$guest_author_term = $coauthors_plus->get_author_term( $linked_account );
					if ( ! empty( $guest_author_term ) ) {
						$terms[] = $guest_author_term->term_id;
					}
				}
			}
		}

		if ( ! empty( $terms ) ) {
			$query->set( 'author__in', array() );

			if ( $query->is_main_query() ) {
				$tax_query   = $query->get( 'tax_query', array() );
				$tax_query[] = array(
					'taxonomy' => 'author',
					'field'    => 'term_id',
					'terms'    => $terms,
					'operator' => 'IN',
				);
				$query->set( 'tax_query', $tax_query );
			} else {
				$query->tax_query->queries[] = array(
					'taxonomy'         => 'author',
					'field'            => 'term_id',
					'terms'            => $terms,
					'operator'         => 'IN',
					'include_children' => 1,
				);

				$query->tax_query->queried_terms['author'] = array(
					'field' => 'term_id',
					'terms' => $terms,
				);

				$query->query_vars['tax_query'] = $query->tax_query->queries; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			}
		}
	}
}

/**
 * Returns module actions.
 *
 * @return array An array of the module actions.
 */
function evie_co_authors_actions() {
	$actions = array();

	if ( ! evie_is_co_authors_plugin_active() ) {
		$actions['install'] = sprintf(
			'<a href="%s">%s</a>',
			sprintf( '%s?page=install-plugins', admin_url( flextension_get_admin_page( 'themes' ) ) ),
			esc_html__( 'Install Required Plugins', 'evie-xt' )
		);
	}

	return $actions;
}

/**
 * Adds the Co Authors Plus plugin to the recommended plugins list.
 *
 * @param array $plugins An array list of the recommended plugins.
 * @return array An array list of the recommended plugins.
 */
function evie_co_authors_add_plugin( $plugins = array() ) {
	$plugins[] = array(
		'name' => esc_html__( 'Co-Authors Plus', 'evie-xt' ),
		'slug' => 'co-authors-plus',
	);
	return $plugins;
}

/**
 * Activates required plugins after the module is first activated.
 */
function evie_co_authors_module_activate() {
	if ( isset( $GLOBALS['tgmpa'] ) && isset( $GLOBALS['tgmpa']->plugins['co-authors-plus'] ) ) {
		activate_plugins( $GLOBALS['tgmpa']->plugins['co-authors-plus']['file_path'] );
	}
}

/**
 * Deactivates required plugins after the module is deactivated.
 */
function evie_co_authors_module_deactivate() {
	if ( isset( $GLOBALS['tgmpa'] ) && isset( $GLOBALS['tgmpa']->plugins['co-authors-plus'] ) ) {
		deactivate_plugins( $GLOBALS['tgmpa']->plugins['co-authors-plus']['file_path'] );
	}
}
