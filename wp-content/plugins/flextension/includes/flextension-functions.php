<?php
/**
 * Helper functions for plugin.
 *
 * @package    Flextension
 * @subpackage Includes
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return the template dir path.
 *
 * @since 1.0.6
 *
 * @param string $template_name The template name.
 * @return string The template dir path.
 */
function flextension_template_dir_path( $template_name = '' ) {
	/**
	 * Filters the template dir path.
	 *
	 * @param string $path          The template dir path.
	 * @param string $template_name The template name.
	 */
	return apply_filters( 'flextension_template_dir_path', 'flextension', $template_name );
}

/**
 * Returns "theme support" values from the current theme, if set.
 *
 * @param  string $name Name of property. Leave blank to get all props as an array.
 * @param  mixed  $default Optional value to return if the theme does not declare support for a prop.
 * @return mixed  The property value(s).
 */
function flextension_get_theme_support( $name = '', $default = '' ) {
	$theme_support = get_theme_support( 'flextension' );

	if ( is_array( $theme_support ) && isset( $theme_support[0] ) ) {
		$theme_support = $theme_support[0];
	} else {
		return $default;
	}

	$settings = array();

	if ( ! empty( $name ) ) {
		if ( isset( $theme_support[ $name ] ) ) {
			$settings = $theme_support[ $name ];
		}
	} else {
		$settings = $theme_support;
	}

	$value = $settings;
	if ( is_array( $settings ) && is_array( $default ) ) {
		$value = wp_parse_args( $settings, $default );
	}

	/**
	 * Filters the theme support settings.
	 *
	 * @param mixed  $value The property value(s).
	 * @param string $name  The name of property. Leave blank to get all props as an array.
	 */
	return apply_filters( 'flextension_get_theme_support', $value, $name );
}

/**
 * Returns whether the active theme supports a specific module in the Flextension plugin.
 *
 * @param bool  $supports Whether the active theme supports the given feature. Default true.
 * @param array $args     Array of arguments for the feature.
 * @param array $features The theme feature.
 * @return bool Whether the active theme supports the given feature. Default true.
 */
function flextension_current_theme_supports( $supports, $args, $features ) {
	if ( $args && ! empty( $args[0] ) ) {
		$name = $args[0];
		if ( isset( $features[0][ $name ] ) ) {
			$supports = ( false !== $features[0][ $name ] );
		}
	}
	return $supports;
}

add_filter( 'current_theme_supports-flextension', 'flextension_current_theme_supports', 10, 3 );

/**
 * Includes files to the plugin.
 *
 * @param string $pattern The pattern to search for files.
 * @param string $dir     The directory path.
 */
function flextension_load_files( $pattern, $dir = '' ) {

	if ( empty( $dir ) ) {
		$dir = FLEXTENSION_PATH;
	}

	$path = wp_normalize_path( $dir . $pattern );

	$files = glob( $path );

	if ( false === $files || empty( $files ) ) {
		return;
	}

	foreach ( $files as $file ) {
		if ( is_file( $file ) ) {
			require_once wp_normalize_path( $file );
		}
	}
}

/**
 * Retrieves the path of the highest priority template file that exists.
 *
 * This is the load order:
 *
 * yourtheme/<template_path>/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialized template.
 * @return string
 */
function flextension_locate_template( $slug, $name = null ) {

	$slug = preg_replace( '/\\.[^.\\s]{3,4}$/', '', $slug );

	$template_name = basename( $slug );

	$templates = array();

	$template_dir_path = flextension_template_dir_path( $template_name );

	if ( ! empty( $name ) ) {
		$templates[] = trailingslashit( $template_dir_path ) . "{$template_name}-{$name}.php";
	}

	$templates[] = trailingslashit( $template_dir_path ) . "{$template_name}.php";

	$template = locate_template( $templates );

	if ( ! $template ) {
		$template = flextension_get_default_template( $slug, $name );
	}

	$template = wp_normalize_path( $template );

	/**
	 * Filters the path of the template file.
	 *
	 * @param string $template      The path of the template file.
	 * @param string $template_name The template name.
	 */
	return apply_filters( 'flextension_locate_template', $template, $template_name );
}

