<?php
/**
 * Portfolio
 *
 * @package    Evie_XT
 * @subpackage Modules/Portfolio
 * @version    1.0.0
 */

/**
 * Returns the name of portfolio post type
 *
 * @return string The name of portfolio post type.
 */
function evie_portfolio_post_type_name() {
	/**
	 * Filters the name of portfolio post type.
	 *
	 * @param string $name The name of portfolio post type.
	 */
	return apply_filters( 'evie_portfolio_post_type', 'project' );
}

/**
 * Returns the settings values of the Live Search module.
 *
 * @return array An array object of the settings.
 */
function evie_portfolio_settings() {
	return wp_parse_args(
		get_option( 'evie_portfolio', array() ),
		array(
			'portfolio_page' => 0,
			'comments'       => false,
		)
	);
}

/**
 * Retrieves the permalink settings for the portfolio post type and taxonomies.
 *
 * @return array An array list of the permalink settings.
 */
function evie_portfolio_permalink_structure() {

	$permalink_settings = get_option( 'evie_portfolio_permalinks', array() );

	$defaults = array(
		'project_base'           => 'project',
		'category_base'          => 'project-category',
		'attribute_base'         => 'project-attribute',
		'tag_base'               => 'project-tag',
		'use_verbose_page_rules' => false,
	);

	$attributes = evie_portfolio_attribute_taxonomies();
	if ( ! empty( $attributes ) ) {
		foreach ( $attributes as $name => $labels ) {
			$defaults[ "{$name}_base" ] = "project-{$name}";
		}
	}

	$permalinks = wp_parse_args( array_filter( $permalink_settings ), $defaults );

	return $permalinks;
}

/**
 * Retrieves the portfolio archive page ID.
 *
 * @return string The ID of the portfolio archive page.
 */
function evie_portfolio_page() {
	$settings = evie_portfolio_settings();

	$page_id = $settings['portfolio_page'];
	/**
	 * Filters the portfolio page.
	 *
	 * @param int $page_id The ID of the portfolio page.
	 */
	$page_id = apply_filters( 'evie_portfolio_page', $page_id );

	return absint( $page_id );
}

/**
 * Returns whether the page is the portfolio archive page.
 *
 * @param string $page_id The page ID.
 * @return bool Whether the page is the portfolio archive page.
 */
function evie_is_portfolio_page( $page_id = '' ) {

	if ( empty( $page_id ) ) {
		return is_post_type_archive( evie_portfolio_post_type_name() );
	} else {
		$portfolio_page_id = evie_portfolio_page();
		if ( $portfolio_page_id ) {
			return absint( $page_id ) === $portfolio_page_id;
		}
	}

	return false;
}

/**
 * Recursively get page children.
 *
 * @param  int $page_id Page ID.
 * @return int[]
 */
function evie_portfolio_page_children( $page_id ) {
	$page_ids = get_posts(
		array(
			'post_parent' => $page_id,
			'post_type'   => 'page',
			'numberposts' => -1,
			'post_status' => 'any',
			'fields'      => 'ids',
		)
	);

	if ( ! empty( $page_ids ) ) {
		foreach ( $page_ids as $page_id ) {
			$page_ids = array_merge( $page_ids, evie_portfolio_page_children( $page_id ) );
		}
	}

	return $page_ids;
}

/**
 * Retrieves portfolio attribute taxonomies.
 *
 * @return array An array of portfolio attribute taxonomies.
 */
function evie_portfolio_attribute_taxonomies() {

	$taxonomies = array();

	/**
	 * Filters the portfolio attribute taxonomies.
	 *
	 * @param array $taxonomies Array of the portfolio attribute taxonomies.
	 */
	return apply_filters( 'evie_portfolio_attribute_taxonomies', $taxonomies );
}

/**
 * Returns whether the current user can edit projects.
 *
 * @return bool Whether the current user can edit projects.
 */
function evie_portfolio_can_edit_projects() {
	return current_user_can( 'edit_posts' );
}

/**
 * Registers the portfolio post type and taxonomies.
 */
