<?php
/**
 * Additional functions to allow styling of the portfolio templates.
 *
 * This file should be loaded only when the portfolio module from Evie XT plugin is active.
 *
 * @package Evie
 * @version 1.0.0
 */

/**
 * Adds portfolio styles to the editor.
 */
function evie_portfolio_add_editor_style() {
	add_editor_style( 'assets/css/portfolio.css' );
}

add_action( 'after_setup_theme', 'evie_portfolio_add_editor_style' );

/**
 * Registers styles and scripts for portfolio module.
 */
function evie_portfolio_register_assets() {
	wp_register_style( 'evie-portfolio', get_theme_file_uri( 'assets/css/portfolio.css' ), array(), wp_get_theme()->get( 'Version' ) );
	wp_style_add_data( 'evie-portfolio', 'rtl', 'replace' );
}

add_action( 'init', 'evie_portfolio_register_assets', 20 );

/**
 * Enqueues scripts and styles for the portfolio.
 */
function evie_portfolio_enqueue_assets() {
	wp_enqueue_style( 'evie-portfolio' );
}

add_action( 'wp_enqueue_scripts', 'evie_portfolio_enqueue_assets', 20 );

/**
 * Enqueues block editor scripts and styles for Block Editor.
 */
function evie_portfolio_enqueue_block_editor_assets() {
	wp_enqueue_style( 'evie-portfolio' );
}

add_action( 'enqueue_block_editor_assets', 'evie_portfolio_enqueue_block_editor_assets', 20 );

/**
 * Sets posts query for the Portfolio page.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function evie_portfolio_set_posts_query( $query ) {
	// Only want to affect the main query.
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$post_type = evie_portfolio_post_type_name();

	if ( $query->is_post_type_archive( $post_type ) && $query->get( 's' ) ) {
		$query->is_post_type_archive = false;
		$query->is_search            = true;
	} elseif ( $query->is_post_type_archive( $post_type ) ) {
		$query->set( 'posts_per_page', (int) get_theme_mod( 'portfolio_posts_per_page', 10 ) );
	} elseif ( is_archive() && ( get_post_type() === $post_type || in_array( $post_type, (array) $query->get( 'post_type' ), true ) ) ) {
		$query->set( 'posts_per_page', (int) get_theme_mod( 'portfolio_archive_per_page', 10 ) );
	} elseif ( is_tax() ) {
		$queried_object = get_queried_object();
		if ( $queried_object instanceof WP_Term ) {
			$taxonomy = get_taxonomy( $queried_object->taxonomy );
			if ( $taxonomy && isset( $taxonomy->object_type ) && in_array( $post_type, $taxonomy->object_type, true ) ) {
				$query->set( 'post_type', $post_type );
				$query->set( 'posts_per_page', (int) get_theme_mod( 'portfolio_archive_per_page', 10 ) );
			}
		}
	}
}

add_action( 'pre_get_posts', 'evie_portfolio_set_posts_query', 50 );

/**
 * Returns the Portfolio page ID if on a project post type archive.
 *
 * @param int $id The page ID.
 * @return int The page ID.
 */
function evie_portfolio_get_page_id( $id ) {
	$post_type = evie_portfolio_post_type_name();
	if ( is_post_type_archive( $post_type ) || ( ! is_search() && evie_is_archive( $post_type ) ) ) {
		$id = evie_portfolio_page();
	}

	return $id;
}

add_filter( 'evie_get_page_id', 'evie_portfolio_get_page_id' );

/**
 * Filters the portfolio customizer prefix.
 *
 * @param string       $prefix The customizer setting prefix.
 * @param string|array $type   The type of customizer setting.
 * @return string The customizer setting prefix.
 */
function evie_portfolio_customizer_prefix( $prefix = '', $type = '' ) {
	if ( is_search() ) {
		return $prefix;
	}

	$is_portfolio_type = false;

	$post_type = evie_portfolio_post_type_name();

	if ( is_array( $type ) && ! empty( $type ) && in_array( $post_type, $type, true ) ) {
		$is_portfolio_type = true;
	} elseif ( $type === $post_type ) {
		$is_portfolio_type = true;
	}

	if ( true === $is_portfolio_type ) {
		$prefix = 'portfolio';
		if ( is_post_type_archive( $post_type ) ) {
			$prefix .= '_posts';
		} elseif ( is_singular( $post_type ) ) {
			$prefix .= '_single';
		} else {
			$prefix .= '_archive';
		}
	}

	return $prefix;
}

