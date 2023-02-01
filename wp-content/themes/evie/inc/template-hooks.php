<?php
/**
 * Additional functions that enhance the theme by hooking into WordPress.
 *
 * @package Evie
 * @version 1.0.0
 */

/**
 * Disable requests to wp.org repository for this theme.
 *
 * @since 1.0.1
 *
 * @param  array  $r Existing request arguments.
 * @param  string $url Request URL.
 * @return array Amended request arguments.
 */
function evie_disable_auto_update( $r, $url ) {

	// If it's not a theme update request, bail.
	if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
		return $r;
	}

	$themes = json_decode( $r['body']['themes'] );
	$child  = get_option( 'stylesheet' );
	unset( $themes->themes->$child );
	$r['body']['themes'] = wp_json_encode( $themes );

	return $r;
}

add_filter( 'http_request_args', 'evie_disable_auto_update', 5, 2 );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array An array list of classes for the body element.
 */
function evie_add_body_classes( $classes = array() ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( is_singular() ) {
		// Adds `singular` to singular pages.
		$classes[] = 'singular';

		if ( ! is_page() ) {
			$layout = evie_get_post_layout();
			if ( ! empty( $layout ) ) {
				$classes[] = 'single-layout-' . $layout;
			}
		}
	} else {
		// Adds `hfeed` to non singular pages.
		$classes[] = 'hfeed';
	}

	if ( true === evie_is_signup_page() ) {
		$classes[] = 'signup-page';
	} elseif ( true === evie_is_transparent_menu() ) {
		$classes[] = 'transparent-menu';

		$menu_text_style = evie_get_menu_text_mode();
		if ( ! empty( $menu_text_style ) ) {
			$classes[] = 'menu-text-' . $menu_text_style;
		}
	}

	// Add default menu class, this class will be changed automatically by JavaScript after loading.
	if ( ! wp_is_mobile() ) {
		$classes[] = 'desktop-menu';
	} else {
		$classes[] = 'mobile-menu';
	}

	$nav_type = get_theme_mod( 'nav_type', '' );
	if ( empty( $nav_type ) ) {
		$nav_type = 'top';
	}

	$classes[] = $nav_type . '-menu';

	if ( true === get_theme_mod( 'nav_sticky_menu', true ) ) {
		$classes[] = 'has-sticky-menu';
	}

	$loader = get_theme_mod( 'loader', '' );
	if ( ! empty( $loader ) ) {
		$classes[] = 'has-loader';
	}

	if ( ( true === get_theme_mod( 'nav_sidebar_button', true ) && is_active_sidebar( 'main' ) ) || is_customize_preview() ) {
		$classes[] = 'has-sidebar';
	}

	if ( is_active_sidebar( 'menu' ) || is_customize_preview() ) {
		$classes[] = 'has-menu-widgets';
	}

	if ( true !== evie_has_menu() ) {
		$classes[] = 'menu-hidden';
	}

	if ( true !== evie_has_header() && ! ( ! is_post_type_archive() && is_archive() ) ) {
		$classes[] = 'header-hidden';
	}

	if ( true !== evie_has_footer() ) {
		$classes[] = 'footer-hidden';
	}

	if ( true !== evie_has_footer_gap() ) {
		$classes[] = 'has-no-footer-gap';
	}

	if ( evie_has_user_color_support() ) {
		$classes[] = 'has-user-color-support';
	}

	$classes[] = 'has-scheme-' . evie_get_color_scheme();

	// Add class if we're viewing the Customizer for easier styling of theme options.
	if ( is_customize_preview() ) {
		$classes[] = 'customizer';
	}

	return $classes;
}

add_filter( 'body_class', 'evie_add_body_classes' );

/**
 * Adds custom classes to the admin body classes list.
 *
 * @param string $classes Space-separated list of CSS classes.
 * @return string New CSS classes.
 */
function evie_dark_mode_admin_body_class( $classes ) {
	if ( evie_has_user_color_support() ) {
		$classes .= ' has-user-color-support';
	}

	$scheme = evie_get_color_scheme();

	$classes .= ' has-scheme-' . $scheme;
	if ( 'dark' === $scheme ) {
		$classes .= ' is-dark-theme';
	}

	return $classes;
}

add_filter( 'admin_body_class', 'evie_dark_mode_admin_body_class' );

/**
 * Adds a theme color scheme to the editor classes.
 *
 * @param array $settings The editor settings.
 * @return array New editor settings.
 */
function evie_dark_mode_classic_editor_settings( $settings = array() ) {
	// Only when Tinymce is enabled.
	if ( isset( $settings['tinymce'] ) ) {

		$classes = array();
		if ( evie_has_user_color_support() ) {
			$classes[] = 'has-user-color-support';
		}

		$classes[] = 'has-scheme-' . evie_get_color_scheme();

		if ( ! is_array( $settings['tinymce'] ) ) {
			$settings['tinymce'] = array();
		}

		if ( ! isset( $settings['tinymce']['body_class'] ) ) {
			$settings['tinymce']['body_class'] = implode( ' ', $classes );
		} else {
			$settings['tinymce']['body_class'] .= ' ' . implode( ' ', $classes );
		}
	}

	return $settings;
}

