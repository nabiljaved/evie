<?php
/**
 * Lazyload
 *
 * @package    Flextension
 * @subpackage Modules/Lazyload
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_lazyload_enqueue_scripts() {

	if ( flextension_lazyload_is_active() ) {

		wp_enqueue_style( 'flextension-lazyload', plugins_url( 'css/style.css', __FILE__ ), array(), flextension_get_setting( 'version' ) );

		wp_enqueue_script( 'flextension-lazyload', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );

		wp_enqueue_script( 'lazysizes', plugins_url( 'js/vendor/lazysizes.min.js', __FILE__ ), array( 'flextension-lazyload' ), '5.3.2', true );

	}

}

add_action( 'wp_enqueue_scripts', 'flextension_lazyload_enqueue_scripts' );

/**
 * Returns the settings values of the Lazyload module.
 *
 * @return array An array object of the settings.
 */
function flextension_lazyload_settings() {
	return wp_parse_args(
		get_option( 'flext_lazyload', array() ),
		array(
			'lqip' => false,
		)
	);
}

/**
 * Returns whether the module is allowed.
 *
 * @param array $attr Attributes for the image markup.
 * @return bool Whether the Single Page is allowed.
 */
function flextension_lazyload_is_active( $attr = array() ) {
	// Admin or preview or customize preview.
	if ( is_admin() ) {
		return false;
	}

	// REST API request.
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST && ( isset( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return false;
	}

	// Feed.
	if ( is_feed() ) {
		return false;
	}

	// Printpage.
	if ( get_query_var( 'print' ) || get_query_var( 'printpage' ) ) {
		return false;
	}

	// Image attribute.
	if ( isset( $attr['data-lazyload'] ) && 'disabled' === $attr['data-lazyload'] ) {
		return false;
	}

	if ( isset( $attr['class'] ) && preg_match( '/lazyload-disabled/', $attr['class'] ) ) {
		return false;
	}

	return true;
}

/**
 * Returns whether the Low Quality Image Placeholder is enabled.
 *
 * @return bool Whether the Low Quality Image Placeholder is enabled.
 */
function flextension_lazyload_is_lqip_enabled() {
	$settings = flextension_lazyload_settings();
	return (bool) $settings['lqip'];
}

/**
 * Allows attributes of Lazy Load for wp_kses.
 */
function flextension_lazyload_allow_lazy_attributes() {
	global $allowedposttags;

	if ( $allowedposttags ) {
		foreach ( $allowedposttags as $key => & $tags ) {
			if ( 'img' === $key ) {
				$tags['data-src']    = true;
				$tags['data-sizes']  = true;
				$tags['data-srcset'] = true;
			}
		}
	}
}

add_filter( 'init', 'flextension_lazyload_allow_lazy_attributes' );

/**
 * Allow protocols of Lazy Load.
 *
 * @param array $protocols Array of allowed protocols e.g.
 */
function flextension_lazyload_allow_lazy_protocols( $protocols ) {
	$protocols[] = 'data';

	return $protocols;
}

add_filter( 'kses_allowed_protocols', 'flextension_lazyload_allow_lazy_protocols' );

/**
 * Processing images in the content.
 *
 * @param string $content Text with Images.
 */
function flextension_lazyload_content_process_images( $content ) {

	if ( ! flextension_lazyload_is_active() ) {
		return $content;
	}

	// Get all images.
	preg_match_all( '/<img\s+.*?>/', $content, $matches );

	$images = array_shift( $matches );

	// Check exists images.
	if ( ! $images ) {
		return $content;
	}

	foreach ( $images as $image ) {

		// Ignore init lazyload.
		if ( preg_match( '/lazyload/', $image ) ) {
			continue;
		}

		// Get Attributes for the image markup.
		if ( preg_match_all( '/(\w+)=[\'"]([^\'"]*)/', $image, $matches ) ) {

			$attr_data = array_shift( $matches );

			// Get attr list of image.
			$attr = array();

			foreach ( $attr_data as $key => $fulldata ) {
				$name  = $matches[0][ $key ];
				$value = $matches[1][ $key ];

				$attr[ $name ] = $value;
			}

			/**
			 * Process image.
			 * --------------------------------
			 */
			$attachment = flextension_lazyload_attachment_attr_to_object( $attr );
			$size       = flextension_lazyload_attachment_attr_to_size( $attr );

			$attr = flextension_lazyload_add_image_placeholders( $attr, $attachment, $size );

			// Variables for new image.
			$new_image = '<img [attr]>';
			$new_attr  = null;

			// Build new attributes.
			foreach ( $attr as $key => $value ) {
				$new_attr .= sprintf( ' %s="%s" ', $key, $value );
			}

			// Create new image based on new attributes.
			$new_image = str_replace( '[attr]', $new_attr, $new_image );

			// Update content.
			$content = str_replace( $image, $new_image, $content );
		}
	}

	return $content;
}

add_filter( 'the_content', 'flextension_lazyload_content_process_images', 50 );

add_filter( 'get_avatar', 'flextension_lazyload_content_process_images', 50 );

add_filter( 'flextension_lazy_process_images', 'flextension_lazyload_content_process_images', 50 );

/**
 * Returns the placeholder image.
 *
 * @param int $width  Optional. The width of image. Default 1.
 * @param int $height Optional. The height of image. Default 1.
 * @return string The placeholder image.
 */
function flextension_lazyload_image_placeholder( $width = 1, $height = 1 ) {

	$transient = sprintf( 'flext_image_placeholder_%s_%s', $width, $height );

	$placeholder_image = get_transient( $transient );

	if ( false === $placeholder_image ) {

		if ( function_exists( 'imagecreate' ) ) {
			$placeholder_code = ob_start();

			$image      = imagecreate( $width, $height );
			$background = imagecolorallocatealpha( $image, 0, 0, 0, 127 );

			imagepng( $image, null, 9 );
			imagecolordeallocate( $image, $background );
			imagedestroy( $image );

			$placeholder_code = ob_get_clean();

			$placeholder_image = 'data:image/png;base64,' . base64_encode( $placeholder_code );

		} else {
			$placeholder_image = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAP+KeNJXAAAAAXRSTlMAQObYZgAAAAlwSFlzAAAOxAAADsQBlSsOGwAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=';
		}

		set_transient( $transient, $placeholder_image );
	}

	return $placeholder_image;
}

/**
 * Adds a placeholder to the attachment meta data.
 *
 * @param array $metadata An array of attachment meta data.
 */
function flextension_lazyload_generate_attachment_placeholder( $metadata ) {

	// Generate image full size.
	if ( isset( $metadata['width'] ) && isset( $metadata['height'] ) ) {
		$metadata['placeholder'] = flextension_lazyload_image_placeholder( $metadata['width'], $metadata['height'] );
	}

	// Generate image sizes.
	if ( isset( $metadata['sizes'] ) ) {
		foreach ( $metadata['sizes'] as $slug => & $size ) {
			// Ignore lqip size.
			if ( preg_match( '/flext-lqip/', $slug ) ) {
				continue;
			}
			// Ignore retina size.
			if ( preg_match( '/-2x$/', $slug ) ) {
				continue;
			}
			if ( isset( $size['width'] ) && isset( $size['height'] ) ) {
				$size['placeholder'] = flextension_lazyload_image_placeholder( $size['width'], $size['height'] );
			}
		}
	}

	return $metadata;
}

add_filter( 'wp_update_attachment_metadata', 'flextension_lazyload_generate_attachment_placeholder' );

add_filter( 'wp_generate_attachment_metadata', 'flextension_lazyload_generate_attachment_placeholder' );

/**
 * Adds placeholder Lazy Load for images.
 *
 * @param array        $attr       Attributes for the image markup.
 * @param WP_Post      $attachment Image attachment post.
 * @param string|array $size       Requested size. Image size or array of width and height values
 *                                 (in that order). Default 'thumbnail'.
 */
function flextension_lazyload_add_image_placeholders( $attr, $attachment, $size ) {

	if ( ! flextension_lazyload_is_active( $attr ) ) {
		return $attr;
	}

	// Init class of image.
	if ( ! isset( $attr['class'] ) ) {
		$attr['class'] = null;
	}

	// Init src of image.
	if ( ! isset( $attr['src'] ) ) {
		$attr['src'] = null;
	}

	$placeholder = '';

	$attachment_id = null;
	// Get attachment id.
	if ( isset( $attachment->ID ) ) {
		$attachment_id = $attachment->ID;
	} elseif ( isset( $attachment['ID'] ) ) {
		$attachment_id = $attachment['ID'];
	}

	if ( empty( $size ) ) {

		// Low Quality Image Placeholder.
		if ( flextension_lazyload_is_lqip_enabled() ) {

			$lqip_size = flextension_lazyload_get_lqip_slug( $size );

			if ( flextension_lazyload_get_image_size( $lqip_size ) ) {
				$placeholder_image = wp_get_attachment_image_url( $attachment_id, $lqip_size );

				// Check lqip image exists.
				if ( preg_match( '/-\d*x\d*\.\w*$/', $placeholder_image ) ) {
					$placeholder = $placeholder_image;

					// Add lqip class.
					$attr['class'] .= ' flext-lqip';
				}
			}
		}
	} elseif ( is_string( $size ) ) {

		// The right Image Placeholder.
		$metadata = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );

		if ( isset( $metadata['sizes'][ $size ]['placeholder'] ) ) {

			$placeholder = $metadata['sizes'][ $size ]['placeholder'];

		} elseif ( isset( $metadata['placeholder'] ) ) {

			$placeholder = $metadata['placeholder'];

		}

		// Low Quality Image Placeholder.
		if ( flextension_lazyload_is_lqip_enabled() ) {

			$lqip_size = flextension_lazyload_get_lqip_slug( $size );

			if ( flextension_lazyload_get_image_size( $lqip_size ) ) {
				$placeholder_image = wp_get_attachment_image_url( $attachment_id, $lqip_size );

				// Check lqip image exists.
				if ( preg_match( '/-\d*x\d*\.\w*$/', $placeholder_image ) ) {
					$placeholder = $placeholder_image;

					// Add lqip class.
					$attr['class'] .= ' flext-lqip';
				}
			}
		}
	}

	// Set Lazyload class.
	if ( isset( $attr['class'] ) && ! empty( $attr['class'] ) ) {
		$attr['class'] .= ' lazyload';
	} else {
		$attr['class'] = 'lazyload';
	}

	// Set data-sizes.
	if ( ! isset( $attr['data-sizes'] ) ) {
		$attr['data-sizes'] = 'auto';
	}

	if ( isset( $attr['sizes'] ) ) {
		$attr['data-ls-sizes'] = $attr['sizes'];

		unset( $attr['sizes'] );
	}

	// Set data-src.
	if ( ! isset( $attr['data-src'] ) ) {
		$attr['data-src'] = $attr['src'];
	}

	// Set data-srcset and unset sizes / srcset.
	if ( isset( $attr['srcset'] ) ) {
		$attr['data-srcset'] = $attr['srcset'];

		unset( $attr['srcset'] );
	}

	if ( empty( $placeholder ) ) {
		$placeholder = flextension_lazyload_image_placeholder();
	}

	// Set placeholder.
	$attr['src'] = $placeholder;

	return $attr;
}

