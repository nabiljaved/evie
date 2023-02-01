<?php
/**
 * Portfolio Settings
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
function evie_customizer_portfolio_hover_effect_options() {
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
function evie_customizer_portfolio_animation_options() {
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
function evie_customizer_portfolio_pagination_options() {
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
function evie_customizer_portfolio_author_options() {
	return array(
		'hide'   => esc_html__( 'Hide', 'evie' ),
		'name'   => esc_html__( 'Name Only ', 'evie' ),
		'avatar' => esc_html__( 'Avatar Only', 'evie' ),
		'all'    => esc_html__( 'Name and Avatar', 'evie' ),
	);
}

/**
 * Returns whether the customizer is on a view that supports portfolio options.
 *
 * @return bool Whether the customizer is on a view that supports portfolio options.
 */
function evie_customizer_is_view_with_portfolio_options() {
	return is_post_type_archive( evie_portfolio_post_type_name() );
}

/**
 * Renders the partial posts options.
 */
function evie_customizer_get_portfolio_options() {
	$show_filter = get_theme_mod( 'portfolio_posts_options_filter', false );
	$show_sortby = get_theme_mod( 'portfolio_posts_options_sortby', false );

	$args = array(
		'show_filter' => $show_filter,
		'show_sortby' => $show_sortby,
	);

	if ( $show_filter || $show_sortby ) {
		evie_posts_filters( $args );
	}
}

/**
 * Returns whether the portfolio parallax is enabled.
 *
 * @return bool Whether the portfolio parallax is enabled.
 */
function evie_customizer_is_portfolio_parallax_enabled() {
	return evie_customizer_is_view_with_portfolio_options() && 'waterfall' === get_theme_mod( 'portfolio_posts_layout', '' );
}

/**
 * Returns whether the posts style is enabled.
 *
 * @return bool Whether the posts style is enabled.
 */
function evie_customizer_is_portfolio_style_enabled() {
	return evie_customizer_is_view_with_portfolio_options() && in_array( get_theme_mod( 'portfolio_posts_layout', '' ), array( 'grid', 'waterfall' ), true );
}

/**
 * Renders the partial posts.
 */
function evie_customizer_get_portfolio() {
	evie_posts();
}

/**
 * Returns whether the customizer is on a view that supports archive options.
 *
 * @return bool Whether the customizer is on a view that supports archive options.
 */
function evie_customizer_is_view_with_portfolio_archive_options() {
	// This option is only available on archive pages.
	return ! evie_customizer_is_view_with_portfolio_options() && ( is_archive() || is_search() ) && evie_portfolio_post_type_name() === get_post_type();
}

/**
 * Returns whether the portfolio archive parallax is enabled.
 *
 * @return bool Whether the portfolio archive parallax is enabled.
 */
function evie_customizer_is_portfolio_archive_parallax_enabled() {
	return evie_customizer_is_view_with_portfolio_archive_options() && 'waterfall' === get_theme_mod( 'portfolio_archive_layout', '' );
}

/**
 * Returns whether the archive posts style is enabled.
 *
 * @return bool Whether the archive posts style is enabled.
 */
function evie_customizer_is_portfolio_archive_style_enabled() {
	return evie_customizer_is_view_with_portfolio_archive_options() && in_array( get_theme_mod( 'portfolio_archive_layout', '' ), array( 'grid', 'waterfall' ), true );
}

/**
 * Renders the partial posts pagination.
 */
function evie_customizer_portfolio_pagination() {
	evie_posts_pagination( get_theme_mod( 'portfolio_posts_pagination', 'numbered' ) );
}

/**
 * Renders the partial archive pagination.
 */
function evie_customizer_portfolio_archive_pagination() {
	evie_posts_pagination( get_theme_mod( 'portfolio_archive_pagination', 'numbered' ) );
}

/**
 * Renders the partial posts options.
 */
function evie_customizer_get_portfolio_archive_options() {
	$args = array(
		'show_filter' => get_theme_mod( 'portfolio_archive_options_filter', false ),
		'show_sortby' => get_theme_mod( 'portfolio_archive_options_sortby', false ),
	);

	evie_posts_filters( $args );
}