add_filter( 'wp_editor_settings', 'evie_dark_mode_classic_editor_settings' );

/**
 * Adds information to the Privacy Policy.
 *
 * This information will be shown under the Policy Guide tab in Settings -> Privacy.
 */
function evie_dark_mode_add_privacy_content() {
	$content = '<p class="privacy-policy-tutorial">' . esc_html__( 'Evie theme uses LocalStorage when your site allows users to switch schemes.', 'evie' ) . '</p>'
			. '<strong class="privacy-policy-tutorial">' . esc_html__( 'Suggested text:', 'evie' ) . '</strong> '
			. __( '<h2>Color scheme preference</h2><p>This website uses LocalStorage to save the setting when the Dark Mode support is turned on or off.<br /> LocalStorage is only used when a visitor (who isn’t logged in) clicks on the dark mode toggle button.</br /> For a logged in user, the setting will be saved to the user’s metadata in the database instead.<br /><br /> Unlike Cookies, data stored in LocalStorage is never transferred to the server.</p>', 'evie' );
	wp_add_privacy_policy_content( 'Evie', wp_kses_post( wpautop( $content, false ) ) );
}

add_action( 'admin_init', 'evie_dark_mode_add_privacy_content' );

/**
 * Includes a skip to content link at the top of the page so that users can bypass the menu.
 */
function evie_skip_link() {
	echo '<a class="skip-link screen-reader-text" href="#site-content">' . esc_html__( 'Skip to the content', 'evie' ) . '</a>';
}

add_action( 'wp_body_open', 'evie_skip_link', 5 );

/**
 * Adds a loading animation to the page.
 */
function evie_loader() {
	$loader = get_theme_mod( 'loader', '' );
	if ( ! empty( $loader ) ) {
		$attrs = array();

		$styles = array();

		$background_color = get_theme_mod( 'loader_overlay', '' );
		if ( ! empty( $background_color ) ) {
			$styles[] = 'background-color:' . $background_color;
		}

		$background_opacity = get_theme_mod( 'loader_overlay_opacity', 75 );
		if ( $background_opacity > 0 ) {
			$styles[] = 'opacity:' . round( floatval( $background_opacity ) / 100.0, 2 );
		}

		if ( ! empty( $styles ) ) {
			$attrs['style'] = implode( ';', $styles );
		}

		$classes = array(
			'content-loader',
			'is-loading',
		);

		if ( ! empty( $styles ) ) {
			$classes[] = 'has-background-overlay';
		}

		echo '<div id="site-loader" class="' . esc_attr( implode( ' ', $classes ) ) . '">';
		echo '	<div class="loader-background"' . evie_attributes( $attrs, true, false ) . '></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo '	<div class="loading-icon loader-' . esc_attr( $loader ) . '">';
		get_template_part( 'template-parts/loader/' . $loader );
		echo '	</div><!-- .loading-icon -->';

		echo '</div><!-- #site-loader -->';
	}
}

add_action( 'wp_body_open', 'evie_loader' );

/**
 * Adds a Dark Mode toggle button to the list of extra menu items.
 *
 * @param array $items An array of the extra menu items.
 * @return array An array of the extra menu items.
 */
function evie_dark_mode_menu_button( $items = array() ) {
	// Only when the site allows users to change their preferences, or currently in Customize mode.
	if ( evie_has_user_color_support() || is_customize_preview() ) {
		$items['dark-mode'] = '<a class="dark-mode-button" href="#" aria-label="' . esc_attr__( 'Toggle Dark Mode', 'evie' ) . '"><i class="evie-ico-dark"></i></a>';
	}
	return $items;
}

add_filter( 'evie_extra_menu_items', 'evie_dark_mode_menu_button' );

/**
 * Adds Dark Mode setting fields at the end of the 'Personal Options' settings table on the user editing screen.
 *
 * @param WP_User $profile_user The current WP_User object.
 */