add_filter( 'wp_get_attachment_image_attributes', 'flextension_lazyload_add_image_placeholders', 10, 3 );

/**
 * Add lqip sizes.
 */
function flextension_lazyload_add_lqip_sizes() {
	if ( ! flextension_lazyload_is_lqip_enabled() ) {
		return;
	}

	$sizes = flextension_lazyload_get_available_image_sizes();

	/**
	 * Filters the Low Quality Image Placeholder width.
	 *
	 * @param int $width The width of the Low Quality Image Placeholder.
	 */
	$lqip_width = apply_filters( 'flextension_lazyload_lqip_width', 80 );

	// Add full lqip size.
	add_image_size( 'flext-lqip-full', $lqip_width, 9999 );

	if ( ! empty( $sizes ) ) {
		foreach ( $sizes as $size => $data ) {
			$divider = $data['width'] / $data['height'];

			// Add new lqip size.
			add_image_size( flextension_lazyload_get_lqip_slug( $size ), $lqip_width, intval( $lqip_width / $divider ), $data['crop'] );
		}
	}
}

add_filter( 'init', 'flextension_lazyload_add_lqip_sizes' );

/**
 * Get lqip slug.
 *
 * @param array $size Registered size or full size.
 */
function flextension_lazyload_get_lqip_slug( $size ) {
	$lqip_slug = 'flext-lqip-full';

	$data = flextension_lazyload_get_image_size( $size );

	if ( isset( $data['width'] ) && isset( $data['height'] ) ) {
		$crop = null;

		if ( isset( $data['crop'] ) ) {
			// Set crop if val array.
			if ( is_array( $data['crop'] ) ) {
				$crop = '-' . implode( '-', $data['crop'] );
			}
			// Set crop if val exist.
			if ( is_bool( $data['crop'] ) && $data['crop'] ) {
				$crop = '-crop';
			}
		}

		// Set divider.
		$divider = $data['width'] / $data['height'];

		$lqip_slug = sprintf( 'flext-lqip-%s%s', round( $divider, 2 ), $crop );
	}

	return $lqip_slug;
}

