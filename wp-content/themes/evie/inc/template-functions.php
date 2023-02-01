<?php
/**
 * Additional features to allow styling of the templates
 *
 * @package Evie
 * @version 1.0.0
 */

/**
 * Returns a list of available fonts for the theme.
 *
 * @return array An array list of the fonts.
 */
function evie_fonts() {
	$fonts = array(
		'standard' => array(
			'title' => esc_html__( 'Standard Fonts', 'evie' ),
			'fonts' => array(
				'Arial, Helvetica, sans-serif'    => 'Arial, Helvetica',
				'"Avant Garde", serif'            => 'Avant Garde',
				'"Comic Sans MS", cursive'        => 'Comic Sans MS',
				'Courier, monospace'              => 'Courier, monospace',
				'Garamond, serif'                 => 'Garamond',
				'Georgia, serif'                  => 'Georgia',
				'Impact, Charcoal, sans-serif'    => 'Impact, Charcoal',
				'Palatino, serif'                 => 'Palatino',
				'Tahoma, Geneva, sans-serif'      => 'Tahoma, Geneva',
				'"Times New Roman", Times, serif' => 'Times New Roman, Times',
				'Verdana, Geneva, sans-serif'     => 'Verdana, Geneva',
			),
		),
	);

	/**
	 * Filters the list of fonts.
	 *
	 * @since 0.5.0
	 *
	 * @param array $fonts An array list of the fonts.
	 */
	return apply_filters( 'evie_fonts', $fonts );
}

/**
 * Returns the current site color scheme.
 *
 * Returns the current user's color scheme setting if supported.
 * Otherwise returns default theme setting.
 *
 * @return string The site color scheme. A return value can be 'dark', 'light' or 'auto'.
 */
function evie_get_color_scheme() {
	$color_scheme = '';

	// If the theme allows users to choose color scheme, get it from the user settings.
	if ( evie_has_user_color_support() ) {
		$color_scheme = evie_get_user_color_scheme();
	}

	// If there are no user setting values, use default theme setting.
	if ( empty( $color_scheme ) ) {
		$color_scheme = get_theme_mod( 'color_scheme', '' );
	}

	if ( empty( $color_scheme ) ) {
		$color_scheme = 'auto';
	}

	return $color_scheme;
}

/**
 * Returns whether the site allows users to switch schemes.
 *
 * @return bool Whether the site allows users to switch schemes.
 */
function evie_has_user_color_support() {
	return ( true === get_theme_mod( 'user_color_support', true ) );
}

/**
 * Returns the user color scheme preference.
 *
 * @param int $user_id Optional. The user ID. Default current user ID.
 * @return string The user color scheme. Returns either 'dark' or 'light' when user has color scheme preference. Otherwise '' (empty string).
 */
function evie_get_user_color_scheme( $user_id = 0 ) {
	$color_scheme = '';

	if ( 0 === $user_id ) {
		$user_id = get_current_user_id();
	}

	if ( $user_id > 0 ) {
		$color_scheme = get_user_meta( $user_id, '_evie_color', true );
	}

	return $color_scheme;
}

/**
 * Updates the user color preference.
 *
 * @param int    $user_id The user ID.
 * @param string $color   The color preference.
 */
function evie_update_user_color_scheme( $user_id = 0, $color = '' ) {
	if ( 0 === $user_id ) {
		$user_id = get_current_user_id();
	}

	update_user_meta( $user_id, '_evie_color', $color );
}

/**
 * Returns whether current user has set color scheme preference.
 *
 * @return bool Whether current user has set color scheme preference.
 */
function evie_has_user_color_scheme() {
	$color_scheme = evie_get_user_color_scheme();

	return ! empty( $color_scheme );
}

/**
 * Creates and returns a new string by concatenating a key which has a true value,
 * separated by a specified separator string.
 *
 * @param string $separator Whether to include the class name.
 * @param array  $values    An associative array to join.
 * @return string A new string separated by a specified separator.
 */
function evie_join( $separator = ' ', $values = '' ) {
	$output = '';
	if ( is_array( $values ) && ! empty( $values ) ) {
		$names = array();
		foreach ( $values as $key => $value ) {
			if ( true === (bool) $value ) {
				$names[] = $key;
			}
		}

		if ( ! empty( $names ) ) {
			$output = implode( $separator, array_unique( $names ) );
		}
	}

	return $output;
}

/**
 * Returns an HTML attribute.
 *
 * @param string $name        Attribute name.
 * @param string $value       Attribute value.
 * @param bool   $empty_value Whether to allow the empty attribute value.
 * @return string An HTML attribute.
 */
function evie_get_attribute( $name, $value = '', $empty_value = false ) {
	if ( empty( $value ) ) {
		if ( true === $empty_value ) {
			return sanitize_key( $name );
		} else {
			return '';
		}
	}

	return sprintf( '%s="%s"', sanitize_key( $name ), esc_attr( $value ) );
}

/**
 * Prints out or returns HTML attributes string.
 *
 * @param array $attributes   Array of attributes to append to HTML element.
 * @param bool  $space_before Whether to add a space before the output.
 * @param bool  $echo         Whether to print out the output string.
 * @return void|string Void if 'echo' argument is true. Otherwise, HTML attribute string.
 */
function evie_attributes( $attributes = array(), $space_before = false, $echo = true ) {
	$attrs = array();
	foreach ( $attributes as $name => $value ) {
		if ( ! empty( $name ) ) {
			$attrs[] = evie_get_attribute( $name, $value );
		}
	}

	if ( empty( $attrs ) ) {
		return;
	}

	$output = implode( ' ', $attrs );

	if ( true === $echo ) {
		echo ( true === $space_before ) ? ' ' . $output : $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		return ( true === $space_before ) ? ' ' . $output : $output;
	}
}

/**
 * Returns an array of CSS classes.
 *
 * @param string|string[] $class Space-separated string or array of class names.
 * @return string[] $classes An array of class names.
 */
function evie_get_classes( $class = '' ) {
	$classes = array();
	if ( $class ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_map( 'esc_attr', $class );
	}
	return $classes;
}

/**
 * Returns a space-separated CSS class.
 * Otherwise, return empty string.
 *
 * @param string $class        A space-separated string.
 * @param bool   $include      Whether to include the class name.
 * @param bool   $space_before Whether to add a space before the output.
 * @param bool   $echo         Whether to print out the output string.
 * @return string|void A space-separated string if the class is valid. Or void if 'echo' argument is true.
 */
function evie_classname( $class = '', $include = true, $space_before = false, $echo = false ) {
	$output = '';
	if ( true === (bool) $include ) {
		$prefix = $space_before ? ' ' : '';
		$output = $prefix . trim( $class );
	}

	if ( true === $echo ) {
		echo esc_attr( $output );
	} else {
		return esc_attr( $output );
	}
}

/**
 * Returns a space-separated CSS class.
 * Otherwise, return empty string.
 *
 * @param array $classes      An associative array of the class names to join.
 * @param bool  $space_before Whether to add a space before the output.
 * @param bool  $echo         Whether to print out the output string.
 * @return string|void A space-separated string if the class is valid. Or void if 'echo' argument is true.
 */
function evie_classnames( $classes = array(), $space_before = false, $echo = false ) {
	$output = '';
	$prefix = $space_before ? ' ' : '';

	if ( is_array( $classes ) && ! empty( $classes ) ) {
		$output = evie_join( ' ', $classes );
		if ( ! empty( $output ) ) {
			$output = $prefix . $output;
		}
	}

	if ( true === $echo ) {
		echo esc_attr( $output );
	} else {
		return esc_attr( $output );
	}
}

/**
 * Returns the color from the theme setting.
 *
 * @param string $name    The color setting name.
 * @param string $default Optional. Default value of the color.
 * @return string The color from the theme setting.
 */
function evie_get_color_setting( $name, $default = '' ) {
	return get_theme_mod( $name . '_color', $default );
}

/**
 * Returns a default HEX color for the color slug.
 *
 * @param string $name The color name.
 * @return string A default HEX color for the color slug.
 */
function evie_default_color( $name = '' ) {
	$default_colors = array(
		'primary'         => '#f45656',
		'on_primary'      => 255,
		'secondary'       => '#a455f4',
		'on_secondary'    => 255,
		'on_surface'      => 20,
		'on_surface_dark' => 245,
	);
	return isset( $default_colors[ $name ] ) ? $default_colors[ $name ] : '';
}

/**
 * Converts HEX color string to RGB array.
 *
 * @param string $color   Hex color string.
 * @return array Array values of the RGB color.
 */
function evie_hex_to_rgb_array( $color ) {
	$rgb = array( 0, 0, 0 );

	/**
	 * Return default if no color provided.
	 */
	if ( empty( $color ) ) {
		return $rgb;
	}

	/**
	 * Remove '#' from $color if it is provided.
	 */
	if ( is_string( $color ) && '#' === $color[0] ) {
		$color = substr( $color, 1 );
	}

	/**
	 * Check if color has 6 or 3 characters and get values.
	 */
	if ( strlen( $color ) === 6 ) {
		$rgb = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) === 3 ) {
		$rgb = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return $rgb;
	}

	return array_map( 'hexdec', $rgb );
}

/**
 * Converts HEX color string to RGB or RGBA colors.
 *
 * @param string $color   Hex color string.
 * @param float  $opacity A number between 0.0 (fully transparent) and 1.0 (fully opaque).
 * @return string RGB or RGBA color string.
 */
function evie_hex_to_rgba( $color, $opacity = false ) {

	$rgb = evie_hex_to_rgb_array( $color );

	if ( abs( $opacity ) > 1 ) {
		$opacity = 1.0;
	}

	if ( false !== $opacity && floatval( $opacity ) > 0.0 ) {
		$output = 'rgba(' . implode( ',', $rgb ) . ',' . min( 1, max( 0.1, $opacity ) ) . ')';
	} else {
		$output = 'rgb(' . implode( ',', $rgb ) . ')';
	}

	/**
	 * Return rgb(a) color string.
	 */
	return $output;
}

/**
 * Returns a custom colors CSS.
 *
 * @return string A custom CSS for colors.
 */