/**
 * Loads the template file with query variables.
 *
 * @param string $template_slug The slug name for the generic template.
 * @param string $template_name The name of template to load.
 * @param array  $args          Array of the variables. (default: array).
 */
function flextension_get_template( $template_slug, $template_name = null, $args = array() ) {
	$cache_key = sanitize_key( implode( '-', array( 'template', $template_slug, $template_name, flextension_get_setting( 'version' ) ) ) );

	$template = (string) wp_cache_get( $cache_key, 'flextension' );

	if ( empty( $template ) ) {
		$template = flextension_locate_template( $template_slug, $template_name );
		wp_cache_set( $cache_key, $template, 'flextension', HOUR_IN_SECONDS );
	}

	if ( ! empty( $template ) ) {
		include $template;
	}
}

/**
 * Retrieves default template path.
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialized template.
 */
function flextension_get_default_template( $slug, $name = null ) {

	$slug = preg_replace( '/\\.[^.\\s]{3,4}$/', '', $slug );

	$templates = array();

	if ( ! empty( $name ) ) {
		$templates[] = "{$slug}-{$name}.php";
	}

	$templates[] = "{$slug}.php";

	$template = '';

	foreach ( $templates as $template_file ) {
		if ( file_exists( $template_file ) ) {
			$template = $template_file;
			break;
		}
	}

	return $template;
}

/**
 * Returns a setting value. Alias of flextension()->get_setting().
 *
 * @see flextension()->get_setting()
 *
 * @param string $name  The name.
 * @param mixed  $value The value.
 */
function flextension_get_setting( $name, $value = null ) {
	$value = flextension()->get_setting( $name );
	/**
	 * Filters the setting value
	 *
	 * @param string $value The current value.
	 */
	return apply_filters( "flextension_settings_{$name}", $value );
}

/**
 * Updates a setting value. Alias of flextension()->update_setting()
 *
 * @see flextension()->update_setting()
 *
 * @param string $name  The name.
 * @param mixed  $value The value.
 */
function flextension_update_setting( $name, $value ) {
	return flextension()->update_setting( $name, $value );
}

/**
 * Retrieves the data contents from the dataset by name.
 *
 * @param string $name The name.
 */
function flextension_get_data( $name ) {
	return flextension()->get_data( $name );
}

/**
 * Saves the data to the dataset.
 *
 * @param string $name  The name.
 * @param mixed  $value The value.
 */
function flextension_set_data( $name, $value ) {
	return flextension()->set_data( $name, $value );
}

/**
 * Returns an absolute URL for the documentation.
 *
 * @since 1.0.6
 *
 * @param string $path The path of the content section.
 * @return string An absolute URL for the documentation.
 */
function flextension_get_doc_url( $path = '' ) {
	if ( ! empty( $path ) ) {
		$path = '#' . $path;
	}
	return trailingslashit( flextension_get_setting( 'documentation' ) ) . $path;
}

/**
 * Creates and returns a new string by concatenating a key which has a true value,
 * separated by a specified separator string.
 *
 * @param string $separator Whether to include the class name.
 * @param array  $values    An associative array to join.
 * @return string A new string separated by a specified separator.
 */
