<?php
/**
 * Blog Settings
 *
 * @package    Evie
 * @subpackage Customizer
 * @version    1.0.0
 */

/**
 * Returns a list of hover effect options.
 *
 * @return array An array of the hover effect options.
 */
function evie_customizer_posts_hover_effect_options() {
	return array(
		'none' => esc_html__( 'None', 'evie' ),
		'1'    => esc_html__( 'Inset Border', 'evie' ),
		'3'    => esc_html__( 'Ripple', 'evie' ),
		'2'    => esc_html__( 'Slide Up', 'evie' ),
	);
}

/**
 * Returns a list of reveal animation options.
 *
 * @return array An array of the reveal animation options.
 */
function evie_customizer_posts_animation_options() {
	return array(
		''  => esc_html__( 'None', 'evie' ),
		'2' => esc_html__( 'Fade In', 'evie' ),
		'3' => esc_html__( 'Fade Up', 'evie' ),
		'1' => esc_html__( 'Zoom In', 'evie' ),
	);
}

/**
 * Returns a list of pagination options.
 *
 * @return array An array of the pagination options.
 */
function evie_customizer_posts_pagination_options() {
	return array(
		'scroll'        => esc_html__( 'Infinite Scroll', 'evie' ),
		'loadmore'      => esc_html__( 'Load More', 'evie' ),
		'next_previous' => esc_html__( 'Next and Previous', 'evie' ),
		'numbered'      => esc_html__( 'Numbered Pagination', 'evie' ),
	);
}

/**
 * Returns a list of author options.
 *
 * @return array An array of the author options.
 */
function evie_customizer_posts_author_options() {
	return array(
		'hide'   => esc_html__( 'Hide', 'evie' ),
		'name'   => esc_html__( 'Name Only ', 'evie' ),
		'avatar' => esc_html__( 'Avatar Only', 'evie' ),
		'all'    => esc_html__( 'Name and Avatar', 'evie' ),
	);
}

/**
 * Returns whether the customizer is on a view that supports posts page options.
 *
 * @return bool Whether the customizer is on a view that supports posts page options.
 */
function evie_customizer_is_view_with_posts_page_options() {
	return is_home();
}

/**
 * Returns whether the blog posts parallax is enabled.
 *
 * @return bool Whether the blog posts parallax is enabled.
 */
function evie_customizer_is_posts_parallax_enabled() {
	return is_home() && 'waterfall' === get_theme_mod( 'blog_posts_layout', '' );
}

/**
 * Returns whether the blog posts style is enabled.
 *
 * @return bool Whether the blog posts style is enabled.
 */
function evie_customizer_is_posts_style_enabled() {
	return is_home() && in_array( get_theme_mod( 'blog_posts_layout', '' ), array( 'grid', 'waterfall' ), true );
}

/**
 * Returns whether the blog archive parallax is enabled.
 *
 * @return bool Whether the blog archive parallax is enabled.
 */
function evie_customizer_is_archive_parallax_enabled() {
	return evie_customizer_is_view_with_archive_options() && 'waterfall' === get_theme_mod( 'blog_archive_layout', '' );
}

/**
 * Returns whether the archive posts style is enabled.
 *
 * @return bool Whether the archive posts style is enabled.
 */
function evie_customizer_is_archive_style_enabled() {
	return evie_customizer_is_view_with_archive_options() && in_array( get_theme_mod( 'blog_archive_layout', '' ), array( 'grid', 'waterfall' ), true );
}

/**
 * Returns whether the customizer is on a view that supports archive options.
 *
 * @return bool Whether the customizer is on a view that supports archive options.
 */
function evie_customizer_is_view_with_archive_options() {
	return ! is_home() && ( is_archive() || is_search() ) && 'post' === get_post_type();
}

/**
 * Returns whether the customizer is on a view that supports single post options.
 *
 * @return bool Whether the customizer is on a view that supports single post options.
 */
function evie_customizer_is_view_with_single_post_options() {
	return is_singular( 'post' );
}

/**
 * Renders the partial posts options.
 */
function evie_customizer_get_posts_options() {
	$show_filter = get_theme_mod( 'blog_posts_options_filter', false );
	$show_sortby = get_theme_mod( 'blog_posts_options_sortby', false );

	$args = array(
		'show_filter' => $show_filter,
		'show_sortby' => $show_sortby,
	);

	if ( $show_filter || $show_sortby ) {
		evie_posts_filters( $args );
	}
}