add_filter( 'evie_customizer_prefix', 'evie_portfolio_customizer_prefix', 10, 2 );

/**
 * Returns the project color scheme.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @return string The project color scheme.
 */
function evie_portfolio_project_scheme( $post = 0 ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	$scheme = get_post_meta( $post->ID, '_evie_project_scheme', true );
	if ( ! empty( $scheme ) ) {
		return 'has-scheme-' . $scheme;
	}

	return '';
}

/**
 * Returns the project color.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @return string The project color scheme.
 */
function evie_portfolio_project_color( $post = 0 ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	$color = get_post_meta( $post->ID, '_evie_project_color', true );

	if ( ! empty( $color ) ) {
		return sanitize_hex_color( $color );
	}

	return '';
}

/**
 * Adds a text mode class to the post class for the Large layout.
 *
 * @param array $classes Array of post classes.
 * @param array $args    Arguments to generate the posts. See evie_posts_args() for all.
 */
function evie_portfolio_project_class( $classes, $args ) {
	if ( evie_portfolio_post_type_name() === get_post_type() && ( in_array( $args['layout'], array( 'large', 'list' ), true ) || 'card' !== $args['style'] ) ) {
		$scheme_class = evie_portfolio_project_scheme();
		if ( ! empty( $scheme_class ) ) {
			$classes[] = $scheme_class;
		}
	}

	return $classes;
}

add_filter( 'evie_post_class', 'evie_portfolio_project_class', 10, 2 );

/**
 * Inserts the Portfolio page content before the projects list on the portfolio page.
 */