function evie_dark_mode_user_settings( $profile_user ) {
	if ( evie_has_user_color_support() ) :
		$color = evie_get_user_color_scheme( $profile_user->ID );
		if ( empty( $color ) ) {
			$color = 'auto';
		}
		?>
	<tr class="evie-user-dark-mode-wrap">
		<th scope="row">
			<label for="evie-dark-mode"><?php echo esc_html__( 'Dark Mode', 'evie' ); ?></label>
		</th>
		<td>
			<p>
				<label for="evie-dark-mode-off">
					<input name="evie_color" type="radio" id="evie-dark-mode-off" value="light"<?php checked( 'light', $color ); ?> />
					<?php echo esc_html__( 'Off', 'evie' ); ?>
				</label>
			</p>
			<p>
				<label for="evie-dark-mode-on">
					<input name="evie_color" type="radio" id="evie-dark-mode-on" value="dark"<?php checked( 'dark', $color ); ?> />
					<?php echo esc_html__( 'On', 'evie' ); ?>
				</label>
			</p>
			<p>
				<label for="evie-dark-mode-auto">
					<input name="evie_color" type="radio" id="evie-dark-mode-auto" value="auto"<?php checked( 'auto', $color ); ?> />
					<?php echo esc_html__( 'Auto (We will automatically adjust the appearance based on your device settings)', 'evie' ); ?>				
				</label>
			</p>
		</td>
	</tr>
		<?php
	endif;
}

add_action( 'personal_options', 'evie_dark_mode_user_settings' );

/**
 * Updates the user color scheme preference.
 *
 * @param int $user_id The user ID.
 */
function evie_user_dark_mode_update( $user_id = 0 ) {
	$color = isset( $_POST['evie_color'] ) ? sanitize_key( wp_unslash( $_POST['evie_color'] ) ) : 'auto'; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	evie_update_user_color_scheme( $user_id, $color );
}

add_action( 'personal_options_update', 'evie_user_dark_mode_update' );

add_action( 'edit_user_profile_update', 'evie_user_dark_mode_update' );

/**
 * Processes the user color scheme setting.
 */
function evie_color_scheme_process_request() {

	check_ajax_referer( 'evie_ajax', 'ajaxNonce' );

	$color = isset( $_POST['evieColor'] ) ? sanitize_key( wp_unslash( $_POST['evieColor'] ) ) : 'auto';

	$user_id = get_current_user_id();
	if ( $user_id > 0 && evie_has_user_color_support() ) {
		evie_update_user_color_scheme( $user_id, $color );

		wp_send_json(
			array(
				'status'  => 200,
				'message' => esc_html__( 'The color scheme has been set.', 'evie' ),
			)
		);
	} else {
		wp_send_json(
			array(
				'status'  => 401,
				'message' => esc_html__( 'You are not allowed to change the color scheme.', 'evie' ),
			)
		);
	}
}

add_action( 'wp_ajax_evie_set_color_scheme', 'evie_color_scheme_process_request' );

add_action( 'wp_ajax_nopriv_evie_set_color_scheme', 'evie_color_scheme_process_request' );

/**
 * Adds a wrapper start tag to the login header.
 */
function evie_login_header() {
	echo '<div class="evie-login-wrapper">
        	<div class="evie-login-header">';
	echo '		<h1><a href="' . esc_attr( evie_login_logo_url() ) . '">' . evie_login_logo() . '</a></h1>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '		<p>' . esc_html( get_bloginfo( 'description', 'display' ) ) . '</p>
        </div>';
}

add_action( 'login_header', 'evie_login_header' );

/**
 * Adds a wrapper start tag to the login header.
 */
function evie_login_footer() {
	echo '</div><!-- .evie-login-wrapper -->';
}

add_action( 'login_footer', 'evie_login_footer' );

/**
 * Filters the login logo URL.
 *
 * @return string The new login logo URL.
 */
function evie_login_logo_url() {
	return home_url();
}

add_filter( 'login_headerurl', 'evie_login_logo_url' );

/**
 * Filters the login logo image.
 *
 * @return string Custom logo markup.
 */
function evie_login_logo() {
	$html = '';

	$custom_logo_id = absint( get_theme_mod( 'light_logo', 0 ) );

	if ( $custom_logo_id ) {
		$custom_logo_attr = array(
			'class'   => 'light-logo',
			'loading' => false,
		);

		$image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
		if ( empty( $image_alt ) ) {
			$custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
		}

		/** This filter is documented in inc/template-functions.php */
		$custom_logo_attr = apply_filters( 'evie_get_light_logo_image_attributes', $custom_logo_attr, $custom_logo_id, 0 );

		$html = wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr );
	}

	return $html;
}

add_filter( 'login_headertext', 'evie_login_logo' );

/**
 * Returns whether the current page is the registration page.
 *
 * @return bool Whether the current page is the registration page.
 */
function evie_is_signup_page() {
	return isset( $GLOBALS['evie_is_signup_page'] ) && $GLOBALS['evie_is_signup_page'];
}

/**
 * Sets whether the current page is the registration page.
 */
function evie_signup_page() {
	$GLOBALS['evie_is_signup_page'] = true;
}

add_action( 'signup_header', 'evie_signup_page' );

/**
 * Filters the Custom Fonts available for this theme.
 *
 * @param array $fonts An array list of fonts available for this theme.
 * @return array An array list of fonts available for this theme.
 */