/**
 * Renders the partial archive options.
 */
function evie_customizer_get_archive_options() {
	$args = array(
		'show_filter' => get_theme_mod( 'blog_archive_options_filter', false ),
		'show_sortby' => get_theme_mod( 'blog_archive_options_sortby', false ),
	);

	evie_posts_filters( $args );
}

/**
 * Renders the partial posts.
 */
function evie_customizer_get_posts() {
	evie_posts();
}

/**
 * Renders the partial posts pagination.
 */
function evie_customizer_posts_pagination() {
	evie_posts_pagination( get_theme_mod( 'blog_posts_pagination', 'numbered' ) );
}

/**
 * Renders the partial archive pagination.
 */
function evie_customizer_archive_pagination() {
	evie_posts_pagination( get_theme_mod( 'blog_archive_pagination', 'numbered' ) );
}

/**
 * Registers the theme settings and controls in the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function evie_customizer_register_blog_settings( $wp_customize ) {

	/**
	 * Blog Panel
	 */
	$wp_customize->add_panel(
		'blog_panel',
		array(
			'title'       => esc_html__( 'Blog', 'evie' ),
			'description' => '',
			'capability'  => 'edit_theme_options',
			'priority'    => 110, // Before Homepage Settings.
		)
	);

	/**
	 * Blog -> Posts Page.
	 */
	$wp_customize->add_section(
		'blog_posts_section',
		array(
			'panel' => 'blog_panel',
			'title' => esc_html__( 'Posts Page', 'evie' ),
		)
	);

	// Blog -> Posts Page -> Custom Content.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'blog_posts_page_content',
			array(
				'section'         => 'blog_posts_section',
				'label'           => esc_html__( 'Custom Content', 'evie' ),
				'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
			)
		)
	);

	// Blog -> Posts Page -> Posts Page Content.
	$wp_customize->add_setting(
		'blog_posts_page_content',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_posts_page_content',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Displays Posts page content before the recent posts.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_posts_page_content',
		array(
			'selector'         => '.blog .main-content > .entry-content',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Posts Page -> Filtering & Sorting.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'blog_posts_options_heading',
			array(
				'section'         => 'blog_posts_section',
				'label'           => esc_html__( 'Filtering & Sorting', 'evie' ),
				'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
			)
		)
	);

	// Blog -> Posts Page -> Filtering & Sorting - Show filters.
	$wp_customize->add_setting(
		'blog_posts_options_filter',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_posts_options_filter',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Show filters.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_posts_options_filter',
		array(
			'selector'            => '.blog .main-posts .posts-filters',
			'render_callback'     => 'evie_customizer_get_posts_options',
			'container_inclusive' => true,
		)
	);

	// Blog -> Posts Page -> Filtering & Sorting. - Show sort by options.
	$wp_customize->add_setting(
		'blog_posts_options_sortby',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_posts_options_sortby',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Show sort by options.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_posts_options_sortby',
		array(
			'selector'            => '.blog .main-posts .posts-filters',
			'render_callback'     => 'evie_customizer_get_posts_options',
			'container_inclusive' => true,
		)
	);

	// Blog -> Posts Page -> - Layout.
	$wp_customize->add_setting(
		'blog_posts_layout',
		array(
			'default'           => 'list',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_posts_layout',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Layout', 'evie' ),
			'description'     => esc_html__( 'Choose blog posts layout.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				'large'     => esc_html__( 'Large', 'evie' ),
				'list'      => esc_html__( 'List', 'evie' ),
				'grid'      => esc_html__( 'Grid', 'evie' ),
				'waterfall' => esc_html__( 'Waterfall', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_posts_layout',
		array(
			'selector'            => '.blog .main-posts',
			'render_callback'     => 'evie_customizer_get_posts',
			'container_inclusive' => true,
		)
	);

	// Blog -> Posts Page -> Parallax.
	$wp_customize->add_setting(
		'blog_posts_parallax',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_posts_parallax',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Parallax Columns', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_posts_parallax_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_posts_parallax',
		array(
			'selector'            => '.blog .main-posts',
			'render_callback'     => 'evie_customizer_get_posts',
			'container_inclusive' => true,
		)
	);

	// Blog -> Posts Page -> Style.
	$wp_customize->add_setting(
		'blog_posts_style',
		array(
			'default'           => 'card',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_posts_style',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Style', 'evie' ),
			'description'     => esc_html__( 'The style of the item in the list.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				'card'         => esc_html__( 'Card', 'evie' ),
				'text-overlay' => esc_html__( 'Text Overlay', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_posts_style_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_posts_style',
		array(
			'selector'            => '.blog .main-posts',
			'render_callback'     => 'evie_customizer_get_posts',
			'container_inclusive' => true,
		)
	);

	// Blog -> Posts Page -> Hover Effect.
	$wp_customize->add_setting(
		'blog_posts_hover_effect',
		array(
			'default'           => 'none',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_posts_hover_effect',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Hover Effect', 'evie' ),
			'description'     => esc_html__( 'The hover effect of the item in the list.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_posts_hover_effect_options(),
			'active_callback' => 'evie_customizer_is_posts_style_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_posts_hover_effect',
		array(
			'selector'            => '.blog .main-posts',
			'render_callback'     => 'evie_customizer_get_posts',
			'container_inclusive' => true,
		)
	);

	// Blog -> Posts Page -> Animation.
	$wp_customize->add_setting(
		'blog_posts_animation',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_posts_animation',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Animation', 'evie' ),
			'description'     => esc_html__( 'Choose a transition for the post.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_posts_animation_options(),
			'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_posts_animation',
		array(
			'selector'            => '.blog .main-posts',
			'render_callback'     => 'evie_customizer_get_posts',
			'container_inclusive' => true,
		)
	);

	// Blog -> Posts Page -> Pagination.
	$wp_customize->add_setting(
		'blog_posts_pagination',
		array(
			'default'           => 'numbered',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_posts_pagination',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Pagination', 'evie' ),
			'description'     => esc_html__( 'Choose posts pagination type.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_posts_pagination_options(),
			'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_posts_pagination',
		array(
			'selector'            => '.blog .main-posts .pagination',
			'render_callback'     => 'evie_customizer_posts_pagination',
			'container_inclusive' => true,
		)
	);

	// Blog -> Posts Page -> Post Metadata.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'blog_posts_meta_heading',
			array(
				'section'         => 'blog_posts_section',
				'label'           => esc_html__( 'Post Metadata', 'evie' ),
				'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
			)
		)
	);

	// Blog -> Posts Page -> Publication date.
	$wp_customize->add_setting(
		'blog_posts_date',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_posts_date',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Publication date.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_posts_date',
		array(
			'selector'         => '.blog .main-posts .post .meta-date',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Posts Page -> Post category.
	$wp_customize->add_setting(
		'blog_posts_category',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_posts_category',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Post category.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_posts_category',
		array(
			'selector'         => '.blog .main-posts .post .meta-category',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Posts Page -> Post Buttons.
	$wp_customize->add_setting(
		'blog_posts_buttons',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_posts_buttons',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Comments, Likes and Share buttons.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_posts_buttons',
		array(
			'selector'         => '.blog .main-posts .post .entry-buttons',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Posts Page -> Author.
	$wp_customize->add_setting(
		'blog_posts_author',
		array(
			'default'           => 'all',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_posts_author',
		array(
			'section'         => 'blog_posts_section',
			'label'           => esc_html__( 'Author', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_posts_author_options(),
			'active_callback' => 'evie_customizer_is_view_with_posts_page_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_posts_author',
		array(
			'selector'         => '.blog .main-posts .post .author',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	/**
	 * Blog -> Archive Pages.
	 */
	$wp_customize->add_section(
		'blog_archive_section',
		array(
			'panel' => 'blog_panel',
			'title' => esc_html__( 'Archive Pages', 'evie' ),
		)
	);

	// Blog -> Archive Pages -> Filtering & Sorting.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'blog_archive_options_heading',
			array(
				'section'         => 'blog_archive_section',
				'label'           => esc_html__( 'Filtering & Sorting', 'evie' ),
				'active_callback' => 'evie_customizer_is_view_with_archive_options',
			)
		)
	);

	// Blog -> Archive Pages -> Filtering & Sorting - Show filters.
	$wp_customize->add_setting(
		'blog_archive_options_filter',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_archive_options_filter',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Show filters.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_archive_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_archive_options_filter',
		array(
			'selector'            => '.archive .main-posts.posts-type-post .posts-filters',
			'render_callback'     => 'evie_customizer_get_archive_options',
			'container_inclusive' => true,
		)
	);

	// Blog -> Archive Pages -> Filtering & Sorting - Show sort by options.
	$wp_customize->add_setting(
		'blog_archive_options_sortby',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_archive_options_sortby',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Show sort by options.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_archive_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_archive_options_sortby',
		array(
			'selector'            => '.archive .main-posts.posts-type-post .posts-filters',
			'render_callback'     => 'evie_customizer_get_archive_options',
			'container_inclusive' => true,
		)
	);

	// Blog -> Archive Pages -> - Layout.
	$wp_customize->add_setting(
		'blog_archive_layout',
		array(
			'default'           => 'list',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_archive_layout',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Layout', 'evie' ),
			'description'     => esc_html__( 'Choose blog posts layout.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				'large'     => esc_html__( 'Large', 'evie' ),
				'list'      => esc_html__( 'List', 'evie' ),
				'grid'      => esc_html__( 'Grid', 'evie' ),
				'waterfall' => esc_html__( 'Waterfall', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_view_with_archive_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_archive_layout',
		array(
			'selector'            => '.archive .main-posts.posts-type-post',
			'render_callback'     => 'evie_customizer_get_posts',
			'container_inclusive' => true,
		)
	);

	// Blog -> Archive Pages -> Parallax.
	$wp_customize->add_setting(
		'blog_archive_parallax',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_archive_parallax',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Parallax Columns', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_archive_parallax_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_archive_parallax',
		array(
			'selector'            => '.archive .main-posts.posts-type-post',
			'render_callback'     => 'evie_customizer_get_posts',
			'container_inclusive' => true,
		)
	);

	// Blog -> Archive Pages -> Style.
	$wp_customize->add_setting(
		'blog_archive_style',
		array(
			'default'           => 'card',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_archive_style',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Style', 'evie' ),
			'description'     => esc_html__( 'The style of the item in the list.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				'card'         => esc_html__( 'Card', 'evie' ),
				'text-overlay' => esc_html__( 'Text Overlay', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_archive_style_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_archive_style',
		array(
			'selector'            => '.archive .main-posts.posts-type-post',
			'render_callback'     => 'evie_customizer_get_posts',
			'container_inclusive' => true,
		)
	);

	// Blog -> Archive Pages -> Hover Effect.
	$wp_customize->add_setting(
		'blog_archive_hover_effect',
		array(
			'default'           => 'none',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_archive_hover_effect',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Hover Effect', 'evie' ),
			'description'     => esc_html__( 'The hover effect of the item in the list.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_posts_hover_effect_options(),
			'active_callback' => 'evie_customizer_is_archive_style_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_archive_hover_effect',
		array(
			'selector'            => '.archive .main-posts.posts-type-post',
			'render_callback'     => 'evie_customizer_get_posts',
			'container_inclusive' => true,
		)
	);

	// Blog -> Archive Pages -> Animation.
	$wp_customize->add_setting(
		'blog_archive_animation',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_archive_animation',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Animation', 'evie' ),
			'description'     => esc_html__( 'Choose a transition for the post.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_posts_animation_options(),
			'active_callback' => 'evie_customizer_is_view_with_archive_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_archive_animation',
		array(
			'selector'            => '.archive .main-posts.posts-type-post',
			'render_callback'     => 'evie_customizer_get_posts',
			'container_inclusive' => true,
		)
	);

	// Blog -> Archive Pages -> Pagination.
	$wp_customize->add_setting(
		'blog_archive_pagination',
		array(
			'default'           => 'numbered',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_archive_pagination',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Pagination', 'evie' ),
			'description'     => esc_html__( 'Choose posts pagination type.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_posts_pagination_options(),
			'active_callback' => 'evie_customizer_is_view_with_archive_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blog_archive_pagination',
		array(
			'selector'            => '.archive .main-posts.posts-type-post .pagination',
			'render_callback'     => 'evie_customizer_archive_pagination',
			'container_inclusive' => true,
		)
	);

	// Blog -> Archive Pages -> Post Metadata.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'blog_archive_meta_heading',
			array(
				'section'         => 'blog_archive_section',
				'label'           => esc_html__( 'Post Metadata', 'evie' ),
				'active_callback' => 'evie_customizer_is_view_with_archive_options',
			)
		)
	);

	// Blog -> Archive Pages -> Publication date.
	$wp_customize->add_setting(
		'blog_archive_date',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_archive_date',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Publication date.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_archive_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_archive_date',
		array(
			'selector'         => '.archive .main-posts.posts-type-post .post .meta-date',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Archive Pages -> Post category.
	$wp_customize->add_setting(
		'blog_archive_category',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_archive_category',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Post category.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_archive_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_archive_category',
		array(
			'selector'         => '.blog .main-posts .post .meta-category',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Archive Pages -> Post Buttons.
	$wp_customize->add_setting(
		'blog_archive_buttons',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_archive_buttons',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Comments, Likes and Share buttons.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_archive_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_archive_buttons',
		array(
			'selector'         => '.blog .main-posts .post .entry-buttons',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Archive Pages -> Author.
	$wp_customize->add_setting(
		'blog_archive_author',
		array(
			'default'           => 'all',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'blog_archive_author',
		array(
			'section'         => 'blog_archive_section',
			'label'           => esc_html__( 'Author', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_posts_author_options(),
			'active_callback' => 'evie_customizer_is_view_with_archive_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_archive_author',
		array(
			'selector'         => '.blog .main-posts .post .author',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	/**
	 * Blog -> Single Post
	 */
	$wp_customize->add_section(
		'blog_single_post_section',
		array(
			'panel' => 'blog_panel',
			'title' => esc_html__( 'Single Post', 'evie' ),
		)
	);

	// Blog -> Single Post -> Post Metadata.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'blog_single_post_meta_heading',
			array(
				'section'         => 'blog_single_post_section',
				'label'           => esc_html__( 'Post Metadata', 'evie' ),
				'active_callback' => 'evie_customizer_is_view_with_single_post_options',
			)
		)
	);

	// Blog -> Single Post -> Publication date.
	$wp_customize->add_setting(
		'blog_single_post_date',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_single_post_date',
		array(
			'section'         => 'blog_single_post_section',
			'label'           => esc_html__( 'Publication date.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_post_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_single_post_date',
		array(
			'selector'         => '.single-post .single-entry .meta-date',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Single Post -> Post category.
	$wp_customize->add_setting(
		'blog_single_post_category',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_single_post_category',
		array(
			'section'         => 'blog_single_post_section',
			'label'           => esc_html__( 'Post category.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_post_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_single_post_category',
		array(
			'selector'         => '.single-post .single-entry .meta-categories',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Single Post -> Post tags.
	$wp_customize->add_setting(
		'blog_single_post_tags',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_single_post_tags',
		array(
			'section'         => 'blog_single_post_section',
			'label'           => esc_html__( 'Post tags.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_post_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_single_post_tags',
		array(
			'selector'         => '.single-post .single-entry .tags-links',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Single Post -> Post Buttons.
	$wp_customize->add_setting(
		'blog_single_post_buttons',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_single_post_buttons',
		array(
			'section'         => 'blog_single_post_section',
			'label'           => esc_html__( 'Comments, Likes and Share buttons.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_post_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_single_post_buttons',
		array(
			'selector'         => '.single-post .single-entry-footer .entry-buttons',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Single Post -> Author Information.
	$wp_customize->add_setting(
		'blog_single_post_author',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_single_post_author',
		array(
			'section'         => 'blog_single_post_section',
			'label'           => esc_html__( 'Author information.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_post_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_single_post_author',
		array(
			'selector'         => '.single-post .single-entry-footer .post-author',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Single Post -> Related Posts.
	$wp_customize->add_setting(
		'blog_single_post_related',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_single_post_related',
		array(
			'section'         => 'blog_single_post_section',
			'label'           => esc_html__( 'Related Posts.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_post_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'blog_single_post_related',
		array(
			'selector'         => '.single-post .related-posts',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Blog -> Single Post -> Post Navigation.
	$wp_customize->add_setting(
		'blog_single_post_navigation',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'blog_single_post_navigation',
		array(
			'section'         => 'blog_single_post_section',
			'label'           => esc_html__( 'Post Navigation.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_post_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'single_post_navigation',
		array(
			'selector'         => '.single-post .post-navigation',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);
}

add_action( 'customize_register', 'evie_customizer_register_blog_settings', 20 );