/**
 * Sets the image placeholder for the image in widget.
 *
 * @param array $attr An array of attributes. Default empty.
 * @return array $attr An array of image attributes.
 */
function flextension_lazyload_add_widget_image_placeholder( $attr = array() ) {

	if ( ! flextension_lazyload_is_active( $attr ) ) {
		return $attr;
	}

	if ( isset( $attr['class'] ) && ! empty( $attr['class'] ) ) {
		$attr['class'] .= ' lazyload';
	} else {
		$attr['class'] = 'lazyload';
	}

	// Set data-sizes.
	if ( ! isset( $attr['data-sizes'] ) ) {
		$attr['data-sizes'] = 'auto';
	}

	if ( isset( $attr['sizes'] ) ) {
		$attr['data-ls-sizes'] = $attr['sizes'];

		unset( $attr['sizes'] );
	}

	// Set data-src.
	if ( ! isset( $attr['data-src'] ) ) {
		$attr['data-src'] = $attr['src'];
	}

	// Set data-srcset and unset sizes / srcset.
	if ( isset( $attr['srcset'] ) ) {
		$attr['data-srcset'] = $attr['srcset'];

		unset( $attr['srcset'] );
	}

	// Set placeholder.
	$attr['src'] = flextension_lazyload_image_placeholder();

	return $attr;
}