function evie_portfolio_register_post_type() {

	$post_type = evie_portfolio_post_type_name();

	if ( ! is_blog_installed() || post_type_exists( $post_type ) ) {
		return;
	}

	$settings = evie_portfolio_settings();

	$permalinks = evie_portfolio_permalink_structure();

	$supports = array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'custom-fields' );

	if ( true === $settings['comments'] ) {
		$supports[] = 'comments';
	}

	$portfolio_page = evie_portfolio_page();

	$has_archive = $portfolio_page && get_post( $portfolio_page ) ? urldecode( get_page_uri( $portfolio_page ) ) : 'portfolio';

	/**
	 * Filters the arguments for registering the portfolio post type.
	 *
	 * @param array|string $args Array or string of arguments for registering the portfolio post type.
	 */
	$portfolio_args = apply_filters(
		'evie_portfolio_post_type_args',
		array(
			'description'        => __( 'Adds projects to your website.', 'evie-xt' ),
			'has_archive'        => $has_archive,
			'labels'             => array(
				'name'                  => __( 'Projects', 'evie-xt' ),
				'singular_name'         => __( 'Project', 'evie-xt' ),
				'all_items'             => __( 'All Projects', 'evie-xt' ),
				'menu_name'             => _x( 'Projects', 'Admin menu name', 'evie-xt' ),
				'add_new'               => __( 'Add New', 'evie-xt' ),
				'add_new_item'          => __( 'Add New Project', 'evie-xt' ),
				'edit'                  => __( 'Edit', 'evie-xt' ),
				'edit_item'             => __( 'Edit Project', 'evie-xt' ),
				'new_item'              => __( 'New Project', 'evie-xt' ),
				'view_item'             => __( 'View Project', 'evie-xt' ),
				'view_items'            => __( 'View Projects', 'evie-xt' ),
				'search_items'          => __( 'Search Projects', 'evie-xt' ),
				'not_found'             => __( 'No projects found', 'evie-xt' ),
				'not_found_in_trash'    => __( 'No projects found in trash', 'evie-xt' ),
				'parent'                => __( 'Parent Project', 'evie-xt' ),
				'insert_into_item'      => __( 'Insert into Project', 'evie-xt' ),
				'uploaded_to_this_item' => __( 'Uploaded to this project', 'evie-xt' ),
				'filter_items_list'     => __( 'Filter projects', 'evie-xt' ),
				'items_list_navigation' => __( 'Projects navigation', 'evie-xt' ),
				'items_list'            => __( 'Projects list', 'evie-xt' ),
			),
			'menu_icon'          => 'dashicons-portfolio',
			'public'             => true,
			'publicly_queryable' => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => $permalinks['project_base'],
				'with_front' => false,
				'feeds'      => true,
			),
			'show_in_nav_menus'  => true,
			'show_in_rest'       => true,
			'show_ui'            => true,
			'supports'           => $supports,
		)
	);

	register_post_type( $post_type, $portfolio_args );

	/**
	 * Filters the arguments for registering the portfolio category.
	 *
	 * @param array|string $args Array or string of arguments for registering the portfolio category.
	 */
	$category_args = apply_filters(
		'evie_portfolio_category_args',
		array(
			'hierarchical' => true,
			'label'        => __( 'Categories', 'evie-xt' ),
			'labels'       => array(
				'name'                       => __( 'Project Categories', 'evie-xt' ),
				'singular_name'              => __( 'Category', 'evie-xt' ),
				'menu_name'                  => _x( 'Categories', 'Admin menu name', 'evie-xt' ),
				'search_items'               => __( 'Search Categories', 'evie-xt' ),
				'popular_items'              => __( 'Popular Categories', 'evie-xt' ),
				'all_items'                  => __( 'All Categories', 'evie-xt' ),
				'parent_item'                => __( 'Parent Category', 'evie-xt' ),
				'parent_item_colon'          => __( 'Parent Category:', 'evie-xt' ),
				'edit_item'                  => __( 'Edit Category', 'evie-xt' ),
				'view_item'                  => __( 'View Category', 'evie-xt' ),
				'update_item'                => __( 'Update Category', 'evie-xt' ),
				'add_new_item'               => __( 'Add New Category', 'evie-xt' ),
				'new_item_name'              => __( 'New Category Name', 'evie-xt' ),
				'separate_items_with_commas' => __( 'Separate with commas or the Enter key.', 'evie-xt' ),
				'add_or_remove_items'        => __( 'Add or remove tags', 'evie-xt' ),
				'choose_from_most_used'      => __( 'Choose from the most used tags', 'evie-xt' ),
				'not_found'                  => __( 'No categories found', 'evie-xt' ),
				'back_to_items'              => __( '&larr; Back to categories', 'evie-xt' ),
			),
			'query_var'    => true,
			'rewrite'      => array(
				'slug'         => $permalinks['category_base'],
				'with_front'   => false,
				'hierarchical' => true,
			),
			'show_in_rest' => true,
			'show_ui'      => true,
		)
	);

	register_taxonomy( 'project_category', $post_type, $category_args );

	$attribute_taxonomies = evie_portfolio_attribute_taxonomies();
	if ( ! empty( $attribute_taxonomies ) ) {

		foreach ( $attribute_taxonomies as $name => $labels ) {

			if ( ! empty( $name ) ) {

				$label         = $labels['name'];
				$singular_name = $labels['singular_name'];

				/**
				 * Filters the arguments for registering the custom portfolio taxonomy.
				 *
				 * @param array|string $args Array or string of arguments for registering the custom portfolio taxonomy.
				 */
				$taxonomy_args = apply_filters(
					"evie_portfolio_{$name}_args",
					array(
						'hierarchical' => true,
						'label'        => $labels['name'],
						'labels'       => array(
							/* translators: %s: attribute name */
							'name'                       => sprintf( __( 'Project %s', 'evie-xt' ), $label ),
							'singular_name'              => $singular_name,
							'menu_name'                  => $label,
							/* translators: %s: attribute name */
							'search_items'               => sprintf( __( 'Search %s', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'popular_items'              => sprintf( __( 'Popular %s', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'all_items'                  => sprintf( __( 'All %s', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'parent_item'                => sprintf( __( 'Parent %s', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'parent_item_colon'          => sprintf( __( 'Parent %s:', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'edit_item'                  => sprintf( __( 'Edit %s', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'view_item'                  => sprintf( __( 'View %s', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'update_item'                => sprintf( __( 'Update %s', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'add_new_item'               => sprintf( __( 'Add New %s', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'new_item_name'              => sprintf( __( 'New %s', 'evie-xt' ), $label ),
							'separate_items_with_commas' => __( 'Separate with commas or the Enter key.', 'evie-xt' ),
							/* translators: %s: attribute name */
							'add_or_remove_items'        => sprintf( __( 'Add or remove "%s"', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'choose_from_most_used'      => sprintf( __( 'Choose from the most used "%s"', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'not_found'                  => sprintf( __( 'No "%s" found', 'evie-xt' ), $label ),
							/* translators: %s: attribute name */
							'back_to_items'              => sprintf( __( '&larr; Back to "%s"', 'evie-xt' ), $label ),
						),
						'query_var'    => true,
						'rewrite'      => array(
							'slug'         => $permalinks[ "{$name}_base" ],
							'with_front'   => false,
							'hierarchical' => true,
						),
						'show_in_rest' => true,
						'show_ui'      => true,
					)
				);

				register_taxonomy( "project_{$name}", $post_type, $taxonomy_args );
			}
		}
	}

	/**
	 * Filters the arguments for registering the portfolio attribute.
	 *
	 * @param array|string $args Array or string of arguments for registering the portfolio attribute.
	 */
	$attribute_args = apply_filters(
		'evie_portfolio_attribute_args',
		array(
			'label'        => __( 'Project Attributes', 'evie-xt' ),
			'labels'       => array(
				'name'                       => __( 'Project Attributes', 'evie-xt' ),
				'singular_name'              => __( 'Attribute', 'evie-xt' ),
				'menu_name'                  => _x( 'Attributes', 'Admin menu name', 'evie-xt' ),
				'search_items'               => __( 'Search Attributes', 'evie-xt' ),
				'all_items'                  => __( 'All Attributes', 'evie-xt' ),
				'edit_item'                  => __( 'Edit Attribute', 'evie-xt' ),
				'update_item'                => __( 'Update Attribute', 'evie-xt' ),
				'add_new_item'               => __( 'Add New Attribute', 'evie-xt' ),
				'new_item_name'              => __( 'New Attribute Name', 'evie-xt' ),
				'separate_items_with_commas' => __( 'Separate with commas or the Enter key.', 'evie-xt' ),
				'not_found'                  => __( 'No tags found', 'evie-xt' ),
				'back_to_items'              => __( '&larr; Back to tags', 'evie-xt' ),
			),
			'query_var'    => true,
			'rewrite'      => array(
				'slug'       => $permalinks['attribute_base'],
				'with_front' => false,
			),
			'show_in_rest' => true,
			'show_ui'      => true,
		)
	);

	register_taxonomy( 'project_attribute', $post_type, $attribute_args );

	/**
	 * Filters the arguments for registering the portfolio tag.
	 *
	 * @param array|string $args Array or string of arguments for registering the portfolio tag.
	 */
	$tag_args = apply_filters(
		'evie_portfolio_tag_args',
		array(
			'label'        => __( 'Project Tags', 'evie-xt' ),
			'labels'       => array(
				'name'                       => __( 'Project Tags', 'evie-xt' ),
				'singular_name'              => __( 'Tag', 'evie-xt' ),
				'menu_name'                  => _x( 'Tags', 'Admin menu name', 'evie-xt' ),
				'search_items'               => __( 'Search Tags', 'evie-xt' ),
				'all_items'                  => __( 'All Tags', 'evie-xt' ),
				'edit_item'                  => __( 'Edit Tag', 'evie-xt' ),
				'update_item'                => __( 'Update Tag', 'evie-xt' ),
				'add_new_item'               => __( 'Add New Tag', 'evie-xt' ),
				'new_item_name'              => __( 'New Tag Name', 'evie-xt' ),
				'separate_items_with_commas' => __( 'Separate with commas or the Enter key.', 'evie-xt' ),
				'not_found'                  => __( 'No tags found', 'evie-xt' ),
				'back_to_items'              => __( '&larr; Back to tags', 'evie-xt' ),
			),
			'query_var'    => true,
			'rewrite'      => array(
				'slug'       => $permalinks['tag_base'],
				'with_front' => false,
			),
			'show_in_rest' => true,
			'show_ui'      => true,
		)
	);

	register_taxonomy( 'project_tag', $post_type, $tag_args );

	/**
	 * Register metadata for the project.
	 */
	$prefix = '_evie_';

	register_meta(
		'post',
		$prefix . 'layout',
		array(
			'object_subtype'    => $post_type,
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_key',
			'auth_callback'     => 'evie_portfolio_can_edit_projects',
		)
	);

	register_meta(
		'post',
		$prefix . 'project_url',
		array(
			'object_subtype'    => $post_type,
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'esc_url_raw',
			'auth_callback'     => 'evie_portfolio_can_edit_projects',
		)
	);

	register_meta(
		'post',
		$prefix . 'project_clients',
		array(
			'object_subtype' => $post_type,
			'type'           => 'array',
			'single'         => true,
			'show_in_rest'   => array(
				'schema' => array(
					'items' => array(
						'type'       => 'object',
						'properties' => array(
							'name'    => array(
								'type' => 'string',
							),
							'website' => array(
								'type'   => 'string',
								'format' => 'uri',
							),
						),
					),
				),
			),
			'auth_callback'  => 'evie_portfolio_can_edit_projects',
		)
	);

	register_meta(
		'post',
		$prefix . 'project_scheme',
		array(
			'object_subtype'    => $post_type,
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_key',
			'auth_callback'     => 'evie_portfolio_can_edit_projects',
		)
	);

	register_meta(
		'post',
		$prefix . 'project_color',
		array(
			'object_subtype'    => $post_type,
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_hex_color',
			'auth_callback'     => 'evie_portfolio_can_edit_projects',
		)
	);

	register_meta(
		'post',
		$prefix . 'project_fields',
		array(
			'object_subtype' => $post_type,
			'type'           => 'array',
			'single'         => true,
			'show_in_rest'   => array(
				'schema' => array(
					'items' => array(
						'type'       => 'object',
						'properties' => array(
							'title'   => array(
								'type' => 'string',
							),
							'content' => array(
								'type' => 'string',
							),
						),
					),
				),
			),
			'auth_callback'  => 'evie_portfolio_can_edit_projects',
		)
	);

	register_meta(
		'post',
		$prefix . 'project_attributes',
		array(
			'object_subtype' => $post_type,
			'type'           => 'array',
			'single'         => true,
			'show_in_rest'   => array(
				'schema' => array(
					'items' => array(
						'type'       => 'object',
						'properties' => array(
							'title' => array(
								'type' => 'string',
							),
							'terms' => array(
								'type'  => 'array',
								'items' => array(
									'type' => 'number',
								),
							),
						),
					),
				),
			),
			'auth_callback'  => 'evie_portfolio_can_edit_projects',
		)
	);

	register_meta(
		'post',
		$prefix . 'project_modified',
		array(
			'object_subtype'    => $post_type,
			'type'              => 'number',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => 'evie_portfolio_can_edit_projects',
		)
	);

}

add_action( 'init', 'evie_portfolio_register_post_type', 5 );

/**
 * Handles redirects before determining which template to load.
 */
function evie_portfolio_template_redirect() {
	// When default permalinks are enabled, redirect portfolio page to post type archive url.
	if ( '' === get_option( 'permalink_structure' ) && get_query_var( 'page_id' ) && ! is_post_type_archive( evie_portfolio_post_type_name() ) ) {
		if ( evie_is_portfolio_page( get_query_var( 'page_id' ) ) ) {
			$portfolio_archive_link = get_post_type_archive_link( evie_portfolio_post_type_name() );
			if ( $portfolio_archive_link ) {
				wp_safe_redirect( $portfolio_archive_link );
				exit;
			}
		}
	}
}

add_action( 'template_redirect', 'evie_portfolio_template_redirect' );

/**
 * Adds Author's Projects page to display all projects from each author.
 */
function evie_portfolio_add_rewrite_rules() {
	$portfolio_page = evie_portfolio_page();
	if ( ! empty( $portfolio_page ) ) {
		add_rewrite_rule( '^' . get_page_uri( $portfolio_page ) . '/author/?(.+)/?$', 'index.php?post_type=' . evie_portfolio_post_type_name() . '&author_name=$matches[1]', 'top' );
	}
}

add_action( 'init', 'evie_portfolio_add_rewrite_rules' );

/**
 * Sets the URL to the author's page based on the post type.
 *
 * @global WP_Rewrite $wp_rewrite WordPress rewrite component.
 *
 * @param string $link The URL to the author's page.
 * @return string The URL to the author's page.
 */
function evie_portfolio_author_link( $link ) {

	$post_type = evie_portfolio_post_type_name();

	if ( get_post_type() === $post_type || in_array( $post_type, (array) get_query_var( 'post_type' ), true ) ) {

		global $wp_rewrite;

		$permalink = $wp_rewrite->get_author_permastruct();

		if ( ! empty( $permalink ) ) {
			$link = str_replace( home_url( '/' ), get_post_type_archive_link( $post_type ), $link );
		}

		$link = add_query_arg( 'post_type', $post_type, $link );
	}

	return $link;
}

add_filter( 'author_link', 'evie_portfolio_author_link', 50 );

/**
 * Update WordPress rewrite rules.
 *
 * @param array $rules Array of rewrite rules.
 * @return array Array of rewrite rules.
 */
function evie_portfolio_update_rewrite_rules( $rules ) {
	global $wp_rewrite;

	$permalinks = evie_portfolio_permalink_structure();

	// Fix the rewrite rules when the project permalink have %project_category% flag.
	if ( preg_match( '`/(.+)(/%project_category%)`', $permalinks['project_base'], $matches ) ) {
		foreach ( $rules as $rule => $rewrite ) {
			if ( preg_match( '`^' . preg_quote( $matches[1], '`' ) . '/\(`', $rule ) && preg_match( '/^(index\.php\?project_category)(?!(.*project))/', $rewrite ) ) {
				unset( $rules[ $rule ] );
			}
		}
	}

	// If the portfolio page is used as the base, we need to handle portfolio subpages to avoid 404 errors.
	if ( false === $permalinks['use_verbose_page_rules'] ) {
		return $rules;
	}

	$portfolio_page = evie_portfolio_page();
	if ( $portfolio_page ) {
		$page_rewrite_rules = array();
		$subpages           = evie_portfolio_page_children( $portfolio_page );
		// Subpage rules.
		foreach ( $subpages as $subpage ) {
			$uri                                = get_page_uri( $subpage );
			$page_rewrite_rules[ $uri . '/?$' ] = 'index.php?pagename=' . $uri;
			$wp_generated_rewrite_rules         = $wp_rewrite->generate_rewrite_rules( $uri, EP_PAGES, true, true, false, false );
			foreach ( $wp_generated_rewrite_rules as $key => $value ) {
				$wp_generated_rewrite_rules[ $key ] = $value . '&pagename=' . $uri;
			}
			$page_rewrite_rules = array_merge( $page_rewrite_rules, $wp_generated_rewrite_rules );
		}

		// Merge with rules.
		$rules = array_merge( $page_rewrite_rules, $rules );
	}

	return $rules;
}

add_filter( 'rewrite_rules_array', 'evie_portfolio_update_rewrite_rules' );

/**
 * Replaces %project_category% in the permalink with the project category slug.
 *
 * @param  string  $permalink The project permalink.
 * @param  WP_Post $post      WP_Post object.
 * @return string The permalink of the project.
 */
function evie_portfolio_update_permalink( $permalink, $post ) {
	// Abort if post is not a project.
	if ( evie_portfolio_post_type_name() !== $post->post_type ) {
		return $permalink;
	}

	// Abort early if the placeholder rewrite tag isn't in the generated URL.
	if ( false === strpos( $permalink, '%' ) ) {
		return $permalink;
	}

	// Get the custom taxonomy terms in use by this post.
	$terms = get_the_terms( $post->ID, 'project_category' );

	if ( ! empty( $terms ) ) {
		$terms = wp_list_sort(
			$terms,
			array(
				'parent'  => 'DESC',
				'term_id' => 'ASC',
			)
		);

		$category_object = $terms[0];
		$category_slug   = $category_object->slug;

		/**
		 * Filters whether the parent category must be excluded from the link.
		 *
		 * @param bool $exclude_parent Whether the portfolio link must have only parent category.
		 */
		$exclude_parent = apply_filters( 'evie_portfolio_link_exclude_parent_category', false );
		if ( ! $exclude_parent && $category_object->parent ) {
			$ancestors = get_ancestors( $category_object->term_id, 'project_category' );
			foreach ( $ancestors as $ancestor ) {
				$ancestor_object = get_term( $ancestor, 'project_category' );
				/**
				 * Filters whether the portfolio link must have only parent category.
				 *
				 * @param bool $parent_only Whether the portfolio link must have only parent category.
				 */
				$parent_only = apply_filters( 'evie_portfolio_link_parent_category_only', false );
				if ( $parent_only ) {
					$category_slug = $ancestor_object->slug;
				} else {
					$category_slug = $ancestor_object->slug . '/' . $category_slug;
				}
			}
		}
	} else {
		// If no terms are assigned to this post, use a 'string' instead (can't leave the placeholder there).
		$category_slug = _x( 'uncategorized', 'slug', 'evie-xt' );
	}

	$permalink = str_replace( '%project_category%', $category_slug, $permalink );

	return $permalink;
}

add_filter( 'post_type_link', 'evie_portfolio_update_permalink', 10, 2 );

/**
 * Sets the post type archive title for the portfolio.
 *
 * @param string $title     The title for archive page.
 * @param string $post_type Post type.
 */
function evie_portfolio_archive_title( $title = '', $post_type = '' ) {
	// If the Portfolio page has been set, return the Portfolio page title instead of the post type label.
	if ( evie_portfolio_post_type_name() === $post_type && evie_portfolio_page() > 0 ) {

		$portfolio_page = get_post( evie_portfolio_page() );

		if ( null !== $portfolio_page && ! empty( $portfolio_page->post_title ) ) {
			$title = $portfolio_page->post_title;
		}
	}

	return $title;
}

add_filter( 'post_type_archive_title', 'evie_portfolio_archive_title', 10, 2 );

/**
 * Sets the main query for the portfolio archive page.
 *
 * @param WP_Query $query The WP_Query instance.
 */
function evie_portfolio_pre_get_posts( $query ) {

	// Only want to affect the main query.
	if ( ! $query->is_main_query() ) {
		return;
	}

	$post_type = evie_portfolio_post_type_name();

	$portfolio_page_id = evie_portfolio_page();

	if ( $portfolio_page_id > 0 && $query->is_page() && absint( $query->get( 'page_id' ) ) === $portfolio_page_id ) {
		$query->set( 'post_type', $post_type );
		$query->set( 'page_id', '' );

		$portfolio_page = get_post( $portfolio_page_id );
		if ( null !== $portfolio_page ) {
			// Get the actual WP page to avoid errors and let us use is_front_page().
			// This is hacky but works. Awaiting https://core.trac.wordpress.org/ticket/21096.
			global $wp_post_types;

			$wp_post_types[ $post_type ]->ID         = $portfolio_page->ID;
			$wp_post_types[ $post_type ]->post_title = $portfolio_page->post_title;
			$wp_post_types[ $post_type ]->post_name  = $portfolio_page->post_name;
			$wp_post_types[ $post_type ]->post_type  = $portfolio_page->post_type;
			$wp_post_types[ $post_type ]->ancestors  = get_ancestors( $portfolio_page->ID, $portfolio_page->post_type );
		}

		// Set conditional Functions like is_front_page.
		$query->is_singular          = false;
		$query->is_post_type_archive = true;
		$query->is_archive           = true;
		$query->is_page              = true;
	}

	if ( $query->get( 'post_type' ) === $post_type && $query->is_author() ) {
		$query->is_post_type_archive = false;
		$query->is_category          = false;
		$query->is_tag               = false;
		$query->is_tax               = false;
		$query->is_home              = false;
	}
}

add_action( 'pre_get_posts', 'evie_portfolio_pre_get_posts' );

/**
 * Sets the menu item active class for the portfolio page.
 *
 * @param array $menu_items Menu items.
 * @return array Menu items.
 */
function evie_portfolio_nav_menu_objects( $menu_items ) {

	if ( evie_is_portfolio_page() ) {

		$portfolio_page = evie_portfolio_page();

		if ( ! empty( $menu_items ) && is_array( $menu_items ) ) {
			foreach ( $menu_items as $key => $menu_item ) {

				$classes = (array) $menu_item->classes;

				$menu_id = (int) $menu_item->object_id;

				if ( $portfolio_page === $menu_id && 'page' === $menu_item->object ) {

					$menu_items[ $key ]->current = true;

					$classes[] = 'current-menu-item';
					$classes[] = 'current_page_item';

				}

				$menu_items[ $key ]->classes = array_unique( $classes );
			}
		}
	}

	return $menu_items;
}

add_filter( 'wp_nav_menu_objects', 'evie_portfolio_nav_menu_objects' );

/**
 * Filters whether the current post is open for comments.
 *
 * @param bool $open    Whether the current post is open for comments.
 * @param int  $post_id The post ID.
 * @return bool Whether the current post is open for comments.
 */
function evie_portfolio_comments_open( $open, $post_id ) {
	$post = get_post( $post_id );

	if ( $post && evie_portfolio_post_type_name() === $post->post_type ) {
		$settings = evie_portfolio_settings();
		if ( true !== $settings['comments'] ) {
			$open = false;
		}
	}

	return $open;
}

add_filter( 'comments_open', 'evie_portfolio_comments_open', 10, 2 );

/**
 * Adds a post display state for special Portfolio page in the page list table.
 *
 * @param string[] $post_states An array of post display states.
 * @param WP_Post  $post        The current post object.
 * @return string[] An array of post display states.
 */
function evie_portfolio_add_display_post_states( $post_states, $post ) {

	if ( evie_is_portfolio_page( $post->ID ) ) {
		$post_states['evie_page_for_portfolio'] = esc_html__( 'Portfolio Page', 'evie-xt' );
	}

	return $post_states;
}

add_filter( 'display_post_states', 'evie_portfolio_add_display_post_states', 10, 2 );

/**
 * Disables page templates when editing the portfolio page.
 *
 * @param array    $page_templates An array of the templates.
 * @param WP_Theme $theme          The theme object.
 * @param WP_Post  $post           The current post object.
 * @return array
 */
function evie_portfolio_disable_page_templates( $page_templates, $theme, $post ) {

	if ( $post && evie_is_portfolio_page( $post->ID ) ) {
		$page_templates = array();
	}

	return $page_templates;
}

add_filter( 'theme_page_templates', 'evie_portfolio_disable_page_templates', 10, 3 );

/**
 * Filters the post types to display in the Post Tabs widget.
 *
 * @param array $post_types An array of post types.
 * @return array An array of post types.
 */
function evie_portfolio_widget_post_types( $post_types = array() ) {

	$post_type = get_post_type_object( evie_portfolio_post_type_name() );
	if ( null !== $post_type ) {
		$post_types[ $post_type->name ] = $post_type->labels->singular_name;
	}

	return $post_types;
}

add_filter( 'flextension_post_carousel_widget_post_types', 'evie_portfolio_widget_post_types' );

add_filter( 'flextension_post_tabs_widget_post_types', 'evie_portfolio_widget_post_types' );

/**
 * Filters the post taxonomy to show in the widget.
 *
 * @param string $taxonomy  The taxonomy to show.
 * @param string $post_type The current post type.
 * @return string The post taxonomy to show in the widget.
 */
function evie_portfolio_widget_post_taxonomy( $taxonomy, $post_type ) {
	if ( evie_portfolio_post_type_name() === $post_type ) {
		$taxonomy = 'project_category';
	}
	return $taxonomy;
}

add_filter( 'flextension_widget_post_taxonomy', 'evie_portfolio_widget_post_taxonomy', 10, 2 );

/**
 * Filters an array of the arguments to retrieve the posts.
 *
 * @param array  $args An array of the arguments to retrieve the posts.
 * @param string $type Data source type.
 * @return array An array of the arguments to retrieve the posts.
 */
function evie_portfolio_post_carousel_args( $args, $type ) {
	if ( evie_portfolio_post_type_name() === $args['post_type'] && 'tags' === $type ) {
		$args['project_tag'] = $args['tag'];
		unset( $args['tag'] );
	}
	return $args;
}

add_filter( 'flextension_post_carousel_args', 'evie_portfolio_post_carousel_args', 10, 2 );

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
function evie_portfolio_featured_media_meta_fields( $fields = array(), $post = 0 ) {
	if ( evie_portfolio_post_type_name() === $post->post_type ) {

		$fields[0]['options'] = array(
			'slider'   => esc_html__( 'Slider', 'evie-xt' ),
			'rollover' => esc_html__( 'Image Rollover', 'evie-xt' ),
			'video'    => esc_html__( 'Video', 'evie-xt' ),
			'audio'    => esc_html__( 'Audio', 'evie-xt' ),
		);

		$fields[1]['dependencies'] = array(
			array(
				'name'  => '_flext_featured_media_type',
				'value' => 'rollover',
			),
		);

		$fields[2]['dependencies'] = array(
			array(
				'name'  => '_flext_featured_media_type',
				'value' => array( 'slider' ),
			),
		);

		$fields[3]['media_type']   = 'audio, video';
		$fields[3]['dependencies'] = array(
			array(
				'name'  => '_flext_featured_media_type',
				'value' => array( 'audio', 'video' ),
			),
		);

	}
	return $fields;
}

add_filter( 'flextension_featured_media_meta_fields', 'evie_portfolio_featured_media_meta_fields', 10, 2 );

/**
 * Enqueues setting panel scripts and stylesheets.
 */
function evie_portfolio_enqueue_setting_panel_scripts() {
	$screen = get_current_screen();
	if ( evie_portfolio_post_type_name() === $screen->post_type ) {

		wp_enqueue_style( 'evie-project-settings', plugins_url( 'css/editor.css', __FILE__ ), array(), EVIE_XT_VERSION );
		wp_style_add_data( 'evie-project-settings', 'rtl', 'replace' );

		wp_enqueue_script(
			'evie-project-settings',
			plugins_url( 'js/index.js', __FILE__ ),
			array(
				'wp-components',
				'wp-compose',
				'wp-core-data',
				'wp-data',
				'wp-editor',
				'wp-edit-post',
				'wp-element',
				'wp-hooks',
				'wp-i18n',
				'wp-plugins',
				'flextension',
			),
			EVIE_XT_VERSION,
			true
		);
	}
}

add_action( 'enqueue_block_editor_assets', 'evie_portfolio_enqueue_setting_panel_scripts' );