function flextension_join( $separator = ' ', $values = '' ) {
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
function flextension_get_attribute( $name, $value = '', $empty_value = false ) {
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
 * Returns HTML attributes string.
 *
 * @param array $attributes   Array of attributes to append to HTML element.
 * @param bool  $space_before Whether to add a space before the output.
 * @return string HTML attribute string.
 */
function flextension_get_attributes( $attributes = array(), $space_before = true ) {
	$attrs = array();
	foreach ( $attributes as $name => $value ) {
		if ( ! empty( $name ) ) {
			$attrs[] = flextension_get_attribute( $name, $value );
		}
	}

	$output = '';
	if ( ! empty( $attrs ) ) {

		$output = implode( ' ', $attrs );

		if ( true === $space_before ) {
			$output = ' ' . $output;
		}
	}

	return $output;
}

/**
 * Prints out or returns HTML attributes string.
 *
 * @param array $attributes   Array of attributes to append to HTML element.
 * @param bool  $space_before Whether to add a space before the output.
 */
function flextension_attributes( $attributes = array(), $space_before = false ) {
	echo flextension_get_attributes( $attributes, $space_before ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Returns an array of CSS classes.
 *
 * @param string|string[] $class Space-separated string or array of class names.
 * @return string[] $classes An array of class names.
 */
function flextension_get_classes( $class = '' ) {
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
function flextension_class_name( $class = '', $include = true, $space_before = false, $echo = false ) {
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
function flextension_class_names( $classes = array(), $space_before = false, $echo = false ) {
	$output = '';
	$prefix = $space_before ? ' ' : '';

	if ( is_array( $classes ) && ! empty( $classes ) ) {
		$output = flextension_join( ' ', $classes );
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
 * Returns unique slug name to refer to this menu by.
 *
 * @param string $slug The page slug.
 */
function flextension_get_page_slug( $slug ) {
	return sprintf( 'flextension-%s', $slug );
}

/**
 * Returns admin page.
 *
 * @param string $type The type page.
 * @return string The admin page.
 */
function flextension_get_admin_page( $type = 'general' ) {
	$page = '';
	switch ( $type ) {
		case 'general':
			$page = 'options-general.php';
			break;
		case 'writing':
			$page = 'options-writing.php';
			break;
		case 'reading':
			$page = 'options-reading.php';
			break;
		case 'discussion':
			$page = 'options-reading.php';
			break;
		case 'media':
			$page = 'options-media.php';
			break;
		case 'permalink':
			$page = 'options-permalink.php';
			break;
		case 'themes':
			$page = 'themes.php';
			break;
		case 'admin':
			$page = 'admin.php';
			break;
		case 'edit':
			$page = 'edit.php';
			break;
		default:
			$page = $type;
			break;
	}
	return $page;
}

/**
 * Returns admin page URL.
 *
 * @see flextension_get_admin_page()
 *
 * @param string $slug A page slug.
 * @param string $type A type of admin menu. See flextension_get_admin_page() for all.
 * @param string $tab  A tab slug.
 * @return string An admin page URL.
 */
function flextension_get_admin_page_url( $slug, $type = 'general', $tab = '' ) {
	$page_url = admin_url( flextension_get_admin_page( $type ) );

	$query_args = array();
	if ( ! empty( $slug ) ) {
		$query_args['page'] = flextension_get_page_slug( $slug );
	}

	if ( ! empty( $tab ) ) {
		$query_args['tab'] = $tab;
	}

	if ( ! empty( $query_args ) ) {
		$page_url = add_query_arg( $query_args, $page_url );
	}

	return $page_url;
}

/**
 * Converts a number into a short format.
 * For example:
 * 1000         => 1K
 * 1000000      => 1M
 * 1000000000   => 1B
 *
 * @param int $number  The number to convert.
 * @param int $decimal Precision of the number of decimal places. Default 1.
 * @return string The short format of the number.
 */
function flextension_number_format( $number, $decimal = 1 ) {

	// First strip any formatting.
	$number = (float) str_replace( ',', '', $number );

	// Is this a number?
	if ( ! is_numeric( $number ) ) {
		return false;
	}

	if ( $number < 1000 ) {
		return number_format_i18n( $number );
	}

	$suffix = array(
		1000000000 => esc_html__( 'B', 'flextension' ), // Billion.
		1000000    => esc_html__( 'M', 'flextension' ), // Million.
		1000       => esc_html__( 'K', 'flextension' ), // Thousand.
	);

	foreach ( $suffix as $value => $text ) {
		if ( $number >= $value ) {
			return round( $number / $value, $decimal ) . $text;
		}
	}
}

/**
 * Retrieves all modules.
 *
 * @return array An array list of all modules.
 */
function flextension_get_modules() {
	$modules = flextension_get_data( 'modules' );

	/**
	 * Filters array list of all modules.
	 *
	 * @param array $modules An array list of all modules.
	 */
	return apply_filters( 'flextension_modules', $modules );
}

/**
 * Updates modules list.
 *
 * @param array $modules An array list of all modules.
 */
function flextension_update_modules( $modules ) {
	flextension_set_data( 'modules', $modules );
}

/**
 * Returns a version number of the modules.
 *
 * @since 1.0.4
 *
 * @return string A version number of the modules.
 */
function flextension_modules_version() {
	/**
	 * Filters the modules version.
	 *
	 * @since 1.0.4
	 *
	 * @param string $version The number of version.
	 */
	return apply_filters( 'flextension_modules_version', FLEXTENSION_VERSION );
}

/**
 * Register a module into modules dataset.
 *
 * @param string $file The module file path.
 * @param array  $args {
 *     Optional. Array of arguments for registering a module. Default empty array.
 *
 *     @type string        $name                A module name.
 *     @type string        $title               Human-readable module label.
 *     @type string        $description         A module description.
 *     @type string|null   $category            Module category classification, used in modules list.
 *     @type string|null   $type                Module type. Accepts 'module' and 'extension'.
 *     @type bool          $public              Whether the module will be showed in the modules list.
 *     @type bool          $enabled             Whether the module is enabled.
 *     @type int           $priority            List the modules in order of priority.
 *     @type array         $dependencies        Array list of the dependencies.
 *     @type array         $actions             Array list of the action links.
 *     @type array         $links               Array list of the links.
 *     @type callable|null $load_callback       A callback function when the module is loaded.
 *     @type callable|null $activate_callback   A callback function when the module is activated.
 *     @type callable|null $deactivate_callback A callback function when the module is deactivated.
 * }
 */
function flextension_register_module( $file, $args = array() ) {
	if ( isset( $args['name'] ) && ! empty( $args['name'] ) ) {
		$name = $args['name'];
	} elseif ( ! empty( $file ) ) {
		$name = basename( dirname( $file ) );
	}

	if ( ! empty( $name ) ) {

		$args['file'] = wp_normalize_path( $file );

		$module = new Flextension_Module( $name, $args );

		// Retrieve the modules list.
		$modules = flextension_get_modules();

		// Add the module into the list.
		$modules[ $name ] = $module;

		flextension_update_modules( $modules );
	}
}

/**
 * Registers modules and extensions.
 *
 * @since 1.0.4
 *
 * @return array An array list of registered modules.
 */
function flextension_register_modules() {
	// Load extensions.
	flextension_load_files( 'extensions/*/index.php' );

	/**
	 * Fires when registering the extensions.
	 */
	do_action( 'flextension_register_extensions' );

	// Load modules.
	flextension_load_files( 'modules/*/index.php' );

	/**
	 * Fires when registering the modules.
	 */
	do_action( 'flextension_register_modules' );

	$registered_modules = array();

	// Retrieve all modules from the list.
	$modules = flextension_get_modules();
	if ( ! empty( $modules ) ) {
		$settings       = get_option( 'flext_modules', array() );
		$active_modules = array();
		if ( ! empty( $settings ) ) {
			if ( isset( $settings['modules'] ) ) {
				$active_modules = $settings['modules'];
			} else {
				// If using the old settings structure, then migrate to the new one.
				delete_option( 'flext_settings' );
				delete_transient( 'flext_settings' );
				foreach ( $settings as $name => $active ) {
					$active_modules[ $name ] = array(
						'active' => (bool) $active,
					);
				}
			}
		} else {
			/**
			 * Fires when all modules are first installed.
			 */
			do_action( 'flextension_install_modules' );
		}

		foreach ( $modules as $name => $module ) {

			if ( isset( $active_modules[ $name ] ) ) {
				$module->enabled = (bool) $active_modules[ $name ]['active'];
			}

			if ( true !== $module->enabled ) {
				$module->enabled = flextension_module_is_required( $module );
			}

			$registered_modules[ $name ] = array(
				'file'   => flextension_get_module_basename( $module->file ),
				'active' => $module->enabled,
			);
		}

		// Update modules in the list.
		flextension_update_modules( $modules );

		// Save active modules.
		update_option(
			'flext_modules',
			array(
				'version' => flextension_modules_version(),
				'modules' => $registered_modules,
			)
		);
	}

	return $registered_modules;
}

/**
 * Returns an array list of registered modules.
 *
 * @since 1.0.4
 *
 * @return array An array list of registered modules.
 */
function flextension_get_registered_modules() {
	$modules  = array();
	$settings = get_option( 'flext_modules', array() );
	if ( ! empty( $settings ) && isset( $settings['version'] ) && flextension_modules_version() === $settings['version'] ) {
		$modules = $settings['modules'];
	} else {
		$modules = flextension_register_modules();
	}
	return $modules;
}

/**
 * Returns a relative path for the module file.
 *
 * @since 1.1.3
 *
 * @param string $file An absolute path of the module file.
 * @return string A relative path for the module file.
 */
function flextension_get_module_basename( $file = '' ) {
	$file       = wp_normalize_path( $file );
	$plugin_dir = wp_normalize_path( WP_PLUGIN_DIR );
	// Get relative path from 'plugins' directory.
	$file = preg_replace( '#^' . preg_quote( $plugin_dir, '#' ) . '/#', '', $file );
	$file = trim( $file, '/' );
	return $file;
}

/**
 * Returns an absolute path for the module file.
 *
 * @since 1.1.3
 *
 * @param string $file A relative path of the module file.
 * @return string An absolute path for the module file.
 */
function flextension_get_module_file( $file = '' ) {
	return wp_normalize_path( WP_PLUGIN_DIR . '/' . $file );
}

/**
 * Loads modules and extensions.
 */
function flextension_load_modules() {
	$registered_modules = flextension_get_registered_modules();
	if ( ! empty( $registered_modules ) ) {
		// Clear all loaded modules.
		flextension_update_modules( array() );
		foreach ( $registered_modules as $name => $module ) {
			if ( true === (bool) $module['active'] ) {
				if ( ! empty( $module['file'] ) ) {
					$file = wp_normalize_path( flextension_get_module_file( $module['file'] ) );
					if ( file_exists( $file ) ) {
						include_once $file;
					}
				}
			}
		}
	}

	$modules = flextension_get_modules();
	if ( ! empty( $modules ) ) {
		foreach ( $modules as $name => $module ) {
			$module->load();
		}
	}
}

/**
 * Returns the module object.
 *
 * @param string|Flextension_Module $module Module name or module object.
 * @return Flextension_Module The module object.
 */
function flextension_get_module( $module ) {
	$_module = null;
	if ( $module instanceof Flextension_Module ) {
		$_module = $module;
	} elseif ( is_string( $module ) ) {
		$modules = flextension_get_modules();
		if ( ! empty( $modules ) && isset( $modules[ $module ] ) ) {
			$_module = $modules[ $module ];
		}
	}

	return $_module;
}

/**
 * Retrieves dependents (modules) requiring given dependency.
 *
 * @param string $dependency The module dependency.
 * @return array An array list of modules.
 */
function flextension_get_dependents( $dependency ) {
	$dependents = array();
	if ( ! empty( $dependency ) ) {
		$modules = flextension_get_modules();
		foreach ( $modules as $name => $module ) {
			if ( ! empty( $module->dependencies ) && in_array( $dependency, $module->dependencies, true ) ) {
				$dependents[] = $module;
			}
		}
	}

	return $dependents;
}

/**
 * Whether the module is required by another module.
 *
 * @param Flextension_Module $module The module instance.
 * @return bool Whether the module is required by another module.
 */
function flextension_module_is_required( $module ) {
	$required   = false;
	$dependents = flextension_get_dependents( $module->name );
	if ( ! empty( $dependents ) ) {
		foreach ( $dependents as $name => $dep ) {
			if ( $name !== $module->name && ( true === $dep->enabled || true === flextension_module_is_required( $dep ) ) ) {
				$required = true;
				break;
			}
		}
	}
	return $required;
}