function evie_portfolio_page_content() {

	if ( is_post_type_archive( evie_portfolio_post_type_name() ) && ( true === get_theme_mod( 'portfolio_posts_page_content', false ) || is_customize_preview() ) ) {
		$content = get_the_content( null, false, evie_portfolio_page() );

		// Remove an empty paragraph.
		$content = preg_replace( '#<p>\s*</p>#', '', $content );

		/** This filter is documented in wp-includes/post-template.php */
		$content = apply_filters( 'the_content', $content ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound, WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound

		$content = trim( $content );
		if ( ! empty( $content ) ) {
			echo '<div class="entry-content">';
			echo str_replace( ']]>', ']]&gt;', $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
		}
	}

}

add_action( 'evie_before_content', 'evie_portfolio_page_content' );

/**
 * Displays the project link.
 *
 * @param int|WP_Post $post  Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string      $label Optional. The label for the link.
 */
function evie_portfolio_project_link( $post = 0, $label = '' ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$project_url = get_post_meta( $post->ID, '_evie_project_url', true );
	if ( ! empty( $project_url ) ) :
		?>
		<p class="project-link">
			<?php echo evie_get_more_link( $post, $project_url, $label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</p><!-- .project-link -->
		<?php
	endif;
}

/**
 * Filters the post types in the filters list.
 *
 * @param array $post_types An array list of post types.
 * @return array An array list of post types.
 */
function evie_portfolio_filter_post_types( $post_types = array() ) {
	$post_types[] = evie_portfolio_post_type_name();
	return $post_types;
}

add_filter( 'evie_filter_post_types', 'evie_portfolio_filter_post_types' );

/**
 * Filters the list of filter taxonomies.
 *
 * @param array $taxonomies An array list of taxonomies.
 * @return array An array list of taxonomies.
 */
function evie_portfolio_filter_taxonomies( $taxonomies = array() ) {
	if ( ! is_search() && isset( $taxonomies['project_category'] ) ) {
		unset( $taxonomies['project_category'] );
	}
	return $taxonomies;
}

add_filter( 'evie_posts_filter_taxonomies', 'evie_portfolio_filter_taxonomies' );

/**
 * Filters the list of categories arguments.
 *
 * @param array $args Array of categories arguments.
 * @return array An array of categories arguments. *
 */
function evie_portfolio_list_categories_args( $args = array() ) {

	$post_type = evie_portfolio_post_type_name();
	if ( in_array( $post_type, (array) get_query_var( 'post_type' ), true ) ) {
		$args['taxonomy'] = 'project_category';
	}

	return $args;
}

add_filter( 'evie_posts_filters_args', 'evie_portfolio_list_categories_args' );

/**
 * Sets the portfolio breadcrumb options.
 *
 * @param array $options Breadcrumb options.
 * @return array New breadcrumb options.
 */
function evie_portfolio_breadcrumb_options( $options = array() ) {
	if ( in_array( evie_portfolio_post_type_name(), (array) get_query_var( 'post_type' ), true ) ) {
		$options['taxonomy'] = 'project_category';
	}
	return $options;
}

add_filter( 'evie_breadcrumb_options', 'evie_portfolio_breadcrumb_options' );

/**
 * Filters the arguments to generate the related posts.
 *
 * @param array  $attributes The related posts carousel attributes.
 * @param string $post_type  The post type.
 * @return array New arguments to generate the related posts.
 */
function evie_portfolio_related_posts_attributes( $attributes = array(), $post_type = '' ) {
	if ( evie_portfolio_post_type_name() === $post_type ) {

		$attributes['query'] = array(
			'postType'      => $post_type,
			'numberOfItems' => 10,
		);

		$query_vars = isset( $attributes['query_vars'] ) ? $attributes['query_vars'] : array();

		$query_vars['post_type'] = $post_type;
		$query_vars['tax_query'] = array();

		$project_tags = get_the_terms( get_the_ID(), 'project_tag' );
		if ( ! empty( $project_tags ) ) {
			$query_vars['tax_query'][] = array(
				'taxonomy' => 'project_tag',
				'field'    => 'term_id',
				'terms'    => wp_list_pluck( $project_tags, 'term_id' ),
			);
		}

		$project_attributes = get_the_terms( get_the_ID(), 'project_attribute' );
		if ( ! empty( $project_attributes ) ) {
			$query_vars['tax_query'][] = array(
				'taxonomy' => 'project_attribute',
				'field'    => 'term_id',
				'terms'    => wp_list_pluck( $project_attributes, 'term_id' ),
			);
		}

		if ( empty( $query_vars['tax_query'] ) ) {
			$project_categories = get_the_terms( get_the_ID(), 'project_category' );
			if ( ! empty( $project_categories ) ) {
				$query_vars['tax_query'][] = array(
					'taxonomy' => 'project_category',
					'field'    => 'term_id',
					'terms'    => wp_list_pluck( $project_categories, 'term_id' ),
				);
			}
		}

		if ( count( $query_vars['tax_query'] ) > 1 ) {
			$query_vars['tax_query']['relation'] = 'OR';
		}

		$attributes['query_vars'] = $query_vars;
	}

	return $attributes;
}

add_filter( 'evie_related_posts_attributes', 'evie_portfolio_related_posts_attributes', 10, 2 );

/**
 * Returns the project clients.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @return array Array of the project clients.
 */
function evie_portfolio_get_project_clients( $post = 0 ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$clients = array();

	$values = get_post_meta( $post->ID, '_evie_project_clients', true );
	if ( ! empty( $values ) ) {
		foreach ( $values as $value ) {

			if ( empty( $value['name'] ) ) {
				continue;
			}

			if ( ! empty( $value['website'] ) ) {
				$clients[] = sprintf( '<a href="%1$s" rel="nofollow" target="_blank">%2$s</a>', esc_url( $value['website'] ), esc_html( $value['name'] ) );
			} else {
				$clients[] = '<span>' . esc_html( $value['name'] ) . '</span>';
			}
		}
	}

	return $clients;
}

/**
 * Returns the project custom fields.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @return array Array of the project clients.
 */
function evie_portfolio_get_project_fields( $post = 0 ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$fields = array();

	$values = get_post_meta( $post->ID, '_evie_project_fields', true );
	if ( ! empty( $values ) ) {
		foreach ( $values as $value ) {

			if ( empty( $value['title'] ) ) {
				continue;
			}

			$fields[] = array(
				'name'  => esc_html( $value['title'] ),
				'value' => esc_html( $value['content'] ),
			);
		}
	}

	return $fields;
}

/**
 * Retrieves the taxonomy attributes for the project.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @return array An array of the taxonomy attributes.
 */
function evie_portfolio_get_taxonomy_attributes( $post = 0 ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$attributes = array();

	$taxonomies = evie_portfolio_attribute_taxonomies();

	if ( ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy => $labels ) {

			$label = isset( $labels['name'] ) ? $labels['name'] : $taxonomy;

			$terms = get_the_term_list( $post->ID, 'project_' . $taxonomy, '', ', ' );

			if ( ! empty( $terms ) ) {
				$attributes[] = array(
					'name'  => $label,
					'value' => $terms,
				);
			}
		}
	}

	return $attributes;
}

/**
 * Retrieves the project attributes.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @return array An array of the project attributes.
 */
function evie_portfolio_get_project_attributes( $post = 0 ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$attributes = array();

	$values = get_post_meta( $post->ID, '_evie_project_attributes', true );

	if ( ! empty( $values ) ) {
		foreach ( $values as $value ) {
			if ( empty( $value['title'] ) ) {
				continue;
			}

			$terms = get_terms(
				array(
					'include' => $value['terms'],
				)
			);

			if ( is_wp_error( $terms ) ) {
				$terms = array();
			}

			$links = array();

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$link = get_term_link( $term, $term->taxonomy );
					if ( is_wp_error( $link ) ) {
						return $link;
					}
					$links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
				}
			}

			$attributes[] = array(
				'name'  => esc_html( $value['title'] ),
				'value' => implode( ', ', $links ),
			);
		}
	}

	return $attributes;
}