function evie_custom_fonts( $fonts = array() ) {
	if ( function_exists( 'flextension_fonts' ) ) {
		$custom_fonts = flextension_fonts();
		if ( ! empty( $custom_fonts ) ) {
			$fonts = array_merge( $fonts, $custom_fonts );
		}
	}
	return $fonts;
}

add_filter( 'evie_fonts', 'evie_custom_fonts' );

/**
 * Removes Block editor assets from Classic editor then add Classic editor assets instead.
 *
 * @param string[] $stylesheets Array of URLs of stylesheets to be applied to the editor.
 */
function evie_classic_editor_stylesheets( $stylesheets = array() ) {

	$index = array_search( get_theme_file_uri( 'assets/css/editor.css' ), $stylesheets, true );
	if ( false !== $index ) {
		unset( $stylesheets[ $index ] );
	}

	$stylesheets[] = get_theme_file_uri( 'assets/css/classic-editor.css' );

	return $stylesheets;
}

add_filter( 'editor_stylesheets', 'evie_classic_editor_stylesheets' );

/**
 * Removes hentry class from the page and adds custom class 'entry' to the array of posts classes.
 *
 * @param string[] $classes An array of post class names.
 * @param string[] $class   An array of additional class names added to the post.
 * @param int      $post_id The post ID.
 * @return string[] $classes An array of post class names.
 */
function evie_get_post_classes( $classes, $class, $post_id ) {
	$classes[] = 'entry';

	if ( is_single( $post_id ) ) {
		$classes[] = 'single-entry';
	}

	if ( 'page' === get_post_type( $post_id ) ) {
		$classes = array_diff( $classes, array( 'hentry' ) );
	}

	return $classes;
}

add_filter( 'post_class', 'evie_get_post_classes', 10, 3 );

/**
 * Adds a custom query var, filter into WordPress public query variables.
 *
 * @param array $query_vars An array of allowed query variable names.
 * @return array An array of allowed query variable names.
 */
function evie_add_query_vars( $query_vars = array() ) {
	$query_vars[] = 'filter';
	return $query_vars;
}

add_filter( 'query_vars', 'evie_add_query_vars' );

/**
 * Overrides WP query on the Author page.
 *
 * @since 1.0.3
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function evie_parse_query( $query ) {
	if ( ! is_admin() && $query->is_main_query() && true === $query->is_author() ) {
		$query->is_category = false;
		$query->is_tag      = false;
		$query->is_tax      = false;
	}
}

add_action( 'parse_query', 'evie_parse_query' );

/**
 * Filters the posts for logged in user by following.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function evie_filter_posts( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {
		if ( 'following' === $query->get( 'filter' ) && function_exists( 'flextension_author_get_following' ) ) {
			// Display only posts from authors current user follow.
			$authors = flextension_author_get_following();
			if ( empty( $authors ) ) {
				$authors = array( 0 );
			}
			$query->set( 'author__in', $authors );
		}
	}
}

add_action( 'pre_get_posts', 'evie_filter_posts' );

/**
 * Sets the list of custom logo image attributes.
 *
 * @param array $custom_logo_attr Custom logo image attributes.
 * @param int   $custom_logo_id   Custom logo attachment ID.
 * @return array Custom logo image attributes.
 */
function evie_custom_logo_image_attributes( $custom_logo_attr = array(), $custom_logo_id = 0 ) {

	$custom_logo_retina_id = absint( get_theme_mod( 'custom_logo_retina', 0 ) );

	if ( 0 !== $custom_logo_retina_id ) {
		$srcsets = array();

		$srcsets[] = wp_get_attachment_image_url( $custom_logo_id, 'full' ) . ' 1x';

		$srcsets[] = wp_get_attachment_image_url( $custom_logo_retina_id, 'full' ) . ' 2x';

		$custom_logo_attr['srcset'] = implode( ', ', $srcsets );
	}

	$custom_logo_attr['data-lazyload'] = 'disabled';

	return $custom_logo_attr;
}

add_filter( 'get_custom_logo_image_attributes', 'evie_custom_logo_image_attributes', 10, 2 );

/**
 * Filters the arguments for a single nav menu item.
 *
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param WP_Post  $item  Menu item data object.
 * @param int      $depth Depth of menu item. Used for padding.
 */
function evie_nav_menu_item_args( $args, $item, $depth ) {
	if ( 0 === $depth && 'split-menu with-counters' === $args->menu_class ) {
		$args->link_after = '</span><span class="menu-arrow-icon"></span>';
	}

	return $args;
}

add_filter( 'nav_menu_item_args', 'evie_nav_menu_item_args', 10, 3 );

/**
 * Adds custom image size names.
 *
 * @param array $sizes An array list of the image sizes.
 * @return array An array list of the image sizes.
 */