function evie_generate_custom_colors_css() {

	$custom_colors      = array();
	$dark_custom_colors = array();

	$primary_color = evie_get_color_setting( 'primary' );
	if ( ! empty( $primary_color ) && evie_default_color( 'primary' ) !== $primary_color ) {
		$custom_colors[] = '--evie-color-primary-rgb: ' . implode( ',', evie_hex_to_rgb_array( $primary_color ) ) . ';';
	}

	$on_primary = evie_get_color_setting( 'on_primary' );
	if ( '' !== $on_primary && evie_default_color( 'on_primary' ) !== $on_primary ) {
		$custom_colors[] = '--evie-color-on-primary-rgb: ' . $on_primary . ', ' . $on_primary . ', ' . $on_primary . ';';
	}

	$secondary_color = evie_get_color_setting( 'secondary' );
	if ( ! empty( $secondary_color ) && evie_default_color( 'secondary' ) !== $secondary_color ) {
		$custom_colors[] = '--evie-color-secondary-rgb: ' . implode( ',', evie_hex_to_rgb_array( $secondary_color ) ) . ';';
	}

	$on_secondary = evie_get_color_setting( 'on_secondary' );
	if ( '' !== $on_secondary && evie_default_color( 'on_secondary' ) !== $on_secondary ) {
		$custom_colors[] = '--evie-color-on-secondary-rgb: ' . $on_secondary . ', ' . $on_secondary . ', ' . $on_secondary . ';';
	}

	if ( ! is_customize_preview() ) {
		$header = evie_get_color_setting( 'header_bg' );
		if ( ! empty( $header ) ) {
			$custom_colors[] = '--evie-color-header: ' . $header . ';';
		}

		$background_color = get_background_color();
		if ( ! empty( $background_color ) ) {
			$custom_colors[] = '--evie-color-background-rgb: ' . implode( ',', evie_hex_to_rgb_array( $background_color ) ) . ';';
		}
	}

	$on_surface = evie_get_color_setting( 'on_surface' );
	if ( ! empty( $on_surface ) && evie_default_color( 'on_surface' ) !== $on_surface ) {
		$custom_colors[] = '--evie-color-on-surface-rgb: ' . $on_surface . ', ' . $on_surface . ', ' . $on_surface . ';';
	}

	$on_surface_dark = evie_get_color_setting( 'on_surface_dark' );
	if ( ! empty( $on_surface_dark ) && evie_default_color( 'on_surface_dark' ) !== $on_surface_dark ) {
		$dark_custom_colors[] = '--evie-color-on-surface-rgb: ' . $on_surface_dark . ', ' . $on_surface_dark . ', ' . $on_surface_dark . ';';
	}

	$custom_styles = array();

	if ( ! empty( $custom_colors ) ) {
		$custom_styles[] = ':root, .has-scheme-light, .flext-has-scheme-light { ' . implode( ' ', $custom_colors ) . ' }';
	}

	if ( ! empty( $dark_custom_colors ) ) {
		$custom_styles[] = '.has-scheme-dark, .flext-has-scheme-dark { ' . implode( ' ', $dark_custom_colors ) . ' }';
	}

	$custom_css = '';

	if ( ! empty( $custom_styles ) ) {
		$custom_css = implode( ' ', $custom_styles );
	}

	return $custom_css;
}

/**
 * Returns a custom typography CSS.
 *
 * @return string A custom CSS for typography.
 */
