<?php
/**
 * Customize Sanitizer
 *
 * @package    Evie
 * @subpackage Customizer
 * @version    1.0.0
 */

/**
 * Sanitizes a select value of the setting.
 *
 * @param mixed                $value   Value of the setting.
 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
 * @return string Sanitized value if valid, or default value.
 */
function evie_customize_sanitize_select( $value, $setting ) {
	// Get the list of possible select options.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	return array_key_exists( $value, $choices ) ? $value : $setting->default;
}

/**
 * Sanitizes a dropdown value of the setting.
 *
 * @param mixed                $value   Value of the setting.
 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
 * @return string Sanitized value if valid, or default value.
 */
function evie_customize_sanitize_dropdown( $value, $setting ) {
	$choices = array();
	// Get the list of possible select options.
	$groups = $setting->manager->get_control( $setting->id )->groups;
	if ( is_array( $groups ) && ! empty( $groups ) ) {
		foreach ( $groups as $name => $group ) {
			if ( is_array( $group['options'] ) && ! empty( $group['options'] ) ) {
				foreach ( $group['options'] as $key => $label ) {
					$choices[ $key ] = $label;
				}
			}
		}
	} else {
		$choices = $setting->manager->get_control( $setting->id )->choices;
	}

	return array_key_exists( $value, $choices ) ? $value : $setting->default;
}

/**
 * Sanitizes a checkbox value of the setting.
 *
 * @param mixed $value Value of the setting.
 * @return string True if checkbox is checked. Otherwise, false.
 */
function evie_customize_sanitize_checkbox( $value ) {
	return ( ( isset( $value ) && true === $value ) ? true : false );
}

/**
 * Sanitizes numeric value.
 *
 * @param string               $value   The number value.
 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
 * @return int The number value.
 */
function evie_customize_sanitize_number( $value, $setting ) {
	$value = is_numeric( $value ) ? $value : 0;

	// Get the input attributes.
	$attributes = $setting->manager->get_control( $setting->id )->input_attrs;

	if ( ! empty( $attributes ) ) {
		if ( ! empty( $attributes['min'] ) && $value < intval( $attributes['min'] ) ) {
			$value = intval( $attributes['min'] );
		}

		if ( ! empty( $attributes['max'] ) && $value > intval( $attributes['max'] ) ) {
			$value = intval( $attributes['max'] );
		}
	}

	return absint( $value );
}

/**
 * Sanitizes sizes.
 *
 * @param string $value Raw size value.
 * @return array Array of sizes.
 */
function evie_customize_sanitize_sizes( $value ) {
	$values = array();
	$sizes  = ! empty( $value ) ? explode( ',', $value ) : array();
	foreach ( $sizes as $size ) {
		if ( preg_match( '/^(\d+|\d*\.\d+)(\w+)?$/', $size, $matches ) ) {
			$number = intval( $matches[1] );
			$unit   = isset( $matches[2] ) ? trim( $matches[2] ) : 'px';
			if ( $number ) {
				$values[] = $number . $unit;
			} else {
				$values[] = '';
			}
		}
	}
	return $values;
}

/**
 * Sanitization callback for validating an image setting value.
 *
 * Sanitization: image
 * Control: text, WP_Customize_Image_Control
 *
 * @uses    evie_validate_image()
 * @uses    esc_url_raw()               http://codex.wordpress.org/Function_Reference/esc_url_raw
 *
 * @param string               $value Value of the setting.
 * @param WP_Customize_Setting $setting Setting.
 * @return string|WP_Error Background value or validation error.
 */
function evie_customize_sanitize_image( $value, $setting ) {
	$value = empty( $value ) ? '' : esc_url_raw( evie_customize_validate_image( $value, $setting->default ) );
	return $value;
}

/**
 * Validates an image file.
 *
 * Validation: image
 * Control: text, WP_Customize_Image_Control
 *
 * @uses wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
 *
 * @param string $value Value of the setting.
 * @param string $default Default value when validation error.
 * @return string An image URL value or default value.
 */
function evie_customize_validate_image( $value, $default = '' ) {
	// Array of valid image file types.
	// The array includes image mime types.
	// that are included in wp_get_mime_types().
	$mimes = array(
		'jpg|jpeg' => 'image/jpeg',
		'gif'      => 'image/gif',
		'png'      => 'image/png',
		'bmp'      => 'image/bmp',
	);
	// Return an array with file extension.
	// and mime_type.
	$file = wp_check_filetype( $value, $mimes );
	// If $input has a valid mime_type,
	// return it; otherwise, return
	// the default.
	return $file['ext'] ? $value : $default;
}