function evie_add_image_size_names( $sizes ) {
	return array_merge(
		$sizes,
		array(
			'evie-square'    => esc_html__( 'Square', 'evie' ),
			'evie-wide'      => esc_html__( 'Wide', 'evie' ),
			'evie-fullwidth' => esc_html__( 'Full Width', 'evie' ),
		)
	);
}

add_filter( 'image_size_names_choose', 'evie_add_image_size_names' );

/**
 * Sets the excerpt lenght.
 *
 * @param int $length The lenght of post excerpt.
 * @return int The new value of the excerpt lenght.
 */
function evie_excerpt_length( $length ) {
	return get_option( 'blog_excerpt_length', $length );
}

add_filter( 'excerpt_length', 'evie_excerpt_length' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ...
 *
 * @param string $more_string Default 'more' string.
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function evie_get_excerpt_more( $more_string = '' ) {
	$more_string = ' &hellip; ';
	return $more_string;
}

add_filter( 'excerpt_more', 'evie_get_excerpt_more' );

/**
 * Gets the anchor tag attributes for the previous posts page link.
 *
 * @param string $attributes Attributes for the anchor tag.
 * @return string Attributes for the anchor tag.
 */
function evie_previous_posts_link_attribute( $attributes = '' ) {
	$attributes = 'class="prev"';
	return $attributes;
}

add_filter( 'previous_posts_link_attributes', 'evie_previous_posts_link_attribute' );

/**
 * Gets the anchor tag attributes for the next posts page link.
 *
 * @param string $attributes Attributes for the anchor tag.
 * @return string Attributes for the anchor tag.
 */
function evie_next_posts_link_attribute( $attributes = '' ) {
	$attributes = 'class="next"';
	return $attributes;
}

add_filter( 'next_posts_link_attributes', 'evie_next_posts_link_attribute' );

/**
 * Adds Next and Previous links to paginated posts.
 *
 * @global int $page
 * @global int $numpages
 *
 * @param array $args An array of arguments for page links for paginated posts.
 * @return array An array of arguments for page links for paginated posts.
 */
function evie_link_pages_args( $args = array() ) {
	global $page, $numpages;

	$prev_link = '';
	$prev      = $page - 1;
	if ( $prev > 0 ) {
		$url       = evie_link_page_url( $prev );
		$prev_link = sprintf(
			'<a href="%1$s" class="page-numbers prev"><i class="evie-ico-left"></i><span>%2$s</span></a> ',
			esc_url( $url ),
			esc_html__( 'Prev.', 'evie' )
		);
	}

	$args['before'] = '<nav class="navigation pagination post-pagination numbered-pagination"><div class="nav-links">' . $prev_link;

	$next_link = '';
	$next      = $page + 1;
	if ( $next <= $numpages ) {
		$url       = evie_link_page_url( $next );
		$next_link = sprintf(
			'<a href="%1$s" class="page-numbers next"><span>%2$s</span><i class="evie-ico-right"></i></a> ',
			esc_url( $url ),
			esc_html__( 'Next', 'evie' )
		);
	}

	$args['after'] = $next_link . '</div></nav>';

	return $args;
}

add_filter( 'wp_link_pages_args', 'evie_link_pages_args' );

/**
 * Sets the arguments used in retrieving the comment list.
 *
 * @see wp_list_comments()
 *
 * @param string|array $args {
 *     Optional. Formatting options.
 *
 *     @type object $walker            Instance of a Walker class to list comments. Default null.
 *     @type int    $max_depth         The maximum comments depth. Default empty.
 *     @type string $style             The style of list ordering. Default 'ul'. Accepts 'ul', 'ol'.
 *     @type string $callback          Callback function to use. Default null.
 *     @type string $end-callback      Callback function to use at the end. Default null.
 *     @type string $type              Type of comments to list.
 *                                     Default 'all'. Accepts 'all', 'comment', 'pingback', 'trackback', 'pings'.
 *     @type int    $page              Page ID to list comments for. Default empty.
 *     @type int    $per_page          Number of comments to list per page. Default empty.
 *     @type int    $avatar_size       Height and width dimensions of the avatar size. Default 32.
 *     @type bool   $reverse_top_level Ordering of the listed comments. If true, will display newest comments first.
 *     @type bool   $reverse_children  Whether to reverse child comments in the list. Default null.
 *     @type string $format            How to format the comments list.
 *                                     Default 'html5' if the theme supports it. Accepts 'html5', 'xhtml'.
 *     @type bool   $short_ping        Whether to output short pings. Default false.
 *     @type bool   $echo              Whether to echo the output or return it. Default true.
 * }
 */
function evie_list_comments_args( $args ) {
	$args['avatar_size'] = 32;
	if ( empty( $args['callback'] ) ) {
		$args['callback'] = 'evie_comment';
	}
	$args['style']      = 'ol';
	$args['short_ping'] = true;
	return $args;
}

add_filter( 'wp_list_comments_args', 'evie_list_comments_args' );

/**
 * Adds the anchor tag attributes for the next comments page link.
 *
 * @param string $attributes Attributes for the anchor tag.
 * @return string Attributes for the anchor tag.
 */
function evie_next_comments_link_attributes( $attributes ) {
	$attributes .= ' class="next"';
	return $attributes;
}

add_filter( 'next_comments_link_attributes', 'evie_next_comments_link_attributes' );

/**
 * Adds the anchor tag attributes for the previous comments page link.
 *
 * @param string $attributes Attributes for the anchor tag.
 * @return string Attributes for the anchor tag.
 */
function evie_previous_comments_link_attributes( $attributes ) {
	$attributes .= ' class="prev"';
	return $attributes;
}

add_filter( 'previous_comments_link_attributes', 'evie_previous_comments_link_attributes' );

/**
 * Sets the comments link title attributes for display.
 *
 * @param string $attributes The comments link attributes. Default empty.
 * @return string The comments link attributes.
 */
function evie_comments_popup_link_attributes( $attributes = '' ) {
	$attributes = ' title="' . esc_attr( esc_html__( 'Leave a comment', 'evie' ) ) . '"';
	return $attributes;
}

add_filter( 'comments_popup_link_attributes', 'evie_comments_popup_link_attributes' );

/**
 * Sets the comment form default arguments.
 *
 * @param array $args The comment form arguments.
 * @return array The comment form arguments.
 */
function evie_comment_form_args( $args = array() ) {
	$args['title_reply']   = esc_html__( 'Leave a Comment', 'evie' );
	$args['comment_field'] = '<p class="comment-form-comment evie-text-field evie-text-area evie-floating-label">
								<label for="comment">' . _x( 'Comment', 'noun', 'evie' ) . ' <span class="required">*</span></label>
								<textarea class="evie-input" id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea>
							</p>';
	return $args;
}

add_filter( 'comment_form_defaults', 'evie_comment_form_args' );

/**
 * Defines the default comment form fields.
 *
 * @param array $fields The default comment fields.
 * @return array The default comment fields.
 */
function evie_comment_form_fields( $fields = array() ) {
	$commenter = wp_get_current_commenter();

	$req      = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$html_req = ( $req ? " required='required'" : '' );

	$fields['author'] = '<p class="comment-form-author evie-text-field evie-floating-label">
							<label for="author">' . esc_html__( 'Name', 'evie' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>
							<input class="evie-input" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . $html_req . ' />
						</p>';

	$fields['email'] = '<p class="comment-form-email evie-text-field evie-floating-label">
							<label for="email">' . esc_html__( 'Email', 'evie' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>
							<input class="evie-input" id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . $html_req . ' />
						</p>';

	$fields['url'] = '<p class="comment-form-url evie-text-field evie-floating-label">
						<label for="url">' . esc_html__( 'Website', 'evie' ) . '</label>
						<input class="evie-input" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" />
					  </p>';

	return $fields;
}

add_filter( 'comment_form_default_fields', 'evie_comment_form_fields' );


/**
 * Returns the arguments for the Navigation Menu widget.
 *
 * @param array $nav_menu_args {
 *     An array of arguments passed to wp_nav_menu() to retrieve a navigation menu.
 *
 *     @type callable|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
 *     @type mixed         $menu        Menu ID, slug, or name.
 * }
 * @return array An array of arguments for the Navigation Menu widget.
 */
function evie_widget_nav_menu_vertical( $nav_menu_args = array() ) {
	$nav_menu_args['menu_class'] = 'vertical-menu';
	$nav_menu_args['container']  = is_customize_preview() ? 'div' : false;
	$nav_menu_args['after']      = '<button class="sub-menu-button" aria-label="' . esc_attr( esc_html__( 'Open Submenu', 'evie' ) ) . '"></button>';
	return $nav_menu_args;
}

add_filter( 'widget_nav_menu_args', 'evie_widget_nav_menu_vertical' );

add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

add_filter( 'use_widgets_block_editor', '__return_false' );

/**
 * Evie Theme actions & filters.
 */

/**
 * Sets the list of custom logo image attributes for the Light version.
 *
 * @param array $custom_logo_attr Custom logo image attributes.
 * @param int   $custom_logo_id   Custom logo attachment ID.
 * @return array Custom logo image attributes.
 */
function evie_light_logo_image_attributes( $custom_logo_attr = array(), $custom_logo_id = 0 ) {

	$custom_logo_retina_id = absint( get_theme_mod( 'light_logo_retina', 0 ) );

	if ( 0 !== $custom_logo_retina_id ) {
		$srcsets = array();

		$srcsets[] = wp_get_attachment_image_url( $custom_logo_id, 'full' ) . ' 1x';

		$srcsets[] = wp_get_attachment_image_url( $custom_logo_retina_id, 'full' ) . ' 2x';

		$custom_logo_attr['srcset'] = implode( ', ', $srcsets );
	}

	$custom_logo_attr['data-lazyload'] = 'disabled';

	return $custom_logo_attr;
}

add_filter( 'evie_get_light_logo_image_attributes', 'evie_light_logo_image_attributes', 10, 2 );

/**
 * Filters the posts arguments for a search page.
 *
 * @param array $args An array of posts arguments.
 * @return array New array of posts arguments.
 */
function evie_search_posts_args( $args ) {

	if ( is_search() || ( isset( $args['query'] ) && $args['query']->is_search() ) ) {
		$args['layout']        = 'list';
		$args['style']         = '';
		$args['hover_effect']  = 'none';
		$args['animation']     = '';
		$args['show_category'] = false;
		$args['show_author']   = false;
		$args['show_date']     = false;
		$args['show_buttons']  = false;
		$args['pagination']    = 'numbered';
	}

	return $args;
}

add_filter( 'evie_posts_args', 'evie_search_posts_args' );

/**
 * Sets the author cover background image to the author's page header.
 *
 * @param array $args An array of the arguments for the header.
 * @return array An array of the arguments for the header.
 */
function evie_author_page_header( $args = array() ) {
	// If Author Cover Image is available.
	if ( 'archive' === $args['layout'] && function_exists( 'flextension_author_cover_image_id' ) && is_author() && get_query_var( 'author' ) ) {
		$author_id = get_query_var( 'author' );
		$image_id  = flextension_author_cover_image_id( $author_id );
		if ( $image_id > 0 ) {
			$image_url = wp_get_attachment_image_url( $image_id, 'fullwidth' );
			if ( ! empty( $image_url ) ) {
				$args['background']       = 'image';
				$args['background_image'] = $image_url;

				/**
				 * Background image styles.
				 *
				 * @since 1.0.1
				 */
				if ( function_exists( 'flextension_author_cover_image_styles' ) ) {
					$styles = flextension_author_cover_image_styles( $author_id );
					if ( ! empty( $styles['background-position'] ) ) {
						$args['background_position'] = $styles['background-position'];
					}
				}
			}
		}
	}
	return $args;
}

add_filter( 'evie_page_header_args', 'evie_author_page_header' );

/**
 * Prints out the filter options for the list of posts.
 *
 * @param array $options An array of options.
 */
function evie_posts_filter_options( $options = array() ) {

	$taxonomies = get_object_taxonomies( $options['post_type'], 'objects' );
	$taxonomies = wp_filter_object_list(
		$taxonomies,
		array(
			'publicly_queryable' => true,
		)
	);

	/**
	 * Filters the array list of taxonomies.
	 *
	 * @param array $taxonomies An array list of taxonomies.
	 */
	$taxonomies = apply_filters( 'evie_posts_filter_taxonomies', $taxonomies );

	if ( ! empty( $taxonomies ) ) {

		if ( ! empty( $options['taxonomy'] ) && isset( $taxonomies[ $options['taxonomy'] ] ) ) {
			unset( $taxonomies[ $options['taxonomy'] ] );
		}

		if ( ! is_search() ) {
			$taxonomies = wp_list_sort( $taxonomies, 'label' );
		}

		$link_base = evie_get_link_base();
		$columns   = 0;
		$output    = '';

		foreach ( $taxonomies as $taxonomy ) {

			$terms = get_terms(
				array(
					'taxonomy' => $taxonomy->name,
				)
			);

			if ( is_wp_error( $terms ) ) {
				$terms = array();
			}

			if ( ! empty( $terms ) ) {

				$columns++;

				$output .= '<div class="filter-tax">';
				$output .= '<h2>' . esc_html( $taxonomy->label ) . '</h2>';
				$output .= '<ul class="filter-terms filter-type-' . esc_attr( $taxonomy->name ) . '">';

				foreach ( $terms as $term ) {
					$output .= evie_filter_term_link( $taxonomy, $term, $link_base );
				}

				$output .= '</ul>';
				$output .= '</div>';
			}
		}

		$columns = min( $columns, 4 );

		$class = '';

		if ( $columns > 1 ) {
			$class = ' ' . evie_get_grid_columns_class( $columns );
		}

		echo '<div class="filter-taxonomies' . esc_attr( $class ) . '">' . $output . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="filter-buttons"><a href="' . esc_url( $options['all_link'] ) . '"><i class="evie-ico-cancel"></i>' . esc_html__( 'Clear all filters', 'evie' ) . '</a></div>';
	}
}

add_action( 'evie_posts_filter_options', 'evie_posts_filter_options' );

/**
 * Displays the header for the main content.
 */
function evie_content_header() {
	if ( is_single() ) {
		return;
	}

	evie_page_header();
}

add_action( 'evie_before_content', 'evie_content_header' );

/**
 * Inserts the page content before the posts on the blog page.
 */
function evie_posts_page_content() {

	if ( 'page' === get_option( 'show_on_front' ) && is_home() && ( true === get_theme_mod( 'blog_posts_page_content', false ) || is_customize_preview() ) ) {
		$content = get_the_content( null, false, absint( get_option( 'page_for_posts' ) ) );

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

add_action( 'evie_before_content', 'evie_posts_page_content' );

/**
 * Sets the number of posts per page to show in the Mega Menu.
 *
 * @param int $per_page The number of posts per page.
 * @return int The number of posts per page.
 */
function evie_mega_menu_posts_per_page( $per_page ) {
	if ( 'full' === get_theme_mod( 'nav_type', '' ) ) {
		$per_page = 4;
	}
	return $per_page;
}

add_filter( 'evie_mega_menu_posts_per_page', 'evie_mega_menu_posts_per_page' );

/**
 * Flextension plugin filters.
 */

/**
 * Filters the template path for the Flextension module.
 *
 * @param string $path The template path.
 * @param string $name Template name.
 * @return string The template path.
 */
function evie_template_path( $path = '', $name = '' ) {
	$path = "template-parts/{$name}";
	return $path;
}

add_filter( 'flextension_template_dir_path', 'evie_template_path', 10, 2 );

add_filter( 'flextension_editor_page_id', 'evie_get_page_id' );

/**
 * Filters the Google Fonts variants.
 *
 * @param array $variants An array list of the font variants.
 * @return array An array list of the font variants.
 */
function evie_google_fonts_variants( $variants = array() ) {
	$variants = array( '300', '400', '700', '300italic', '400italic', '700italic' );
	return $variants;
}

add_filter( 'flextension_google_fonts_variants', 'evie_google_fonts_variants' );

add_filter( 'flextension_live_search_post_types', 'evie_search_post_types' );

/**
 * Sets the categories block attributes.
 *
 * @param array $attributes Array of block attributes.
 * @return array New block attributes.
 */
function evie_categories_block_settings( $attributes = array() ) {

	if ( 'carousel' === $attributes['layout'] ) {
		$attributes['attr'] = wp_parse_args(
			array(
				'data-space-between' => 30,
			),
			isset( $attributes['attr'] ) ? $attributes['attr'] : array()
		);
	}

	return $attributes;
}

add_filter( 'flextension_categories_block_attributes', 'evie_categories_block_settings' );

/**
 * Filters the number of recent posts to show.
 *
 * @param int   $number     The number of recent posts to show.
 * @param array $attributes The attributes list for the block.
 * @return int The number of recent posts to show.
 */
function evie_author_block_recent_posts_number( $number = 0, $attributes = array() ) {
	$number = isset( $attributes['className'] ) && strpos( $attributes['className'], 'is-style-grid' ) === false ? 5 : 3;
	return $number;
}

add_filter( 'flextension_author_block_recent_posts_number', 'evie_author_block_recent_posts_number', 10, 2 );

/**
 * Returns default settings for the featured media.
 *
 * @param array       $args An array of the arguments to retrieve the featured media.
 * @param int|WP_Post $post Post ID or WP_Post object.
 * @return array An array of the arguments to retrieve the featured media.
 */
function evie_featured_media_args( $args = array(), $post = 0 ) {

	$args['lightbox_size'] = 'evie-fullwidth';

	switch ( $args['type'] ) {
		case 'gallery':
		case 'slider':
			$args['type'] = 'slider';
			if ( ! isset( $args['slider'] ) ) {
				$args['slider'] = array();
			}

			if ( is_single( $post ) ) {
				$args['slider'] = wp_parse_args(
					$args['slider'],
					array(
						'navigation' => true,
					)
				);
			} else {
				$args['slider'] = wp_parse_args(
					$args['slider'],
					array(
						'autoplay'   => 'hover',
						'show_count' => true,
					)
				);
			}
			break;

		case 'video':
			if ( ! isset( $args['video'] ) ) {
				$args['video'] = array();
			}

			if ( is_single( $post ) ) {
				$args['video'] = wp_parse_args(
					$args['video'],
					array(
						'autoplay' => 'visible',
					)
				);
			} else {
				$args['video'] = wp_parse_args(
					$args['video'],
					array(
						'autoplay' => 'hover',
					)
				);
			}
			break;

		case 'audio':
			if ( ! isset( $args['audio'] ) ) {
				$args['audio'] = array();
			}

			if ( is_single( $post ) ) {
				$args['audio'] = wp_parse_args(
					$args['audio'],
					array(
						'autoplay' => 'visible',
					)
				);
			} else {
				$args['audio'] = wp_parse_args(
					$args['audio'],
					array(
						'autoplay' => 'hover',
					)
				);
			}
			break;
	}

	return $args;
}

add_filter( 'flextension_featured_media_args', 'evie_featured_media_args', 10, 2 );