/**
 * Returns whether the customizer is on a view that supports single post options.
 *
 * @return bool Whether the customizer is on a view that supports single post options.
 */
function evie_customizer_is_view_with_single_project_options() {
	return is_singular( evie_portfolio_post_type_name() );
}

/**
 * Returns whether the customizer is on a view that supports quick view options.
 *
 * @return bool Whether the customizer is on a view that supports quick view options.
 */
function evie_customizer_is_view_with_portfolio_quick_view_options() {
	return evie_customizer_is_view_with_portfolio_options() || evie_customizer_is_view_with_portfolio_archive_options() || evie_customizer_is_view_with_single_project_options();
}

/**
 * Returns whether the Quick View option is enable.
 *
 * @return bool Whether the Quick View option is enable.
 */
function evie_customizer_is_portfolio_quick_view_enable() {
	return evie_customizer_is_view_with_portfolio_quick_view_options() && true === get_theme_mod( 'portfolio_quick_view_enable', true );
}

/**
 * Registers the theme settings and controls in the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function evie_customizer_register_portfolio_settings( $wp_customize ) {

	/**
	 * Portfolio Panel
	 */
	$wp_customize->add_panel(
		'portfolio_panel',
		array(
			'title'       => esc_html__( 'Portfolio', 'evie' ),
			'description' => '',
			'capability'  => 'edit_theme_options',
			'priority'    => 110, // Before Homepage Settings.
		)
	);

	/**
	 * Portfolio -> Portfolio Page.
	 */
	$wp_customize->add_section(
		'portfolio_posts_section',
		array(
			'panel' => 'portfolio_panel',
			'title' => esc_html__( 'Portfolio Page', 'evie' ),
		)
	);

	// Portfolio -> Portfolio Page -> Custom Content.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'portfolio_posts_page_content',
			array(
				'section'         => 'portfolio_posts_section',
				'label'           => esc_html__( 'Custom Content', 'evie' ),
				'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
			)
		)
	);

	// Portfolio -> Portfolio Page -> Portfolio Page Content.
	$wp_customize->add_setting(
		'portfolio_posts_page_content',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_page_content',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Displays Portfolio page content before the recent projects.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_page_content',
		array(
			'selector'         => '.post-type-archive-project .main-content > .entry-content',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Portfolio Page -> Filtering & Sorting.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'portfolio_posts_options_heading',
			array(
				'section'         => 'portfolio_posts_section',
				'label'           => esc_html__( 'Filtering & Sorting', 'evie' ),
				'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
			)
		)
	);

	// Portfolio -> Portfolio Page -> Filtering & Sorting - Show filters.
	$wp_customize->add_setting(
		'portfolio_posts_options_filter',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_options_filter',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Show filters.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_options_filter',
		array(
			'selector'            => '.post-type-archive-project .main-posts .posts-filters',
			'render_callback'     => 'evie_customizer_get_portfolio_options',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Portfolio Page -> Filtering & Sorting. - Show sort by options.
	$wp_customize->add_setting(
		'portfolio_posts_options_sortby',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_options_sortby',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Show sort by options.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_options_sortby',
		array(
			'selector'            => '.post-type-archive-project .main-posts .posts-filters',
			'render_callback'     => 'evie_customizer_get_portfolio_options',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Portfolio Page -> - Layout.
	$wp_customize->add_setting(
		'portfolio_posts_layout',
		array(
			'default'           => 'list',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_layout',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Layout', 'evie' ),
			'description'     => esc_html__( 'Choose portfolio posts layout.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				'large'     => esc_html__( 'Large', 'evie' ),
				'list'      => esc_html__( 'List', 'evie' ),
				'grid'      => esc_html__( 'Grid', 'evie' ),
				'waterfall' => esc_html__( 'Waterfall', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_layout',
		array(
			'selector'            => '.post-type-archive-project .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Portfolio Page -> Parallax.
	$wp_customize->add_setting(
		'portfolio_posts_parallax',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_parallax',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Parallax Columns', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_portfolio_parallax_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_parallax',
		array(
			'selector'            => '.post-type-archive-project .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Portfolio Page -> Style.
	$wp_customize->add_setting(
		'portfolio_posts_style',
		array(
			'default'           => 'card',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_style',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Style', 'evie' ),
			'description'     => esc_html__( 'The style of the item in the list.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				'card'         => esc_html__( 'Card', 'evie' ),
				'text-overlay' => esc_html__( 'Text Overlay', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_portfolio_style_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_style',
		array(
			'selector'            => '.post-type-archive-project .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Portfolio Page -> Hover Effect.
	$wp_customize->add_setting(
		'portfolio_posts_hover_effect',
		array(
			'default'           => 'none',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_hover_effect',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Hover Effect', 'evie' ),
			'description'     => esc_html__( 'The hover effect of the item in the list.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_portfolio_hover_effect_options(),
			'active_callback' => 'evie_customizer_is_portfolio_style_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_hover_effect',
		array(
			'selector'            => '.post-type-archive-project .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Portfolio Page -> Animation.
	$wp_customize->add_setting(
		'portfolio_posts_animation',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_animation',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Animation', 'evie' ),
			'description'     => esc_html__( 'Choose a transition for the post.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_portfolio_animation_options(),
			'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_animation',
		array(
			'selector'            => '.post-type-archive-project .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Portfolio Page -> Pagination.
	$wp_customize->add_setting(
		'portfolio_posts_pagination',
		array(
			'default'           => 'numbered',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_pagination',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Pagination', 'evie' ),
			'description'     => esc_html__( 'Choose posts pagination type.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_portfolio_pagination_options(),
			'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_pagination',
		array(
			'selector'            => '.post-type-archive-project .main-posts.posts-type-project .pagination',
			'render_callback'     => 'evie_customizer_portfolio_pagination',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Portfolio Page -> - Items per page.
	$wp_customize->add_setting(
		'portfolio_posts_per_page',
		array(
			'default'           => 10,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_number',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_per_page',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Items per page', 'evie' ),
			'description'     => esc_html__( 'Number of items per page.', 'evie' ),
			'type'            => 'number',
			'input_attrs'     => array(
				'min' => 1,
				'max' => 50,
			),
			'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_per_page',
		array(
			'selector'            => '.post-type-archive-project .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Portfolio Page -> Project Metadata.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'portfolio_posts_meta_heading',
			array(
				'section'         => 'portfolio_posts_section',
				'label'           => esc_html__( 'Project Metadata', 'evie' ),
				'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
			)
		)
	);

	// Portfolio -> Portfolio Page -> Publish Date.
	$wp_customize->add_setting(
		'portfolio_posts_date',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_date',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Publication date.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_date',
		array(
			'selector'         => '.post-type-archive-project .main-posts.posts-type-project .post .meta-date',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Portfolio Page -> Project category.
	$wp_customize->add_setting(
		'portfolio_posts_category',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_category',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Project category.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_category',
		array(
			'selector'         => '.post-type-archive-project .main-posts.posts-type-project .post .meta-category',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Portfolio Page -> Post Buttons.
	$wp_customize->add_setting(
		'portfolio_posts_buttons',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_buttons',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Comments, Likes and Share buttons.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_buttons',
		array(
			'selector'         => '.post-type-archive-project .main-posts.posts-type-project .post .entry-buttons',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Portfolio Page -> Author.
	$wp_customize->add_setting(
		'portfolio_posts_author',
		array(
			'default'           => 'all',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_posts_author',
		array(
			'section'         => 'portfolio_posts_section',
			'label'           => esc_html__( 'Author', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_portfolio_author_options(),
			'active_callback' => 'evie_customizer_is_view_with_portfolio_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_posts_author',
		array(
			'selector'         => '.post-type-archive-project .main-posts.posts-type-project .post .author',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	/**
	 * Portfolio -> Archive Pages.
	 */
	$wp_customize->add_section(
		'portfolio_archive_section',
		array(
			'panel' => 'portfolio_panel',
			'title' => esc_html__( 'Archive Pages', 'evie' ),
		)
	);

	// Portfolio -> Archive Pages -> Filtering & Sorting.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'portfolio_archive_options_heading',
			array(
				'section'         => 'portfolio_archive_section',
				'label'           => esc_html__( 'Filtering & Sorting', 'evie' ),
				'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
			)
		)
	);

	// Portfolio -> Archive Pages -> Filtering & Sorting - Show filters.
	$wp_customize->add_setting(
		'portfolio_archive_options_filter',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_options_filter',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Show filters.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_options_filter',
		array(
			'selector'            => 'body:not(.post-type-archive) .main-posts.posts-type-project .posts-filters',
			'render_callback'     => 'evie_customizer_get_portfolio_archive_options',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Archive Pages -> Filtering & Sorting - Show sort by options.
	$wp_customize->add_setting(
		'portfolio_archive_options_sortby',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_options_sortby',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Show sort by options.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_options_sortby',
		array(
			'selector'            => 'body:not(.post-type-archive) .main-posts.posts-type-project .posts-filters',
			'render_callback'     => 'evie_customizer_get_portfolio_archive_options',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Archive Pages -> - Layout.
	$wp_customize->add_setting(
		'portfolio_archive_layout',
		array(
			'default'           => 'list',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_layout',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Layout', 'evie' ),
			'description'     => esc_html__( 'Changes the projects layout.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				'large'     => esc_html__( 'Large', 'evie' ),
				'list'      => esc_html__( 'List', 'evie' ),
				'grid'      => esc_html__( 'Grid', 'evie' ),
				'waterfall' => esc_html__( 'Waterfall', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_layout',
		array(
			'selector'            => 'body:not(.post-type-archive) .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Portfolio Page -> Parallax.
	$wp_customize->add_setting(
		'portfolio_archive_parallax',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_parallax',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Parallax Columns', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_portfolio_archive_parallax_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_parallax',
		array(
			'selector'            => 'body:not(.post-type-archive) .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Archive Pages -> Style.
	$wp_customize->add_setting(
		'portfolio_archive_style',
		array(
			'default'           => 'card',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_style',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Style', 'evie' ),
			'description'     => esc_html__( 'The style of the item in the list.', 'evie' ),
			'type'            => 'select',
			'choices'         => array(
				'card'         => esc_html__( 'Card', 'evie' ),
				'text-overlay' => esc_html__( 'Text Overlay', 'evie' ),
			),
			'active_callback' => 'evie_customizer_is_portfolio_archive_style_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_style',
		array(
			'selector'            => 'body:not(.post-type-archive) .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Archive Pages -> Hover Effect.
	$wp_customize->add_setting(
		'portfolio_archive_hover_effect',
		array(
			'default'           => 'none',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_hover_effect',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Hover Effect', 'evie' ),
			'description'     => esc_html__( 'The hover effect of the item in the list.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_portfolio_hover_effect_options(),
			'active_callback' => 'evie_customizer_is_portfolio_archive_style_enabled',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_hover_effect',
		array(
			'selector'            => 'body:not(.post-type-archive) .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Archive Pages -> Animation.
	$wp_customize->add_setting(
		'portfolio_archive_animation',
		array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_animation',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Animation', 'evie' ),
			'description'     => esc_html__( 'Choose a transition for the post.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_portfolio_animation_options(),
			'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_animation',
		array(
			'selector'            => 'body:not(.post-type-archive) .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Archive Pages -> Pagination.
	$wp_customize->add_setting(
		'portfolio_archive_pagination',
		array(
			'default'           => 'numbered',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_pagination',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Pagination', 'evie' ),
			'description'     => esc_html__( 'Choose posts pagination type.', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_portfolio_pagination_options(),
			'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_pagination',
		array(
			'selector'            => 'body:not(.post-type-archive) .main-posts.posts-type-project .pagination',
			'render_callback'     => 'evie_customizer_portfolio_pagination',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Archive Pages -> - Items per page.
	$wp_customize->add_setting(
		'portfolio_archive_per_page',
		array(
			'default'           => 10,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_number',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_per_page',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Items per page', 'evie' ),
			'description'     => esc_html__( 'Number of items per page.', 'evie' ),
			'type'            => 'number',
			'input_attrs'     => array(
				'min' => 1,
				'max' => 50,
			),
			'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_per_page',
		array(
			'selector'            => 'body:not(.post-type-archive) .main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Archive Pages -> Project Metadata.
	$wp_customize->add_control(
		new Evie_Customize_Label_Control(
			$wp_customize,
			'portfolio_archive_meta_heading',
			array(
				'section'         => 'portfolio_archive_section',
				'label'           => esc_html__( 'Project Metadata', 'evie' ),
				'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
			)
		)
	);

	// Portfolio -> Archive Pages -> Publish Date.
	$wp_customize->add_setting(
		'portfolio_archive_date',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_date',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Publication date.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_date',
		array(
			'selector'         => 'body:not(.post-type-archive) .main-posts.posts-type-project .post .meta-date',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Archive Pages -> Project category.
	$wp_customize->add_setting(
		'portfolio_archive_category',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_category',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Project category.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_category',
		array(
			'selector'         => 'body:not(.post-type-archive) .main-posts.posts-type-project .post .meta-category',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Archive Pages -> Post Buttons.
	$wp_customize->add_setting(
		'portfolio_archive_buttons',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_buttons',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Comments, Likes and Share buttons.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_buttons',
		array(
			'selector'         => 'body:not(.post-type-archive) .main-posts.posts-type-project .post .entry-buttons',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Archive Pages -> Author.
	$wp_customize->add_setting(
		'portfolio_archive_author',
		array(
			'default'           => 'all',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_author',
		array(
			'section'         => 'portfolio_archive_section',
			'label'           => esc_html__( 'Author', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_portfolio_author_options(),
			'active_callback' => 'evie_customizer_is_view_with_portfolio_archive_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_archive_author',
		array(
			'selector'         => 'body:not(.post-type-archive) .main-posts.posts-type-project .post .author',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	/**
	 * Portfolio -> Single Project
	 */
	$wp_customize->add_section(
		'portfolio_single_post_section',
		array(
			'panel' => 'portfolio_panel',
			'title' => esc_html__( 'Single Project', 'evie' ),
		)
	);

	// Portfolio -> Single Project -> Publication date.
	$wp_customize->add_setting(
		'portfolio_single_post_date',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_single_post_date',
		array(
			'section'         => 'portfolio_single_post_section',
			'label'           => esc_html__( 'Publication date.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_project_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_single_post_date',
		array(
			'selector'         => '.single-project .single-entry .meta-date',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Single Project -> Project category.
	$wp_customize->add_setting(
		'portfolio_single_post_category',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_single_post_category',
		array(
			'section'         => 'portfolio_single_post_section',
			'label'           => esc_html__( 'Project category.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_project_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_single_post_category',
		array(
			'selector'         => '.single-project .single-entry .meta-categories',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Single Project -> Project tags.
	$wp_customize->add_setting(
		'portfolio_single_post_tags',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_single_post_tags',
		array(
			'section'         => 'portfolio_single_post_section',
			'label'           => esc_html__( 'Project tags.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_project_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_single_post_tags',
		array(
			'selector'         => '.single-project .single-entry .tags-links',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Single Project -> Project Buttons.
	$wp_customize->add_setting(
		'portfolio_single_post_buttons',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_single_post_buttons',
		array(
			'section'         => 'portfolio_single_post_section',
			'label'           => esc_html__( 'Comments, Likes and Share buttons.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_project_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_single_post_buttons',
		array(
			'selector'         => '.single-project .single-entry-footer .entry-buttons',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Single Project -> Author Information.
	$wp_customize->add_setting(
		'portfolio_single_post_author',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_single_post_author',
		array(
			'section'         => 'portfolio_single_post_section',
			'label'           => esc_html__( 'Author information.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_project_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_single_post_author',
		array(
			'selector'         => '.single-project .single-entry-footer .post-author',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Single Project -> Related Projects.
	$wp_customize->add_setting(
		'portfolio_single_post_related',
		array(
			'default'           => false,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_single_post_related',
		array(
			'section'         => 'portfolio_single_post_section',
			'label'           => esc_html__( 'Related Projects.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_project_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_single_post_related',
		array(
			'selector'         => '.single-project .related-posts',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Single Project -> Project Navigation.
	$wp_customize->add_setting(
		'portfolio_single_post_navigation',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_single_post_navigation',
		array(
			'section'         => 'portfolio_single_post_section',
			'label'           => esc_html__( 'Project Navigation.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_single_project_options',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'single_post_navigation',
		array(
			'selector'         => '.single-project .post-navigation',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	/**
	 * Portfolio -> Quick View.
	 */
	$wp_customize->add_section(
		'portfolio_quick_view_section',
		array(
			'panel' => 'portfolio_panel',
			'title' => esc_html__( 'Portfolio Quick View', 'evie' ),
		)
	);

	// Portfolio -> Quick View -> Enable Quick View.
	$wp_customize->add_setting(
		'portfolio_quick_view_enable',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_quick_view_enable',
		array(
			'section'         => 'portfolio_quick_view_section',
			'label'           => esc_html__( 'Enable Quick View.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_view_with_portfolio_quick_view_options',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'portfolio_quick_view_enable',
		array(
			'selector'            => '.main-posts.posts-type-project',
			'render_callback'     => 'evie_customizer_get_portfolio',
			'container_inclusive' => true,
		)
	);

	// Portfolio -> Quick View -> Publish Date.
	$wp_customize->add_setting(
		'portfolio_quick_view_date',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_quick_view_date',
		array(
			'section'         => 'portfolio_quick_view_section',
			'label'           => esc_html__( 'Publication date.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_portfolio_quick_view_enable',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_quick_view_date',
		array(
			'selector'         => '.quick-view-content .meta-date',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Quick View -> Project category.
	$wp_customize->add_setting(
		'portfolio_quick_view_category',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_quick_view_category',
		array(
			'section'         => 'portfolio_quick_view_section',
			'label'           => esc_html__( 'Project category.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_portfolio_quick_view_enable',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_quick_view_category',
		array(
			'selector'         => '.quick-view-content .meta-categories',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Quick View -> Project Buttons.
	$wp_customize->add_setting(
		'portfolio_quick_view_buttons',
		array(
			'default'           => true,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'portfolio_quick_view_buttons',
		array(
			'section'         => 'portfolio_quick_view_section',
			'label'           => esc_html__( 'Comments, Likes and Share buttons.', 'evie' ),
			'type'            => 'checkbox',
			'active_callback' => 'evie_customizer_is_portfolio_quick_view_enable',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_quick_view_buttons',
		array(
			'selector'         => '.quick-view-content .entry-buttons',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

	// Portfolio -> Portfolio Page -> Author.
	$wp_customize->add_setting(
		'portfolio_quick_view_author',
		array(
			'default'           => 'all',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'evie_customize_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'portfolio_quick_view_author',
		array(
			'section'         => 'portfolio_quick_view_section',
			'label'           => esc_html__( 'Author', 'evie' ),
			'type'            => 'select',
			'choices'         => evie_customizer_portfolio_author_options(),
			'active_callback' => 'evie_customizer_is_portfolio_quick_view_enable',
		)
	);

	// Show an Edit icon to easily access the settings panel.
	$wp_customize->selective_refresh->add_partial(
		'portfolio_quick_view_author',
		array(
			'selector'         => '.quick-view-content .author',
			'render_callback'  => '',
			'fallback_refresh' => false,
		)
	);

}

add_action( 'customize_register', 'evie_customizer_register_portfolio_settings', 20 );