add_filter( 'flextension_widget_image_attributes', 'flextension_lazyload_add_widget_image_placeholder' );

/**
 * Returns the available image sizes
 */
function flextension_lazyload_get_available_image_sizes() {
	$image_sizes = wp_get_registered_image_subsizes();

	if ( is_array( $image_sizes ) && ! empty( $image_sizes ) ) {
		foreach ( $image_sizes as $size => $size_data ) {
			// Size registered, but has 0 width or height.
			if ( 0 === (int) $image_sizes[ $size ]['width'] || 0 === (int) $image_sizes[ $size ]['height'] ) {
				unset( $image_sizes[ $size ] );
			}
		}
	}

	return $image_sizes;
}

/**
 * Returns the data of a specific image size.
 *
 * @param string $size Name of the size.
 * @return array|bool An array data of the image size or false if is not available.
 */
function flextension_lazyload_get_image_size( $size ) {
	if ( ! is_string( $size ) ) {
		return false;
	}

	$sizes = flextension_lazyload_get_available_image_sizes();

	return isset( $sizes[ $size ] ) ? $sizes[ $size ] : false;
}

/**
 * Tries to convert an attachment IMG attr into a post object.
 *
 * @param string $attr The img attr.
 */
function flextension_lazyload_attachment_attr_to_object( $attr ) {
	if ( ! isset( $attr['src'] ) ) {
		return;
	}

	// Set ID by class.
	if ( isset( $attr['class'] ) && preg_match( '/wp-image-(\d*)/i', $attr['class'], $matche ) ) {
		return array(
			'ID' => $matche[1],
		);
	}

	// Remove the thumbnail size.
	$src = preg_replace( '~-[0-9]+x[0-9]+(?=\..{2,6})~', '', $attr['src'] );

	// Set ID by src.
	return array(
		'ID' => attachment_url_to_postid( $src ),
	);
}

/**
 * Tries to convert an attachment IMG attr into a image size.
 *
 * @param string $attr The img attr.
 */
function flextension_lazyload_attachment_attr_to_size( $attr ) {
	if ( ! isset( $attr['class'] ) ) {
		return;
	}

	// Set ID by class.
	if ( preg_match( '/size-(\S*)/i', $attr['class'], $matche ) ) {
		return $matche[1];
	}
}