function evie_generate_custom_typography_css() {
	$custom_styles = array();

	$font_styles = array();
	$base_font   = get_theme_mod( 'typography_font_base', '' );
	if ( ! empty( $base_font ) ) {
		$font_styles[] = '--evie-font-secondary: ' . $base_font . ';';
	}

	$primary_font = get_theme_mod( 'typography_font_headings', '' );
	if ( ! empty( $primary_font ) ) {
		$font_styles[] = '--evie-font-primary: ' . $primary_font . ';';
	}

	if ( ! empty( $font_styles ) ) {
		$custom_styles[] = 'body { ' . implode( ' ', $font_styles ) . ' }';
	}

	$font_size_settings = array( 'base', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
	$font_sizes         = array();

	foreach ( $font_size_settings as $name ) {
		$font_sizes[ $name ] = get_theme_mod( "typography_font_{$name}_sizes", array() );
	}

	$media_queries = array(
		'@media (max-width: 767px)',
		'@media (min-width: 768px) and (max-width: 1023px)',
		'@media (min-width: 1024px)',
	);

	$breakpoint_count  = count( $media_queries );
	$breakpoint_styles = array();

	for ( $i = 0; $i < $breakpoint_count; $i++ ) {

		$font_size_styles = array();

		foreach ( $font_size_settings as $name ) {
			if ( ! empty( $font_sizes[ $name ] ) ) {
				$value = isset( $font_sizes[ $name ][ $i ] ) ? $font_sizes[ $name ][ $i ] : '';
				if ( ! empty( $value ) ) {
					$font_size_styles[] = "--evie-font-size-{$name}: {$value};";
				}
			}
		}

		if ( ! empty( $font_size_styles ) ) {
			$font_size_style = 'body { ' . implode( ' ', $font_size_styles ) . ' }';
			if ( ! empty( $media_queries[ $i ] ) ) {
				$font_size_style = $media_queries[ $i ] . ' { ' . $font_size_style . ' }';
			}

			$breakpoint_styles[] = $font_size_style;
		}
	}

	if ( ! empty( $breakpoint_styles ) ) {
		$custom_styles[] = implode( ' ', $breakpoint_styles );
	}

	$custom_css = '';

	if ( ! empty( $custom_styles ) ) {
		$custom_css = implode( ' ', $custom_styles );
	}

	return $custom_css;
}

/**
 * Ands custom styles to a registered stylesheet.
 *
 * @param string $handle Name of the stylesheet to add the extra styles to.
 */
function evie_add_custom_styles( $handle = '' ) {

	$custom_colors_css = evie_generate_custom_colors_css();
	if ( ! empty( $custom_colors_css ) ) {
		wp_add_inline_style( $handle, $custom_colors_css );
	}

	if ( ! is_customize_preview() ) {
		$custom_typography_css = evie_generate_custom_typography_css();
		if ( ! empty( $custom_typography_css ) ) {
			wp_add_inline_style( $handle, $custom_typography_css );
		}
	}
}

/**
 * Returns the customizer setting prefix.
 *
 * @param string|array $type The type of customizer setting.
 * @return string The customizer setting prefix.
 */
function evie_customizer_prefix( $type = '' ) {
	if ( empty( $type ) ) {
		$type = get_query_var( 'post_type' );
	}

	if ( empty( $type ) ) {
		$type = get_post_type();
	}

	$prefix = 'blog';

	if ( is_archive() || is_search() ) {
		$prefix .= '_archive';
	} elseif ( is_single() ) {
		$prefix .= '_single';
	} else {
		$prefix .= '_posts';
	}

	/**
	 * Filters a prefix of the customizer setting.
	 *
	 * @param string       $prefix The customizer setting prefix.
	 * @param string|array $type   The type of customizer setting.
	 */
	return apply_filters( 'evie_customizer_prefix', $prefix, $type );
}

/**
 * Retrieves theme modification value for the current theme.
 *
 * @param string       $name    Theme setting name.
 * @param string|false $default Optional. Theme setting default value. Default false.
 * @param string       $prefix  The setting prefix.
 * @return mixed Theme modification value.
 */
function evie_get_theme_setting( $name, $default = false, $prefix = '' ) {
	if ( empty( $prefix ) ) {
		$prefix = evie_customizer_prefix();
	}

	$value = get_theme_mod( "{$prefix}_{$name}", $default );

	/**
	 * Filters the current setting value.
	 *
	 * @param string $value The current setting value.
	 */
	return apply_filters( "evie_{$prefix}_{$name}", $value );
}

/**
 * Returns CSS class names for the grid columns.
 *
 * @param int $columns Number of columns.
 * @return string CSS class names.
 */
function evie_get_grid_columns_class( $columns = 0 ) {
	if ( $columns > 1 ) {
		return 'evie-grid has-' . $columns . '-columns';
	}
	return '';
}

/**
 * Retrieves the ID for the current page or post.
 *
 * @return int The ID for the current page or post.
 */
function evie_get_page_id() {

	$id = 0;
	if ( 'page' === get_option( 'show_on_front' ) && ( is_home() || is_archive() ) ) {
		if ( is_home() ) {
			$id = get_queried_object_id();
		} elseif ( ! is_search() && is_archive() ) {
			$id = (int) get_option( 'page_for_posts' );
		}
	} elseif ( is_singular() ) {
		$id = get_the_ID();
	}

	/**
	 * Filters the ID for the current page.
	 *
	 * @param int $id The ID for the current page.
	 */
	return apply_filters( 'evie_get_page_id', $id );
}

/**
 * Returns whether currently viewing archive pages.
 *
 * @param string $post_type Optional. The post type of archive. Default empty.
 * @return bool Whether currently viewing archive pages.
 */
function evie_is_archive( $post_type = '' ) {

	if ( empty( $post_type ) ) {
		return is_archive() || is_author() || is_tax();
	}

	if ( is_archive() && ( get_post_type() === $post_type || in_array( $post_type, (array) get_query_var( 'post_type' ), true ) ) ) {
		return true;
	} elseif ( is_tax() ) {
		$queried_object = get_queried_object();
		if ( $queried_object instanceof WP_Term ) {
			$taxonomy = get_taxonomy( $queried_object->taxonomy );
			return $taxonomy && isset( $taxonomy->object_type ) && in_array( $post_type, $taxonomy->object_type, true );
		}
	}

	return false;
}

/**
 * Displays the header navigation menu.
 */
function evie_header_navigation() {
	$args = array(
		'type'  => get_theme_mod( 'nav_type', '' ),
		'align' => get_theme_mod( 'nav_align', '' ),
	);

	get_template_part( 'template-parts/header/header', '', $args );
}

/**
 * Returns whether the transparent menu is enabled.
 *
 * @param int $id The post ID.
 * @return bool Whether the transparent menu is enabled.
 */
function evie_is_transparent_menu( $id = 0 ) {
	if ( ( is_archive() && ! is_post_type_archive() ) && ! evie_has_header( $id ) ) {
		return false;
	}

	if ( empty( $id ) ) {
		$id = evie_get_page_id();
	}

	return (bool) get_post_meta( $id, '_evie_transparent_menu', true );
}

/**
 * Returns menu text mode for the current page.
 *
 * @param int $id The current page ID.
 * @return string Menu text mode for the current page.
 */
function evie_get_menu_text_mode( $id = 0 ) {
	if ( ( is_archive() && ! is_post_type_archive() ) && ! evie_has_header( $id ) ) {
		return '';
	}

	if ( empty( $id ) ) {
		$id = evie_get_page_id();
	}

	return get_post_meta( $id, '_evie_menu_color', true );
}

/**
 * Returns the login URL.
 *
 * @return string The menu login URL.
 */
function evie_menu_login_url() {
	$url = wp_login_url( home_url() );

	/**
	 * Filters the menu login URL.
	 *
	 * @param string $url The menu login URL.
	 */
	return apply_filters( 'evie_menu_login_url', $url );
}

/**
 * Returns HTML markup for the menu login button.
 *
 * @return string HTML markup for the menu login button.
 */
function evie_menu_login_button() {
	$button = '';
	if ( function_exists( 'flextension_lightbox_login_button' ) ) {
		$button = flextension_lightbox_login_button( 'no-ajax', 'evie-ico-user' );
	} else {
		$button = '<a class="login-button" href="' . esc_attr( evie_menu_login_url() ) . '" title="' . esc_attr__( 'Log In', 'evie' ) . '"><i class="evie-ico-user"></i></a>';
	}
	return $button;
}

/**
 * Retrieves the site logo, either text or image.
 *
 * @param array $args Arguments for displaying the site logo either as an image or text.
 * @return string HTML content for the site logo, either text or image.
 */
function evie_get_site_logo( $args = array() ) {
	$logo       = get_custom_logo();
	$site_title = get_bloginfo( 'name' );
	$content    = '';
	$classname  = '';

	$defaults = array(
		'logo'        => '%1$s<span class="screen-reader-text">%2$s</span>',
		'logo_class'  => 'site-logo',
		'title'       => '<a href="%1$s">%2$s</a>',
		'title_class' => 'site-title',
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * Filters the arguments for `evie_site_logo()`.
	 *
	 * @param array  $args     Parsed arguments.
	 * @param array  $defaults Function's default arguments.
	 */
	$args = apply_filters( 'evie_site_logo_args', $args, $defaults );

	$logo .= evie_get_light_logo();

	if ( has_custom_logo() ) {
		$content   = sprintf( $args['logo'], $logo, esc_html( $site_title ) );
		$classname = $args['logo_class'];
	} else {
		$content   = sprintf( $args['title'], esc_url( get_home_url( null, '/' ) ), esc_html( $site_title ) );
		$classname = $args['title_class'];
	}

	$html = '<div class="' . esc_attr( $classname ) . '">' . $content . '</div>';

	/**
	 * Filters the HTML output for `evie_site_logo()`.
	 *
	 * @param string $html      Compiled HTML based on our arguments.
	 * @param array  $args      Parsed arguments.
	 * @param string $classname Class name based on current view, home or single.
	 * @param string $content   HTML for site title or logo.
	 */
	return apply_filters( 'evie_site_logo', $html, $args, $classname, $content );
}

/**
 * Returns a light logo, linked to home unless the theme supports removing the link on the home page.
 *
 * @param int $blog_id Optional. ID of the blog in question. Default is the ID of the current blog.
 * @return string Custom logo markup.
 */
function evie_get_light_logo( $blog_id = 0 ) {
	$html          = '';
	$switched_blog = false;

	if ( is_multisite() && ! empty( $blog_id ) && get_current_blog_id() !== (int) $blog_id ) {
		switch_to_blog( $blog_id );
		$switched_blog = true;
	}

	$custom_logo_id = absint( get_theme_mod( 'light_logo', 0 ) );

	// We have a logo. Logo is go.
	if ( $custom_logo_id ) {
		$custom_logo_attr = array(
			'class'   => 'light-logo',
			'loading' => false,
		);

		$unlink_homepage_logo = (bool) get_theme_support( 'custom-logo', 'unlink-homepage-logo' );

		if ( $unlink_homepage_logo && is_front_page() && ! is_paged() ) {
			/*
			 * If on the home page, set the logo alt attribute to an empty string,
			 * as the image is decorative and doesn't need its purpose to be described.
			 */
			$custom_logo_attr['alt'] = '';
		} else {
			/*
			 * If the logo alt attribute is empty, get the site title and explicitly pass it
			 * to the attributes used by wp_get_attachment_image().
			 */
			$image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
			if ( empty( $image_alt ) ) {
				$custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
			}
		}

		/**
		 * Filters the list of custom logo image attributes for the Light version.
		 *
		 * @param array $custom_logo_attr Custom logo image attributes for the Light version.
		 * @param int   $custom_logo_id   Custom logo attachment ID for the Light version.
		 * @param int   $blog_id          ID of the blog to get the custom logo for.
		 */
		$custom_logo_attr = apply_filters( 'evie_get_light_logo_image_attributes', $custom_logo_attr, $custom_logo_id, $blog_id );

		/*
		 * If the alt attribute is not empty, there's no need to explicitly pass it
		 * because wp_get_attachment_image() already adds the alt attribute.
		 */
		$image = wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr );

		if ( $unlink_homepage_logo && is_front_page() && ! is_paged() ) {
			// If on the home page, don't link the logo to home.
			$html = sprintf(
				'<span class="light-logo-link">%1$s</span>',
				$image
			);
		} else {
			$aria_current = is_front_page() && ! is_paged() ? ' aria-current="page"' : '';

			$html = sprintf(
				'<a href="%1$s" class="light-logo-link" rel="home"%2$s>%3$s</a>',
				esc_url( home_url( '/' ) ),
				$aria_current,
				$image
			);
		}
	} elseif ( is_customize_preview() ) {
		// If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
		$html = sprintf(
			'<a href="%1$s" class="light-logo-link" style="display:none;"><img class="light-logo"/></a>',
			esc_url( home_url( '/' ) )
		);
	}

	if ( $switched_blog ) {
		restore_current_blog();
	}

	/**
	 * Filters the custom logo output for the Light version.
	 *
	 * @param string $html    Custom logo HTML output for the Light version.
	 * @param int    $blog_id ID of the blog to get the custom logo for.
	 */
	return apply_filters( 'evie_get_light_logo', $html, $blog_id );
}

/**
 * Displays the site logo, either text or image.
 *
 * @param array $args Arguments for displaying the site logo either as an image or text.
 */
function evie_site_logo( $args = array() ) {
	echo evie_get_site_logo( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Returns a viewport width in pixels for when the mobile menu will become the desktop menu.
 *
 * @return int A viewport width in pixels.
 */
function evie_menu_breakpoint() {
	return absint( get_theme_mod( 'nav_menu_breakpoint', 1080 ) );
}

/**
 * Returns whether the main menu can be displayed.
 *
 * @param int $id The post ID.
 * @return bool Whether the main menu can be displayed.
 */
function evie_has_menu( $id = 0 ) {
	if ( empty( $id ) ) {
		$id = evie_get_page_id();
	}
	return false === (bool) get_post_meta( $id, '_evie_hide_menu', true );
}

/**
 * Returns whether the menu button can be displayed.
 *
 * @param string $name Button name.
 * @return bool Whether the main menu can be displayed.
 */
function evie_has_menu_button( $name ) {
	$visible = false;

	switch ( $name ) {
		case 'sidebar':
			$enabled = ( true === get_theme_mod( 'nav_sidebar_button', true ) );
			if ( true === $enabled ) {
				$type    = get_theme_mod( 'nav_type', '' );
				$enabled = ( 'full' === $type && is_active_sidebar( 'main' ) ) || ( empty( $type ) && is_active_sidebar( 'main' ) || is_active_sidebar( 'menu' ) );
			}
			$visible = $enabled || is_customize_preview();
			break;
		case 'login':
			$visible = ! is_user_logged_in() && get_theme_mod( 'nav_login_button', false ) || is_customize_preview();
			break;
		case 'search':
			$visible = get_theme_mod( 'nav_search_button', true ) || is_customize_preview();
			break;
	}

	return $visible;
}

/**
 * Prints out the extra menu items.
 */
function evie_extra_menu_items() {
	$items = array();

	if ( evie_has_menu_button( 'sidebar' ) ) {
		$items['sidebar'] = '<a class="sidebar-button" href="#" aria-label="' . esc_attr__( 'Show/Hide Sidebar', 'evie' ) . '"><i class="evie-ico-more"></i></a>';
	}

	if ( evie_has_menu_button( 'login' ) ) {
		$items['login'] = evie_menu_login_button();
	}

	if ( evie_has_menu_button( 'search' ) ) {
		$items['search'] = '<a class="live-search-button" href="#" aria-label="' . esc_attr__( 'Show/Hide Live Search', 'evie' ) . '"><i class="evie-ico-search"></i></a>';
	}

	/**
	 * Filters the list of extra menu items.
	 *
	 * @param array $items An array of the extra menu items.
	 */
	$items = apply_filters( 'evie_extra_menu_items', $items );

	if ( ! empty( $items ) ) {
		foreach ( $items as $name => $button ) {
			echo '<li class="menu-item-' . esc_attr( $name ) . '">' . $button . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

/**
 * Returns whether the page has header.
 *
 * @param int $id The post ID.
 * @return bool Whether the page has header.
 */
function evie_has_header( $id = 0 ) {
	if ( empty( $id ) ) {
		$id = evie_get_page_id();
	}
	$header = get_post_meta( $id, '_evie_header', true );
	return 'none' !== $header;
}

/**
 * Displays the Live Search from the Flextension plugin if it is enabled.
 */
function evie_live_search() {
	if ( function_exists( 'flextension_live_search' ) ) {
		flextension_live_search();
	} else {
		echo '<div class="evie-search-form">';
		get_search_form();
		echo '</div>';
	}
}

/**
 * Retrieves the post layout setting.
 *
 * @param int $id The post ID.
 * @return string The layout setting value for the post.
 */
function evie_get_post_layout( $id = 0 ) {
	if ( empty( $id ) ) {
		$id = evie_get_page_id();
	}
	$layout = get_post_meta( $id, '_evie_layout', true );
	if ( empty( $layout ) ) {
		$layout = '1';
	}
	return $layout;
}

/**
 * Displays a loader logo.
 */
function evie_loader_logo() {
	$image_id = get_theme_mod( 'loader_logo', '' );
	if ( ! empty( $image_id ) ) {
		echo wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'class' => 'spinner-image' ) );
	}
}

/**
 * Displays the page header.
 *
 * @param int $id The page ID.
 */
function evie_page_header( $id = 0 ) {

	if ( 0 === $id ) {
		$id = evie_get_page_id();
	}

	$args = array(
		'layout'                     => 'none',
		'size'                       => '',
		'width'                      => '',
		'align'                      => '',
		'gap'                        => true,
		'text_mode'                  => '',
		'default_background'         => false,
		'background'                 => '',
		'background_color'           => '',
		'background_image'           => '',
		'background_position'        => '',
		'background_size'            => '',
		'background_attachment'      => '',
		'background_repeat'          => '',
		'background_overlay'         => '',
		'background_overlay_color'   => '',
		'background_overlay_opacity' => '',
	);

	if ( $id > 0 ) {

		$layout = get_post_meta( $id, '_evie_header', true );

		if ( 'none' !== $layout ) {
			$args = wp_parse_args(
				array(
					'layout'     => $layout,
					'title'      => get_the_title( $id ),
					'size'       => get_post_meta( $id, '_evie_header_size', true ),
					'width'      => get_post_meta( $id, '_evie_header_width', true ),
					'align'      => get_post_meta( $id, '_evie_header_align', true ),
					'gap'        => false === (bool) get_post_meta( $id, '_evie_hide_header_gap', true ),
					'background' => get_post_meta( $id, '_evie_header_bg', true ),
				),
				$args
			);

			if ( empty( $args['layout'] ) ) {
				$args['layout']      = 'description';
				$args['description'] = get_post_meta( $id, '_evie_header_desc', true );
			}

			if ( 'color' === $args['background'] ) {
				$args['background_color'] = get_post_meta( $id, '_evie_header_bg_color', true );
			} elseif ( 'image' === $args['background'] ) {
				$image_id = get_post_thumbnail_id( $id );
				if ( ! empty( $image_id ) ) {
					$image_url = wp_get_attachment_image_url( $image_id, 'evie-fullwidth' );
					if ( empty( $image_url ) ) {
						$image_url = get_header_image();
					}

					if ( ! empty( $image_url ) ) {
						$args['background_image']      = esc_url( $image_url );
						$args['background_position']   = get_post_meta( $id, '_evie_header_bg_position', true );
						$args['background_size']       = get_post_meta( $id, '_evie_header_bg_size', true );
						$args['background_attachment'] = get_post_meta( $id, '_evie_header_bg_attachment', true );
						$args['background_repeat']     = get_post_meta( $id, '_evie_header_bg_repeat', true );
						$args['background_overlay']    = get_post_meta( $id, '_evie_header_bg_overlay', true );

						if ( 'color' === $args['background_overlay'] ) {
							$args['text_mode']                  = get_post_meta( $id, '_evie_header_text_mode', true );
							$args['background_overlay_color']   = get_post_meta( $id, '_evie_header_overlay_color', true );
							$opacity                            = get_post_meta( $id, '_evie_header_overlay_opacity', true );
							$args['background_overlay_opacity'] = round( floatval( $opacity ) / 100.0, 2 );
						}
					}
				}
			}
		}
	}

	if (
		( 0 === $id && ( is_home() || is_archive() || is_search() ) ) ||
		( $id > 0 && ( ! is_post_type_archive() && is_archive() ) )
	) {
		$args = wp_parse_args(
			array(
				'layout' => 'archive',
				'width'  => 'wide',
			),
			$args
		);
	}

	if ( 'none' !== $args['layout'] ) {
		// If empty, use default header background settings.
		if ( empty( $args['background'] ) ) {
			$args['default_background'] = true;

			if ( '' === get_theme_mod( 'header_bg', '' ) ) {
				$args['background']       = '';
				$args['background_color'] = get_theme_mod( 'header_bg_color', '' );
			} else {
				$args['background'] = 'image';

				$image_url = get_header_image();
				if ( ! empty( $image_url ) ) {
					$args['background_image']      = $image_url;
					$args['background_position']   = get_theme_mod( 'header_bg_position', '' );
					$args['background_size']       = get_theme_mod( 'header_bg_size', '' );
					$args['background_attachment'] = get_theme_mod( 'header_bg_attachment', '' );
					$args['background_repeat']     = get_theme_mod( 'header_bg_repeat', '' );
					$args['background_overlay']    = get_theme_mod( 'header_bg_overlay', '' );

					if ( 'color' === $args['background_overlay'] ) {
						$args['text_mode']                  = get_theme_mod( 'header_text_mode', '' );
						$args['background_overlay_color']   = get_theme_mod( 'header_bg_overlay_color', '#000' );
						$opacity                            = get_theme_mod( 'header_bg_overlay_opacity', 75 );
						$args['background_overlay_opacity'] = round( floatval( $opacity ) / 100.0, 2 );
					}
				}
			}
		}
	}

	/**
	 * Filters the header arguments.
	 *
	 * @param array $args An array of the arguments for the header.
	 */
	$args = apply_filters( 'evie_page_header_args', $args );

	if ( 'none' !== $args['layout'] ) {
		get_template_part( 'template-parts/header/header', 'page', $args );
	}
}

/**
 * Prints out the header CSS class.
 *
 * @param array $args An array of the arguments for the header.
 */
function evie_header_class( $args = array() ) {
	evie_classnames(
		array(
			'page-header'                          => true,
			'alignfull'                            => true,
			"has-header-{$args['layout']}"         => ! empty( $args['layout'] ),
			"has-header-size-{$args['size']}"      => ! empty( $args['size'] ),
			'has-default-background'               => $args['default_background'],
			'has-gradient-overlay'                 => ! empty( $args['background_image'] ) && empty( $args['background_overlay'] ),
			"has-background-{$args['background']}" => ( ! empty( $args['background'] ) && 'color' !== $args['background'] ),
			"has-text-mode-{$args['text_mode']}"   => ! empty( $args['text_mode'] ),
			"has-text-align-{$args['align']}"      => ! empty( $args['align'] ),
			'has-text-align-none'                  => empty( $args['align'] ),
			'has-no-gap'                           => false === $args['gap'],
		),
		false,
		true
	);
}

/**
 * Displays the header background.
 *
 * @param array $args An array of the arguments to display the background.
 */
function evie_header_background( $args = array() ) {

	if ( empty( $args['background'] ) && ! is_customize_preview() ) {
		return;
	}

	echo '<div';

	evie_attributes(
		array(
			'class' => evie_classnames(
				array(
					'header-background'       => true,
					'has-background-parallax' => ! empty( $args['background_image'] ) && (bool) $args['background_attachment'],
					'has-background-repeat'   => ! empty( $args['background_image'] ) && (bool) $args['background_repeat'],
					"has-background-{$args['background_size']}" => ! empty( $args['background_image'] ) && ! empty( $args['background_size'] ),
				)
			),
			'style' => evie_join(
				';',
				array(
					"background-color: {$args['background_color']}"       => ! empty( $args['background_color'] ),
					"background-image: url({$args['background_image']})"  => ! empty( $args['background_image'] ),
					"background-position: {$args['background_position']}" => ! empty( $args['background_position'] ),
				)
			),
		),
		true
	);

	echo '>';

	if ( 'color' === $args['background_overlay'] || is_customize_preview() ) {

		if ( is_customize_preview() ) {
			if ( empty( $args['background_overlay_color'] ) ) {
				$args['background_overlay_color'] = '#000';
			}

			if ( empty( $args['background_overlay_opacity'] ) ) {
				$args['background_overlay_opacity'] = 0.75;
			}
		}

		if ( ! empty( $args['background_overlay_opacity'] ) ) {

			echo '<div class="background-overlay"';

			evie_attributes(
				array(
					'style' => evie_join(
						';',
						array(
							"background-color: {$args['background_overlay_color']}" => ! empty( $args['background_overlay_color'] ),
							"opacity: {$args['background_overlay_opacity']}"        => ! empty( $args['background_overlay_opacity'] ),
						)
					),
				),
				true
			);

			echo '></div>';
		}
	}

	echo '</div>';
}

/**
 * Retrieves the date archive title.
 *
 * @param string $format (Optional) PHP date format. Defaults to the 'date_format' option.
 * @return string The date archive title.
 */
function evie_get_date_archive_title( $format = '' ) {
	$post = 0;
	if ( ! isset( $GLOBALS['post'] ) ) {
		$post = $GLOBALS['wp_query']->posts[0];
	}

	return get_the_date( $format, $post );
}

/**
 * Retrieves the post type title.
 *
 * @return string The title for a post type.
 */
function evie_get_post_type_title() {
	$post_type = get_query_var( 'post_type' );

	if ( empty( $post_type ) ) {
		$post_type = get_post_type();
	}

	if ( is_array( $post_type ) && count( $post_type ) > 0 ) {
		$post_type = $post_type[0];
	}

	if ( empty( $post_type ) ) {
		$post_type = 'post';
	}

	$post_type_object = get_post_type_object( $post_type );

	return $post_type_object->label;
}

/**
 * Returns the current sort order.
 *
 * @return string Current sort order.
 */
function evie_get_sort_order() {
	$orderby = explode( ' ', get_query_var( 'orderby', 'date' ) );
	if ( ! empty( $orderby ) && isset( $orderby[0] ) ) {
		$orderby = $orderby[0];
	}

	$sort_order = array(
		'orderby' => $orderby,
		'order'   => get_query_var( 'order', 'desc' ),
	);
	if ( false !== strpos( $sort_order['orderby'], 'meta_value' ) ) {
		$meta_key = get_query_var( 'meta_key', '_flext_views' );
		switch ( $meta_key ) {
			case '_flext_likes':
				$sort_order['orderby'] = 'likes';
				$sort_order['order']   = 'desc';
				break;
			case '_flext_views':
				$sort_order['orderby'] = 'views';
				$sort_order['order']   = 'desc';
				break;
		}
	}

	return $sort_order;
}

/**
 * Prints out the filter options.
 *
 * @param array $options An array of options.
 */
function evie_filter_options( $options = array() ) {
	echo '<button class="filter-toggle-button" aria-label="' . esc_attr( esc_html__( 'Toggle filters', 'evie' ) ) . '"><i class="evie-ico-filter"></i></button><div class="filter-options">';

	do_action( 'evie_posts_filter_options', $options );

	echo '</div>';
}

/**
 * Returns query var string.
 *
 * @param WP_Taxonomy $taxonomy  Taxonomy object.
 * @param WP_Term     $term      Term object.
 * @param string      $link_base The link base URL.
 * @return string Link for the term.
 */
function evie_filter_term_link( $taxonomy, $term, $link_base ) {

	$query_vars = array();
	$queries    = $GLOBALS['wp_query']->query;
	$query_var  = isset( $queries[ $taxonomy->query_var ] ) ? $queries[ $taxonomy->query_var ] : get_query_var( $taxonomy->query_var );
	$class      = 'term-item-' . $term->term_id;
	$url        = '';

	if ( ! empty( $query_var ) ) {
		$query_vars = explode( ',', $query_var );
		$index      = array_search( $term->slug, $query_vars, true );
		if ( false !== $index ) {
			array_splice( $query_vars, $index, 1 );
			$class .= ' is-selected';
		} else {
			$query_vars[] = $term->slug;
		}
	} else {
		$query_vars[] = $term->slug;
	}

	if ( ! empty( $query_vars ) ) {
		$query_args                         = array();
		$query_args[ $taxonomy->query_var ] = implode( ',', $query_vars );
		$url                                = add_query_arg( $query_args, $link_base );
	} else {
		$url = remove_query_arg( $taxonomy->query_var, $link_base );
	}

	return sprintf(
		'<li class="%1$s"><a href="%2$s">%3$s</a></li>',
		esc_attr( $class ),
		esc_attr( $url ),
		esc_html( $term->name )
	);
}

/**
 * Retrieves the currently queried term.
 *
 * If queried term is not set, then the queried term will be set from
 * the category, tag, taxonomy query variable.
 *
 * @return WP_Term|null The queried object.
 */
function evie_filter_get_queried_term() {

	$queried_object = get_queried_object();

	if ( ! ( $queried_object instanceof WP_Term ) ) {
		if ( get_query_var( 'term' ) ) {
			$queried_object = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		} elseif ( ! empty( $GLOBALS['wp_query']->tax_query->queried_terms ) ) {
			$queried_taxonomies = array_keys( $GLOBALS['wp_query']->tax_query->queried_terms );
			$matched_taxonomy   = reset( $queried_taxonomies );
			$query              = $GLOBALS['wp_query']->tax_query->queried_terms[ $matched_taxonomy ];

			if ( ! empty( $query['terms'] ) ) {
				if ( 'term_id' === $query['field'] ) {
					$queried_object = get_term( reset( $query['terms'] ), $matched_taxonomy );
				} else {
					$queried_object = get_term_by( $query['field'], reset( $query['terms'] ), $matched_taxonomy );
				}
			}
		}
	}

	return $queried_object;
}

/**
 * Returns whether the follow feature is enabled.
 *
 * @return bool Whether the follow feature is enabled.
 */
function evie_user_is_follow_enabled() {
	if ( ! function_exists( 'flextension_author_follow_enabled' ) ) {
		return false;
	}

	return flextension_author_follow_enabled();
}

/**
 * Returns whether the user has number of following.
 *
 * @param int $user_id The user ID.
 * @return bool Whether the user has number of following.
 */
function evie_user_has_following( $user_id = 0 ) {
	if ( ! evie_user_is_follow_enabled() ) {
		return false;
	}

	if ( ! $user_id ) {
		$user_id = get_current_user_id();

		if ( 0 === $user_id ) {
			return false;
		}
	}

	$following = flextension_author_get_following( $user_id );

	return ! empty( $following );
}

/**
 * Prints out the filters for logged in user on the Post Type Archive page.
 */
function evie_filter_user_following() {

	if ( ! is_user_logged_in() || ! evie_user_is_follow_enabled() ) {
		return;
	}

	$post_type = get_post_type();
	if ( empty( $post_type ) ) {
		$post_type = get_query_var( 'post_type' );
		if ( is_array( $post_type ) ) {
			$post_type = $post_type[0];
		}
	}
	if ( empty( $post_type ) && is_tax() ) {
		$queried_object = get_queried_object();
		if ( $queried_object instanceof WP_Term ) {
			$taxonomy = get_taxonomy( $queried_object->taxonomy );
			if ( $taxonomy instanceof WP_Taxonomy && $taxonomy->object_type ) {
				$post_type = $taxonomy->object_type[0];
			}
		}
	}

	if ( empty( $post_type ) ) {
		$post_type = 'post';
	}

	$post_types = evie_filter_post_types();
	if ( ! in_array( $post_type, $post_types, true ) ) {
		return;
	}

	$all_link = get_post_type_archive_link( $post_type );

	$items = array();

	if ( ! empty( $all_link ) ) {

		$class = 'filter-type-item-all';
		if ( 'following' !== get_query_var( 'filter' ) ) {
			$class .= ' is-selected';
		}

		$items[] = '<li class="' . esc_attr( $class ) . '"><a href="' . esc_url( $all_link ) . '">' . esc_html__( 'Discover', 'evie' ) . '</a></li>';

		$class = 'filter-type-item-following';
		if ( 'following' === get_query_var( 'filter' ) ) {
			$class .= ' is-selected';
		}

		$link = add_query_arg( 'filter', 'following', $all_link );

		$items[] = '<li class="' . esc_attr( $class ) . '"><a href="' . esc_url( $link ) . '">' . esc_html__( 'Following', 'evie' ) . '</a></li>';
	}

	if ( count( $items ) > 0 ) {
		echo '<div class="filter-types"><ul class="terms-list">';
		echo implode( "\n", $items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</ul></div>';
	}

}

/**
 * Returns an array list of the post types to display on the Search page.
 *
 * @return array An array list of the post types.
 */
function evie_search_post_types() {
	$args = array(
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
	);

	$post_types = get_post_types( $args );

	if ( isset( $post_types['attachment'] ) ) {
		unset( $post_types['attachment'] );
	}

	/**
	 * Filters the list of post types to display on the search page.
	 *
	 * @param array $post_types An array list of post types.
	 */
	return apply_filters( 'evie_search_post_types', $post_types );
}

/**
 * Returns an array list of the post types for the filters.
 *
 * @return array An array list of the post types.
 */
function evie_filter_post_types() {
	/**
	 * Filters the list of post types for the filters.
	 *
	 * @param array $post_types An array list of post types.
	 */
	return apply_filters( 'evie_filter_post_types', array( 'post' ) );
}

/**
 * Prints out the filter post types on the Author page.
 */
function evie_filter_author_post_types() {

	$current = get_query_var( 'post_type' ) ? get_query_var( 'post_type' ) : 'post';

	if ( is_array( $current ) ) {
		$current = $current[0];
	}

	$author_id = get_query_var( 'author' );

	$all_link = get_author_posts_url( $author_id );

	$items = array();

	$has_custom_post_type = false;

	$post_types = evie_filter_post_types();

	foreach ( $post_types as $post_type ) {

		if ( ! post_type_supports( $post_type, 'author' ) ) {
			continue;
		}

		/**
		 * If no posts found, then don't show a post type link.
		 *
		 * @since 1.0.1
		 */
		if ( ! evie_author_has_posts( $author_id, $post_type ) ) {
			continue;
		}

		$class = 'post-type-item-' . $post_type;
		if ( $current === $post_type ) {
			$class .= ' is-selected';
		}

		$link = add_query_arg( 'post_type', $post_type, $all_link );

		$label = $post_type;

		$post_type_object = get_post_type_object( $post_type );
		if ( null !== $post_type_object ) {
			$label = $post_type_object->label;
		}

		$items[] = '<li class="' . esc_attr( $class ) . '"><a href="' . esc_url( $link ) . '">' . $label . '</a></li>';

		if ( 'post' !== $post_type ) {
			$has_custom_post_type = true;
		}
	}

	if ( count( $items ) > 0 && true === $has_custom_post_type ) {
		echo '<div class="filter-types"><ul class="terms-list">';
		echo implode( "\n", $items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</ul></div>';
	}

}

/**
 * Prints out the filter types on Archive pages.
 */
function evie_filter_types() {
	if ( is_author() ) {
		evie_filter_author_post_types();
	} elseif ( ! is_search() ) {
		evie_filter_user_following();
	}
}

/**
 * Prints out the filter categories.
 */
function evie_filter_categories() {

	$options = array();
	$items   = array();

	if ( is_search() ) {

		$all_link = get_search_link();

		$options['all_link'] = $all_link;

		$post_types = evie_search_post_types();

		$count = count( $post_types );
		if ( $count > 1 ) {
			$current = get_query_var( 'post_type' ) ? get_query_var( 'post_type' ) : '';

			if ( is_array( $current ) ) {
				$current = $current[0];
			}

			$type_names = array();

			$items[] = sprintf(
				'<li class="post-type-item-all%1$s"><a href="%2$s">%3$s</a></li>',
				( empty( $current ) || 'any' === $current ) ? ' is-selected' : '',
				esc_attr( $all_link ),
				esc_html__( 'All', 'evie' )
			);

			foreach ( $post_types as $post_type ) {

				$type_names[] = $post_type;

				$class = 'post-type-item-' . $post_type;
				if ( $current === $post_type ) {
					$class .= ' is-selected';

					$options['post_type'] = array( $post_type );
				}

				$link = add_query_arg( 'post_type', $post_type, $all_link );

				$label = $post_type;

				$post_type_object = get_post_type_object( $post_type );
				if ( null !== $post_type_object ) {
					$label = $post_type_object->label;
				}

				$items[] = '<li class="' . esc_attr( $class ) . '"><a href="' . esc_url( $link ) . '">' . $label . '</a></li>';
			}

			if ( ! isset( $options['post_type'] ) ) {
				$options['post_type'] = $type_names;
			}
		} elseif ( 1 === $count ) {
			$options['post_type'] = $post_types;
		}
	} else {

		$args = array(
			'all_link'   => '',
			'hide_empty' => 1,
			'taxonomy'   => 'category',
		);

		/**
		 * Filters the list of categories arguments.
		 *
		 * @param string|array $args Array of optional arguments.
		 */
		$args = apply_filters( 'evie_posts_filters_args', $args );

		$current = 0;

		$queried_object = evie_filter_get_queried_term();
		if ( $queried_object instanceof WP_Term ) {
			$taxonomy = get_taxonomy( $queried_object->taxonomy );
			if ( true === $taxonomy->hierarchical ) {
				if ( $queried_object->parent ) {
					$parents = get_ancestors( $queried_object->term_id, $taxonomy->name, 'taxonomy' );
					$parents = array_reverse( $parents );
					$current = $parents[0];
				} else {
					$current = $queried_object->term_id;
				}
				$args['taxonomy'] = $taxonomy->name;
			} else {
				$taxonomy = get_taxonomy( $args['taxonomy'] );
			}
		} else {
			$taxonomy = get_taxonomy( $args['taxonomy'] );
		}

		$options['taxonomy'] = $taxonomy->name;

		$post_type = get_post_type();
		if ( empty( $post_type ) ) {
			$post_type = get_query_var( 'post_type' );
			if ( is_array( $post_type ) ) {
				$post_type = $post_type[0];
			}
		}

		if ( empty( $post_type ) ) {
			if ( $taxonomy instanceof WP_Taxonomy && $taxonomy->object_type ) {
				$post_type = $taxonomy->object_type[0];
			}
		}

		$options['post_type'] = array( $post_type );

		$all_link = $args['all_link'];

		$filter = get_query_var( 'filter' );

		if ( false !== $all_link ) {
			if ( empty( $all_link ) ) {
				$all_link = get_post_type_archive_link( $post_type );

				if ( ! empty( $filter ) ) {
					$all_link = add_query_arg( 'filter', $filter, $all_link );
				}
			}

			if ( is_author() ) {
				$all_link = get_author_posts_url( get_query_var( 'author' ) );
			}

			$class = '';

			if ( 0 === $current ) {
				$class = ' is-selected';

				$options['all_link'] = $all_link;
			}

			$items[] = sprintf(
				'<li class="cat-item-all%1$s"><a href="%2$s">%3$s</a></li>',
				esc_attr( $class ),
				esc_attr( $all_link ),
				esc_html__( 'All', 'evie' )
			);
		}

		$terms = get_terms( $args );

		if ( is_wp_error( $terms ) ) {
			$terms = array();
		}

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {

				if ( is_author() && $taxonomy->query_var ) {
					$link = add_query_arg( $taxonomy->query_var, $term->slug, $all_link );
				} else {
					$link = get_term_link( $term );

					if ( ! empty( $filter ) ) {
						$link = add_query_arg( 'filter', $filter, $link );
					}
				}

				$class = 'cat-item-' . $term->term_id;
				if ( $current === $term->term_id ) {
					$class .= ' is-selected';

					$options['all_link'] = $link;
				}

				$items[] = '<li class="' . esc_attr( $class ) . '"><a href="' . esc_url( $link ) . '">' . $term->name . '</a></li>';
			}
		}
	}

	if ( ! empty( $items ) ) {
		echo '<div class="filter-categories"><ul class="terms-list">';
		echo implode( "\n", $items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</ul></div>';
	}

	evie_filter_options( $options );
}

/**
 * Prints out the filters on archive pages.
 */
function evie_filters() {
	evie_filter_types();
	evie_filter_categories();
}

/**
 * Prints out the sortby options.
 */
function evie_sortby() {

	$sortby_options = array(
		'date-desc'  => array(
			'orderby' => 'date',
			'order'   => 'desc',
			'icon'    => 'evie-ico-date',
			'label'   => esc_html__( 'Newest to oldest', 'evie' ),
		),
		'date-asc'   => array(
			'orderby' => 'date',
			'order'   => 'asc',
			'icon'    => 'evie-ico-date-new',
			'label'   => esc_html__( 'Oldest to newest', 'evie' ),
		),
		'title-asc'  => array(
			'orderby' => 'title',
			'order'   => 'asc',
			'icon'    => 'evie-ico-title',
			'label'   => esc_html__( 'Title A → Z', 'evie' ),
		),
		'title-desc' => array(
			'orderby' => 'title',
			'order'   => 'desc',
			'icon'    => 'evie-ico-title-za',
			'label'   => esc_html__( 'Title Z → A', 'evie' ),
		),
	);

	if ( function_exists( 'flextension_post_likes_button' ) ) {
		$sortby_options['likes'] = array(
			'orderby' => 'likes',
			'order'   => 'desc',
			'icon'    => 'flext-ico-like',
			'label'   => esc_html__( 'Most Liked', 'evie' ),
		);
	}

	if ( function_exists( 'flextension_post_views_button' ) ) {
		$sortby_options['views'] = array(
			'orderby' => 'views',
			'order'   => 'desc',
			'icon'    => 'flext-ico-view',
			'label'   => esc_html__( 'Most Viewed', 'evie' ),
		);
	}

	/**
	 * Filters sort options.
	 *
	 * @param array $sortby_options The list of sort options.
	 */
	$sortby_options = apply_filters( 'evie_posts_sortby_options', $sortby_options );

	if ( ! empty( $sortby_options ) ) {

		$current_sortby = evie_get_sort_order();

		$items = array();

		$selected_item = array();

		$link_base = evie_get_link_base();

		foreach ( $sortby_options as $key => $option ) {
			$item_class = '';

			$order = isset( $option['order'] ) ? $option['order'] : 'asc';

			if ( strtolower( $option['orderby'] ) === strtolower( $current_sortby['orderby'] ) && strtolower( $order ) === strtolower( $current_sortby['order'] ) ) {
				$selected_item = $option;
				$item_class    = ' class="is-selected"';
			}

			$url = add_query_arg(
				array(
					'orderby' => $option['orderby'],
					'order'   => $order,
				),
				$link_base
			);

			$icon = '';
			if ( isset( $option['icon'] ) && ! empty( $option['icon'] ) ) {
				$icon = '<i class="' . esc_attr( $option['icon'] ) . '"></i>';
			}

			$items[] = sprintf(
				'<li%1$s><a href="%2$s">%3$s %4$s</a></li>',
				$item_class,
				esc_url( $url ),
				$icon,
				esc_html( $option['label'] )
			);
		}

		if ( ! empty( $items ) ) {
			if ( empty( $selected_item ) ) {
				$selected_item = wp_parse_args(
					array(
						'icon'  => 'evie-ico-sortby',
						'title' => esc_html__( 'Default', 'evie' ),
					),
					$current_sortby
				);
			}
			?>
		<div class="filter-sortby evie-menu">
			<button class="sortby-button" aria-label="<?php echo esc_attr( esc_html__( 'Sort By', 'evie' ) ); ?>">
				<?php if ( ! empty( $selected_item['icon'] ) ) : ?>
				<i class="<?php echo esc_attr( $selected_item['icon'] ); ?>"></i>
				<?php endif; ?>
				<span><?php echo esc_html__( 'Sort By', 'evie' ); ?></span>
			</button>
			<ul class="evie-list has-scheme-dark">
				<?php echo implode( "\n", $items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</ul>
		</div>
			<?php
		}
	}
}

/**
 * Returns the CSS class to add to the main content container.
 *
 * @param array $args Optional. Additional arguments passed to the template.
 *                    Default empty array.
 * @return string The CSS class for the main content container.
 */
function evie_posts_class( $args = array() ) {
	$classes = array( 'evie-posts' );

	if ( ! empty( $args['class'] ) ) {
		$classes = array_merge( evie_get_classes( $args['class'] ), $classes );
	}

	$post_type = get_query_var( 'post_type' );

	if ( empty( $post_type ) ) {
		$post_type = get_post_type();
	}

	if ( isset( $args['query_vars']['post_type'] ) ) {
		$post_type = $args['query_vars']['post_type'];
	}

	if ( is_array( $post_type ) && count( $post_type ) > 0 ) {
		$post_type = $post_type[0];
	}

	$classes[] = 'posts-type-' . $post_type;

	if ( ! empty( $args['layout'] ) ) {

		if ( in_array( $args['layout'], array( 'grid', 'waterfall', 'carousel' ), true ) ) {

			if ( 'waterfall' === $args['layout'] && true === $args['parallax'] ) {
				$classes[] = 'grid-parallax';
			}

			$classes[] = 'posts-style-' . $args['style'];

			if ( ! empty( $args['hover_effect'] ) ) {
				$classes[] = 'posts-hover-' . $args['hover_effect'];
			}

			if ( 'carousel' !== $args['layout'] ) {
				$classes[] = 'alignwide';
			}
		} else {
			$classes[] = 'posts-style-list';
			$classes[] = 'alignfull';
		}

		$classes[] = 'posts-layout-' . $args['layout'];
	}

	if ( ( $args['show_author'] && 'hide' !== $args['show_author'] ) || true === $args['show_date'] ) {
		$classes[] = 'has-post-footer';
	}

	if ( ! empty( $args['animation'] ) ) {
		$classes[] = 'has-post-animation';
		$classes[] = 'posts-animation-' . $args['animation'];
	}

	return implode( ' ', array_unique( $classes ) );
}

/**
 * Parses the posts arguments.
 *
 * @param array $args Optional. Arguments to generate the posts. Default empty array.
 */
function evie_posts_args( $args = array() ) {
	$post_type = get_query_var( 'post_type' );

	if ( empty( $post_type ) ) {
		$post_type = get_post_type();
	}

	if ( ! empty( $args ) && isset( $args['query_vars'] ) && isset( $args['query_vars']['post_type'] ) ) {
		$post_type = $args['query_vars']['post_type'];
	}

	$prefix = evie_customizer_prefix( $post_type );

	$defaults = array(
		'layout'        => evie_get_theme_setting( 'layout', 'list', $prefix ),
		'parallax'      => evie_get_theme_setting( 'parallax', false, $prefix ),
		'style'         => evie_get_theme_setting( 'style', 'card', $prefix ),
		'hover_effect'  => evie_get_theme_setting( 'hover_effect', 'none', $prefix ),
		'animation'     => evie_get_theme_setting( 'animation', '', $prefix ),
		'columns'       => 3,
		'show_filter'   => evie_get_theme_setting( 'options_filter', false, $prefix ),
		'show_sortby'   => evie_get_theme_setting( 'options_sortby', false, $prefix ),
		'show_title'    => true,
		'show_category' => evie_get_theme_setting( 'category', true, $prefix ) || is_customize_preview(),
		'show_author'   => evie_get_theme_setting( 'author', 'all', $prefix ),
		'show_date'     => evie_get_theme_setting( 'date', true, $prefix ) || is_customize_preview(),
		'show_buttons'  => evie_get_theme_setting( 'buttons', true, $prefix ) || is_customize_preview(),
		'pagination'    => evie_get_theme_setting( 'pagination', 'numbered', $prefix ),
		'class'         => '',
		'image_size'    => '',
		'attrs'         => array(),
		'query_vars'    => array(),
		'post_class'    => array(),
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * Filters the posts arguments.
	 *
	 * @param array $args Array of the posts arguments.
	 */
	$args = apply_filters( 'evie_posts_args', $args );

	if ( ! in_array( $args['layout'], array( 'grid', 'waterfall', 'carousel' ), true ) ) {
		$args['style'] = '';
	}

	$args['attrs'] = array_merge(
		array(
			'class' => evie_posts_class( $args ),
		),
		(array) $args['attrs']
	);

	if ( ! empty( $args['query_vars'] ) ) {
		$query_vars = $args['query_vars'];
		if ( ! isset( $query_vars['paged'] ) ) {
			$query_vars['paged'] = max( 1, get_query_var( 'paged' ) );
		}
		$args['query'] = new WP_Query( $query_vars );
	} else {
		$args['query'] = $GLOBALS['wp_query'];
	}

	return $args;
}

/**
 * Displays the posts.
 *
 * @param array $args Optional. Arguments to generate the posts. See evie_posts_args() for all.
 */
function evie_posts( $args = array() ) {

	$args = evie_posts_args( $args );

	get_template_part( 'template-parts/loop/posts', $args['layout'], $args );
}

/**
 * Includes the posts loop.
 *
 * @param array $args Optional. Arguments to generate the posts. See evie_posts_args() for all.
 */
function evie_posts_loop( $args = array() ) {

	if ( empty( $args ) ) {
		$args = evie_posts_args();
	}

	$is_grid = in_array( $args['layout'], array( 'grid', 'waterfall' ), true );

	$classes = array( 'posts-list' );
	if ( true === $is_grid && absint( $args['columns'] ) > 1 ) {
		$classes[] = 'grid-columns';
		$classes[] = 'has-' . $args['columns'] . '-columns';
	}

	echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">';

	if ( 'waterfall' === $args['layout'] ) {
		echo '<div class="grid-column grid-col-1">';
	}

	while ( $args['query']->have_posts() ) {
		$args['query']->the_post();
		evie_content_template( $args['layout'], '', $args );
	}

	if ( 'waterfall' === $args['layout'] ) {
		echo '</div>';

		$columns = ! empty( $args['columns'] ) ? absint( $args['columns'] ) : 3;

		for ( $i = 2; $i <= $columns; $i++ ) {
			echo '<div class="grid-column grid-col-' . esc_attr( $i ) . '"></div>';
		}
	}

	echo '</div><!-- .posts-list -->';
}

/**
 * Displays the filters for the posts.
 *
 * @param array $args Optional. Arguments to generate the posts. See evie_posts_args() for all.
 */
function evie_posts_filters( $args = array() ) {
	get_template_part( 'template-parts/loop/filters', '', $args );
}

/**
 * Displays the post content in the current template on the blog and archive pages.
 *
 * @param string $name The template name to display.
 * @param string $type The type of the content template.
 * @param array  $args Additional arguments passed to the template.
 */
function evie_content_template( $name = '', $type = '', $args = array() ) {

	if ( empty( $type ) ) {
		$type = get_post_type();
	}

	if ( ! isset( $args['post_class'] ) ) {
		$args['post_class'] = array();
	}

	if ( 'post' === $type && has_post_thumbnail() && isset( $args['style'] ) && 'text-overlay' === $args['style'] ) {
		$args['post_class'][] = 'has-scheme-dark';
	}

	if ( isset( $args['layout'] ) && 'large' === $args['layout'] ) {
		$thumbnail_id = get_post_thumbnail_id();
		if ( false !== $thumbnail_id ) {
			$attrs = wp_get_attachment_image_src( $thumbnail_id, 'evie-wide' );
			if ( ! empty( $attrs ) ) {
				if ( $attrs[1] < $attrs[2] ) {
					$args['post_class'][] = 'has-portrait-thumbnail';
				}
			}
		}
	}

	/**
	 * Filters the list of CSS class names for the current post.
	 *
	 * @param string[] $classes An array of post class names.
	 * @param array    $args    Additional arguments passed to the template.
	 */
	$args['post_class'] = apply_filters( 'evie_post_class', $args['post_class'], $args );

	if ( empty( $name ) ) {
		$prefix = evie_customizer_prefix( $type );
		$name   = evie_get_theme_setting( 'layout', 'list', $prefix );
	}

	if ( 'post' === $type ) {
		$type = get_post_format();
	}

	$template = 'none' === $name ? 'none' : "{$name}/content";

	/*
	 * Include the Post-Layout-specific template for the content.
	 */
	get_template_part( "template-parts/content/{$template}", $type, $args );
}

/**
 * Returns the content for the post content.
 *
 * @param string $name The template name to display.
 * @param string $type The type of the content template.
 * @param array  $args Additional arguments passed to the template.
 * @return string The content for the post content.
 */
function evie_get_content_template( $name = '', $type = '', $args = array() ) {
	ob_start();
	evie_content_template( $name, $type, $args );
	$content = ob_get_clean();

	/**
	 * Filters the post content.
	 *
	 * @param string $name The template name to display.
	 * @param string $type The type of the content template.
	 * @param array  $args Additional arguments passed to the template.
	 */
	return apply_filters( 'evie_content_template', $content, $name, $type, $args );
}

/**
 * Returns HTML content for the featured quote.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @return string The HTML content for the featured audio.
 */
function evie_get_post_quote( $post = 0 ) {
	$block_content = '';

	if ( ! function_exists( 'flextension_get_featured_block' ) ) {
		return $block_content;
	}

	$post = get_post( $post );
	if ( ! $post ) {
		return $block_content;
	}

	if ( has_block( 'core/quote', $post->post_content ) ) {
		$quote_block   = flextension_get_featured_block( 'core/quote', $post->post_content );
		$block_content = render_block( $quote_block );
	} elseif ( has_block( 'core/pullquote', $post->post_content ) ) {
		$quote_block   = flextension_get_featured_block( 'core/pullquote', $post->post_content );
		$block_content = render_block( $quote_block );
	}

	return '<div class="post-quote">' .

				sprintf(
					'<a href="%1$s" aria-hidden="true" tabindex="-1">%2$s</a>',
					esc_url( get_permalink( $post ) ),
					wp_kses(
						$block_content,
						array(
							'blockquote' => array(),
							'p'          => array(),
							'cite'       => array(),
						)
					)
				)

			. '</div><!-- .post-quote -->';
}

/**
 * Returns a 'Read more' link to append to the post excerpt.
 *
 * @param int|WP_Post $post    Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string      $url     Link URL.
 * @param string      $text    Link text. Default 'Read more'.
 * @param string      $tooltip Link tooltip. Default 'Continue reading %s'.
 * @return string A 'Read more' link HTML.
 */
function evie_get_more_link( $post = 0, $url = '', $text = '', $tooltip = '' ) {

	if ( empty( $url ) ) {
		$url = get_permalink( $post );
	}

	if ( empty( $text ) ) {
		$text = esc_html__( 'Read more', 'evie' );
	}

	if ( empty( $tooltip ) ) {
		$tooltip = sprintf(
			/* translators: %s: Post title. */
			esc_html__( 'Continue reading %s', 'evie' ),
			the_title_attribute(
				array(
					'before' => '"',
					'after'  => '"',
					'echo'   => false,
					'post'   => $post,
				)
			)
		);
	}

	return sprintf(
		'<a href="%1$s" title="%2$s" class="more-link">
			<span>%3$s</span>
			<i class="evie-arrow-icon more-button-icon">
			</i>
		</a>',
		esc_url( $url ),
		esc_attr( $tooltip ),
		esc_html( $text )
	);
}

/**
 * Prints out a 'Read more' link.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object.  Default is global `$post`.
 * @param string      $url  Link URL.
 * @param string      $text Link text. Default 'Read more'.
 */
function evie_more_link( $post = 0, $url = '', $text = '' ) {
	echo evie_get_more_link( $post, $url, $text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Gets the link base URL.
 *
 * @return string The link base URL.
 */
function evie_get_link_base() {
	$base = false;

	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {

		$base = wp_get_referer();

		if ( empty( $base ) ) {
			// If front page is set to display a static page, get the URL of the posts page.
			if ( 'page' === get_option( 'show_on_front' ) ) {
				$base = get_permalink( get_option( 'page_for_posts' ) );
			} else {
				$base = get_home_url();
			}
		}
	}

	return $base;
}

/**
 * Retrieves a paginated navigation to next/previous set of posts, when applicable.
 *
 * @global WP_Query $wp_query
 *
 * @param string $type     The pagination type.
 * @param int    $total    Total pages.
 * @param int    $current  Current page.
 * @return string The HTML output for the next/previous page link.
 */
function evie_posts_pagination( $type, $total = 0, $current = 0 ) {
	global $wp_query;

	if ( 'none' === $type ) {
		return;
	}

	$class      = '';
	$page_links = '';

	if ( ! $total ) {
		$total = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
	}

	if ( ! $current ) {
		$current = max( 1, get_query_var( 'paged' ) );
	}

	$current = absint( $current );

	switch ( $type ) {
		case 'next_previous':
			$class      = 'next-previous-pagination';
			$page_links = evie_next_previous_pagination( $total, $current );

			break;
		case 'numbered':
			$class      = 'numbered-pagination';
			$args       = array(
				'total'   => $total,
				'current' => $current,
			);
			$page_links = evie_numbered_pagination( $args );

			break;
		default:
			$page_link = '';
			$class     = 'loadmore-pagination';

			if ( 'scroll' === $type ) {
				$class .= ' infinite-scroll';
			}

			if ( 1 !== $total && $current < $total ) {
				$page_link = get_pagenum_link( $current + 1 );
			}

			if ( ! empty( $page_link ) ) {
				$page_links = evie_posts_pagination_link( $page_link );
			}

			break;
	}

	echo evie_get_pagination( $page_links, $class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Gets the posts pagination link.
 *
 * @param string $page_link The page link.
 * @return string The HTML output for the posts pagination link.
 */
function evie_posts_pagination_link( $page_link = '' ) {
	return '<a href="' . esc_url( $page_link ) . '" class="next">' . esc_html__( 'Load more', 'evie' ) . '<i></i></a><div class="post-loader"></div><p class="post-status"></p>';
}

/**
 * Returns the next and previous posts pagination.
 *
 * @param int $total   Total pages.
 * @param int $current The current page.
 * @return string The HTML output for the posts pagination.
 */
function evie_next_previous_pagination( $total = 0, $current = 0 ) {
	if ( ! $current ) {
		$current = max( 1, get_query_var( 'paged' ) );
	}

	$pagination = '';

	// Previous link.
	if ( $current > 1 ) {
		$label = '<i class="evie-ico-left"></i><span>' . esc_html__( 'Newer', 'evie' ) . '</span>';

		$pagination = get_previous_posts_link( $label );
	}

	// Current link.
	if ( $total > 1 ) {
		$pagination .= '<span class="current">' . esc_html( $current ) . '</span>';
	}

	// Next link.
	$nextpage = absint( $current ) + 1;
	if ( $nextpage <= $total ) {
		$label = '<span>' . esc_html__( 'Older', 'evie' ) . '</span><i class="evie-ico-right"></i>';

		$pagination .= get_next_posts_link( $label, $total );
	}

	return $pagination;
}

/**
 * Retrieves a numbered pagination.
 *
 * @param array $args {
 *     Optional. Default pagination arguments, see paginate_links().
 *
 *     @type string $screen_reader_text Screen reader text for navigation element.
 *                                      Default 'Posts navigation'.
 *     @type string $aria_label         ARIA label text for the nav element. Default 'Posts'.
 *     @type string $class              Custom class for the nav element. Default 'pagination'.
 * }
 * @return string Markup for pagination links.
 */
function evie_numbered_pagination( $args = array() ) {
	// Make sure the nav element has an aria-label attribute: fallback to the screen reader text.
	if ( ! empty( $args['screen_reader_text'] ) && empty( $args['aria_label'] ) ) {
		$args['aria_label'] = $args['screen_reader_text'];
	}

	$args = wp_parse_args(
		$args,
		array(
			'end_size'  => 0,
			'mid_size'  => 1,
			'prev_text' => sprintf( '<i class="evie-ico-left"></i><span>%s</span>', esc_html__( 'Newer', 'evie' ) ),
			'next_text' => sprintf( '<span>%s</span><i class="evie-ico-right"></i>', esc_html__( 'Older', 'evie' ) ),
		)
	);

	// Make sure we get a string back. Plain is the next best thing.
	if ( isset( $args['type'] ) && 'array' === $args['type'] ) {
		$args['type'] = 'plain';
	}

	// Set up paginated links.
	return paginate_links( $args );
}

/**
 * Gets the pagination navigation.
 *
 * @param string $page_links The page links.
 * @param string $class      The pagination class name.
 * @return string The HTML output for the posts pagination.
 */
function evie_get_pagination( $page_links = '', $class = '' ) {

	if ( empty( $page_links ) ) {
		return '';
	}

	$output = sprintf(
		'<nav class="navigation pagination %1$s"><div class="nav-links">%2$s</div></nav><!-- .navigation -->',
		$class,
		$page_links
	);

	return $output;
}

/**
 * Returns the URL of the post page.
 *
 * @global WP_Rewrite $wp_rewrite
 *
 * @param int $i Page number.
 * @return string URL of the post page.
 */
function evie_link_page_url( $i ) {
	global $wp_rewrite;

	$post       = get_post();
	$query_args = array();

	if ( 1 === $i ) {
		$url = get_permalink();
	} else {
		$permalink = get_option( 'permalink_structure' );
		if ( empty( $permalink ) || in_array( $post->post_status, array( 'draft', 'pending' ), true ) ) {
			$url = add_query_arg( 'page', $i, get_permalink() );
		} elseif ( 'page' === get_option( 'show_on_front' ) && absint( get_option( 'page_on_front' ) ) === $post->ID ) {
			$url = trailingslashit( get_permalink() ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
		} else {
			$url = trailingslashit( get_permalink() ) . user_trailingslashit( $i, 'single_paged' );
		}
	}

	if ( is_preview() ) {

		if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$query_args['preview_id']    = absint( wp_unslash( $_GET['preview_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$query_args['preview_nonce'] = sanitize_key( $_GET['preview_nonce'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		$url = get_preview_post_link( $post, $query_args, $url );
	}

	return $url;
}

/**
 * Returns whether the footer can be displayed.
 *
 * @param int $id The post ID.
 * @return bool Whether the footer can be displayed.
 */
function evie_has_footer( $id = 0 ) {
	if ( empty( $id ) ) {
		$id = evie_get_page_id();
	}
	return false === (bool) get_post_meta( $id, '_evie_hide_footer', true );
}

/**
 * Return whether the footer gap is visible.
 *
 * @param int $id The post ID.
 * @return bool Whether the footer gap is visible.
 */
function evie_has_footer_gap( $id = 0 ) {

	if ( ( is_archive() && ! is_post_type_archive() ) ) {
		return true;
	}

	if ( empty( $id ) ) {
		$id = evie_get_page_id();
	}

	return false === (bool) get_post_meta( $id, '_evie_hide_footer_gap', true );
}

/**
 * Determines if the Footer Logo can be displayed.
 *
 * @return bool Whether the Footer Logo can be displayed.
 */
function evie_has_footer_logo() {
	return (bool) get_theme_mod( 'footer_logo', 0 );
}

/**
 * Prints out the footer columns and widgets.
 *
 * @param int $columns Number of columns.
 */
function evie_footer_columns( $columns = 1 ) {
	if ( is_customize_preview() ) {
		for ( $i = 1; $i <= 4; $i++ ) {
			$name = 'footer-' . $i;

			$class = 'footer-col-' . esc_attr( $i );
			if ( $i > $columns ) {
				$class .= ' is-hidden';
			}

			echo '<div class="' . esc_attr( $class ) . '">';
			dynamic_sidebar( $name );
			echo '</div>';
		}
	} else {
		for ( $i = 1; $i <= $columns; $i++ ) {
			$name = 'footer-' . $i;
			if ( is_active_sidebar( $name ) ) {
				echo '<div class="footer-col-' . esc_attr( $i ) . '">';
				dynamic_sidebar( $name );
				echo '</div>';
			}
		}
	}
}

/**
 * Displays footer widgets.
 */
function evie_footer_widgets() {
	get_template_part( 'template-parts/footer/footer', 'widgets', array( 'columns' => get_theme_mod( 'footer_widgets', '' ) ) );
}

/**
 * Displays footer info.
 */
function evie_footer_info() {
	get_template_part( 'template-parts/footer/footer', 'info' );
}

/**
 * Displays Footer Logo.
 */
function evie_footer_logo() {
	$html           = '';
	$footer_logo_id = absint( get_theme_mod( 'footer_logo', 0 ) );
	// We have a logo. Logo is go.
	if ( $footer_logo_id ) {
		$footer_logo_attr = array();

		$footer_logo_retina_id = absint( get_theme_mod( 'footer_logo_retina', 0 ) );

		if ( $footer_logo_retina_id ) {

			$srcsets = array();

			$srcsets[] = wp_get_attachment_image_url( $footer_logo_id, 'full' ) . ' 1x';

			$srcsets[] = wp_get_attachment_image_url( $footer_logo_retina_id, 'full' ) . ' 2x';

			$footer_logo_attr['srcset'] = implode( ', ', $srcsets );

			unset( $footer_logo_attr['sizes'] );

		}

		/*
		* If the logo alt attribute is empty, get the site title and explicitly
		* pass it to the attributes used by wp_get_attachment_image().
		*/
		$image_alt = get_post_meta( $footer_logo_id, '_wp_attachment_image_alt', true );
		if ( empty( $image_alt ) ) {
			$footer_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
		}

		/*
		* If the alt attribute is not empty, there's no need to explicitly pass
		* it because wp_get_attachment_image() already adds the alt attribute.
		*/
		echo sprintf(
			'<a href="%1$s" class="footer-logo-link" rel="home">%2$s</a>',
			esc_url( home_url( '/' ) ),
			wp_get_attachment_image( $footer_logo_id, 'full', false, $footer_logo_attr )
		);

	} elseif ( is_customize_preview() ) {
		// If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
		echo sprintf(
			'<a href="%1$s" class="footer-logo-link" style="display:none;"><img /></a>',
			esc_url( home_url( '/' ) )
		);
	}
}

/**
 * Displays footer menu.
 */
function evie_footer_menu() {
	if ( ( 'show' === get_theme_mod( 'footer_menu', '' ) || is_customize_preview() ) && has_nav_menu( 'footer' ) ) {
		echo '<div class="footer-menu-wrapper">';

		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'menu_id'        => 'footer-menu',
				'menu_class'     => 'footer-menu',
				'container'      => false,
			)
		);

		echo '<!-- #footer-menu -->';
		echo '</div><!-- .footer-menu-wrapper -->';
	}
}

/**
 * Displays footer social links.
 */
function evie_footer_social_links() {
	$footer_links = get_theme_mod( 'footer_links', '' );
	if ( function_exists( 'flextension_social_icons_widget' ) && ( ! empty( $footer_links ) || is_customize_preview() ) ) {
		echo '<div class="footer-social-links">';
		if ( ! empty( $footer_links ) ) {
			$args = array(
				'style' => 'icons' === $footer_links ? '' : $footer_links,
			);
			echo flextension_social_icons_widget( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</div><!-- .footer-social-links -->';
	}
}

/**
 * Returns default Site Info.
 *
 * @return string Default site info.
 */
function evie_default_site_info() {
	$site_title = get_bloginfo( 'name' );
	if ( empty( $site_title ) ) {
		$site_title = 'Evie';
	}
	return '© ' . gmdate( 'Y' ) . ' ' . $site_title . '. Proudly powered by WordPress.';
}

/**
 * Displays Site Info in the footer.
 */
function evie_footer_text() {
	$html = get_theme_mod( 'footer_text', evie_default_site_info() );
	if ( ! empty( $html ) || is_customize_preview() ) {
		echo '<span class="footer-copyright">' . wp_kses_data( $html ) . '</span><!-- .footer-copyright -->';
	}
}