/**
 * Displays the project attributes.
 *
 * @param int|WP_Post $post    Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param int         $columns Optional. The number of columns to display. Default 2.
 * @param int         $rows    Optional. The number of rows to display. Default -1 (no limit).
 */
function evie_portfolio_project_attributes( $post = 0, $columns = 2, $rows = -1 ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$attributes = array_merge(
		evie_portfolio_get_taxonomy_attributes( $post ),
		evie_portfolio_get_project_attributes( $post ),
		evie_portfolio_get_project_fields( $post )
	);

	$count = $columns * absint( $rows );

	if ( $rows < 1 || $count > count( $attributes ) ) {
		$clients = evie_portfolio_get_project_clients( $post );
		if ( ! empty( $clients ) ) {
			$attributes[] = array(
				'name'  => _n( 'Client', 'Clients', count( $clients ), 'evie' ),
				'value' => implode( ', ', $clients ),
			);
		}
	}

	if ( ! empty( $attributes ) ) {
		if ( -1 === $rows ) {
			$count = count( $attributes );
		}

		$count = min( $count, count( $attributes ) );

		$classes = array( 'project-attributes' );

		$columns = min( $columns, $count );

		$classes[] = evie_get_grid_columns_class( $columns );

		echo '<ul class="' . esc_attr( implode( ' ', $classes ) ) . '">';
		for ( $i = 0; $i < $count; $i++ ) {
			echo sprintf( '<li><h6 class="project-attribute-title">%1$s</h6><p>%2$s</p></li>', $attributes[ $i ]['name'], $attributes[ $i ]['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</ul>';
	}
}

/**
 * Prints out the post footer for the single project.
 */
function evie_single_project_footer() {
	$is_preview   = is_customize_preview();
	$show_tags    = ( true === get_theme_mod( 'portfolio_single_post_tags', true ) );
	$show_buttons = ( true === get_theme_mod( 'portfolio_single_post_buttons', false ) );
	$show_author  = ( true === get_theme_mod( 'portfolio_single_post_author', false ) );
	if ( $is_preview || $show_tags || $show_buttons || $show_author ) :
		?>
	<footer class="single-entry-footer">

		<?php if ( $is_preview || $show_tags || $show_buttons ) : ?>
		<div class="post-tags">
			<?php

			if ( $is_preview || $show_tags ) {
				evie_tags_list( get_the_ID(), 'project_tag' );
			}

			if ( $is_preview || $show_buttons ) {
				evie_single_post_buttons();
			}

			?>
		</div>
		<?php endif; ?>

		<?php
		if ( $is_preview || $show_author ) {
			evie_single_post_author();
		}
		?>

	</footer><!-- .single-entry-footer -->
		<?php
	endif;
}
