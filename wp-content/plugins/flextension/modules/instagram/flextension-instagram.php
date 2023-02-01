<?php
/**
 * Instagram
 *
 * @package    Flextension
 * @subpackage Modules/Instagram
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers scripts and stylesheets.
 */
function flextension_instagram_register_scripts() {
	wp_register_style( 'flextension-instagram-feed', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-instagram-feed', 'rtl', 'replace' );

	wp_register_script( 'flextension-instagram-feed', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );

	wp_register_style( 'flextension-instagram-feed-block-editor', plugins_url( 'js/block-editor.css', __FILE__ ), array( 'flextension-instagram-feed' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-instagram-feed-block-editor', 'rtl', 'replace' );

	wp_register_script(
		'flextension-instagram-feed-block-editor',
		plugins_url( 'js/block-editor.js', __FILE__ ),
		array( 'wp-blocks', 'wp-components', 'wp-block-editor', 'wp-element', 'wp-i18n', 'flextension-editor' ),
		flextension_get_setting( 'version' ),
		true
	);

	register_block_type_from_metadata(
		plugin_dir_path( __FILE__ ) . 'instagram-feed',
		array(
			'render_callback' => 'flextension_instagram_feed_block_render',
		)
	);
}

add_action( 'init', 'flextension_instagram_register_scripts' );

/**
 * Returns the settings of the Instagram module.
 *
 * @return array An array object of the settings.
 */
function flextension_instagram_settings() {
	return wp_parse_args(
		get_option( 'flext_instagram', array() ),
		array(
			'account_type' => '',
			'username'     => '',
			'user_id'      => '',
			'access_token' => '',
		)
	);
}

/**
 * Updates the settings of the Instagram module.
 *
 * @param array $new_settings Optional. An array object of the settings. Default array().
 */
function flextension_instagram_update_settings( $new_settings = array() ) {
	$settings = flextension_instagram_settings();

	foreach ( $new_settings as $name => $value ) {
		$settings[ $name ] = $value;
	}

	update_option( 'flext_instagram', $settings );
}

/**
 * Returns the URL to request the Instagram Access Token.
 *
 * @param string $redirect_uri A URI where it will redirect to after a permission request has been allowed or denied.
 * @return string A URL to request the Instagram Access Token.
 */
function flextension_instagram_token_request_url( $redirect_uri = '' ) {
	return 'https://api.instagram.com/oauth/authorize?response_type=code&scope=user_profile,user_media&app_id=206360940619297&state=' . rawurlencode( $redirect_uri ) . '&redirect_uri=https%3A%2F%2Fwww.slickremix.com%2Finstagram-basic-token%2F';
}

/**
 * Retrieves the Instagram access token.
 *
 * @return string An Instagram access token.
 */
function flextension_instagram_get_access_token() {

	$settings = flextension_instagram_settings();

	$token = $settings['access_token'];
	if ( ! empty( $token ) ) {
		if ( false === get_transient( 'flext_instagram_token_refresh' ) ) {
			// Refresh the access token.
			$url = add_query_arg(
				array(
					'grant_type'   => 'ig_refresh_token',
					'access_token' => $token,
				),
				'https://graph.instagram.com/refresh_access_token'
			);

			$data = flextension_instagram_fetch( $url );
			if ( is_array( $data ) && isset( $data['access_token'] ) ) {
				$token                    = $data['access_token'];
				$settings['access_token'] = $token;
				flextension_instagram_update_settings( $settings );

				set_transient( 'flext_instagram_token_refresh', true, DAY_IN_SECONDS * 30 );
			}
		}
	}

	return $token;
}

/**
 * Removes the current account and reset the Instagram caches.
 */
function flextension_instagram_reset_cache() {
	flextension_instagram_update_settings(
		array(
			'username' => '',
		)
	);
	delete_transient( 'flext_instagram_media' );
}

/**
 * Retrieves Instagram user profile.
 *
 * @param string $user_id Optional. An ID for the Instagram user. Default 'me'.
 * @return array An associative array of the Instagram media data.
 */
function flextension_instagram_get_profile( $user_id = 'me' ) {

	$profile = array();

	$data = flextension_instagram_get_data( $user_id, '?fields=username,account_type,id' );
	if ( is_array( $data ) && ! empty( $data ) ) {

		if ( isset( $data['id'] ) ) {
			$profile['user_id'] = sanitize_key( $data['id'] );
		}

		if ( isset( $data['username'] ) ) {
			$profile['username'] = sanitize_key( $data['username'] );
		}

		if ( isset( $data['account_type'] ) ) {
			$profile['account_type'] = sanitize_key( $data['account_type'] );
		}

		flextension_instagram_update_settings( $profile );
	}

	return $profile;
}

/**
 * Retrieves Instagram media data.
 *
 * @param string $user_id Optional. An ID for the Instagram user. Default 'me'.
 * @return array An associative array of the Instagram media data.
 */
function flextension_instagram_get_media( $user_id = 'me' ) {
	$cache_key = 'flext_instagram_media';

	$media = get_transient( $cache_key );
	if ( empty( $media ) ) {
		$data = flextension_instagram_get_data( $user_id, '/media?limit=20&fields=media_url,thumbnail_url,caption,id,media_type,permalink,children%7Bmedia_url,id,media_type,permalink,thumbnail_url%7D' );
		if ( isset( $data['data'] ) ) {
			$media = $data['data'];
			set_transient( $cache_key, $media, 2 * HOUR_IN_SECONDS );
		} elseif ( isset( $data['error'] ) ) {
			$media = $data;
		}
	}

	return $media;
}

/**
 * Retrieves specific data from Instagram API.
 *
 * @param string $user_id Optional. An ID for the Instagram user. Default 'me'.
 * @param string $endpoint Optional. A URL endpoint. Default empty.
 * @return array An associative array of the response body.
 */
function flextension_instagram_get_data( $user_id = 'me', $endpoint = '' ) {

	$data = array();

	$token = flextension_instagram_get_access_token();
	if ( ! empty( $token ) ) {
		$url = add_query_arg(
			array(
				'access_token' => $token,
			),
			'https://graph.instagram.com/v11.0/' . $user_id . $endpoint
		);

		$data = flextension_instagram_fetch( $url );
	} else {
		$data = array(
			'error' => array(
				'message' => esc_html__( 'Please connect to an Instagram account on the Settings page.', 'flextension' ),
			),
		);
	}

	return $data;
}

/**
 * Retrieves response body from Instagram API.
 *
 * @param string $url An Instagram API URI.
 * @return array An associative array of the response body.
 */
function flextension_instagram_fetch( $url ) {
	$data     = array();
	$response = wp_safe_remote_get( $url );
	$body     = wp_remote_retrieve_body( $response );
	if ( ! empty( $body ) ) {
		$data = json_decode( $body, true );
	}

	return $data;
}

/**
 * Returns media attributes
 *
 * @param array $attrs Media attributes.
 * @return array New media attributes.
 */
function flextension_instagram_widget_media_attributes( $attrs ) {
	/**
	 * Filters the image attributes.
	 *
	 * @param array $attrs An associative array of the image attributes.
	 */
	return apply_filters( 'flextension_widget_image_attributes', $attrs );
}

/**
 * Returns HTML content for the media.
 *
 * @param array $media An associative array of media data.
 * @return string HTML content for the media.
 */
function flextension_instagram_widget_media_html( $media ) {
	$html = '';

	$media = wp_parse_args(
		$media,
		array(
			'id'            => '',
			'caption'       => '',
			'media_type'    => '',
			'media_url'     => '',
			'permalink'     => '',
			'thumbnail_url' => '',
		)
	);

	switch ( $media['media_type'] ) {
		case 'IMAGE':
			$attrs = flextension_instagram_widget_media_attributes(
				array(
					'src' => $media['media_url'],
					'alt' => $media['caption'],
				)
			);

			$html = '<a href="' . esc_attr( esc_url( $media['permalink'] ) ) . '" target="_blank">
						<img' . flextension_get_attributes( $attrs ) . ' />
					</a>';
			break;
		case 'VIDEO':
			$attrs = flextension_instagram_widget_media_attributes(
				array(
					'src' => $media['thumbnail_url'],
					'alt' => $media['caption'],
				)
			);

			$html = '<a href="' . esc_attr( esc_url( $media['permalink'] ) ) . '" data-media-url="' . esc_attr( $media['media_url'] ) . '" target="_blank">
						<img' . flextension_get_attributes( $attrs ) . ' />
					</a>';
			break;
		case 'CAROUSEL_ALBUM':
			if ( isset( $media['children'] ) && isset( $media['children']['data'] ) ) {

				$children = $media['children']['data'];

				$html = '<div class="flext-carousel" data-slides-per-view="1" data-effect="creative" data-autoplay="hover" data-loop="true"><div class="flext-carousel-wrapper">';

				foreach ( $children as $item ) {
					$item['caption'] = $media['caption'];

					$html .= '<div class="flext-slide">' . flextension_instagram_widget_media_html( $item ) . '</div>';
				}

				$html .= '</div></div><span class="flext-total-slides">' . absint( count( $children ) ) . '</span>';

			} else {

				$media['media_type'] = 'IMAGE';

				$html = flextension_instagram_widget_media_html( $media );

			}
			break;
	}
	return $html;
}

/**
 * Renders an Instagram widget.
 *
 * @param array $attributes The attributes list for the widget.
 * @return string The HTML content for the widget.
 */
function flextension_instagram_widget( $attributes = array() ) {

	wp_enqueue_style( 'flextension-instagram-feed' );

	wp_enqueue_script( 'flextension-instagram-feed' );

	$defaults = array(
		'columns'       => 3,
		'number'        => 9,
		'gutters'       => '',
		'show_username' => false,
		'class'         => '',
	);

	$attributes = wp_parse_args( $attributes, $defaults );

	/**
	 * Filters Instagram Feed widget attributes.
	 *
	 * @param array $attributes The widget attributes.
	 */
	$attributes = apply_filters( 'flextension_instagram_feed_widget_attributes', $attributes );

	$classes = array( 'flext-instagram-feed' );

	if ( ! empty( $attributes['class'] ) ) {
		$classes[] = $attributes['class'];
	}

	$output = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">';

	if ( ! empty( $attributes['title'] ) || true === $attributes['show_username'] ) {
		$output .= '<div class="widget-title">';

		if ( ! empty( $attributes['title'] ) ) {
			$output .= '<h2>' . esc_html( $attributes['title'] ) . '</h2>';
		}

		if ( true === $attributes['show_username'] ) {
			$settings = flextension_instagram_settings();
			$output  .= '<a href="https://instagram.com/' . esc_attr( $settings['username'] ) . '" target="_blank" class="flext-instagram-username">' . esc_html( $settings['username'] ) . '</a>';
		}

		$output .= '</div>';
	}

	$data = flextension_instagram_get_media();

	if ( isset( $data['error'] ) && isset( $data['error']['message'] ) ) {
		if ( current_user_can( 'manage_options' ) ) {
			$output .= '<p>' . esc_html__( 'This error message is only visible to WordPress administrators:', 'flextension' ) . ' <strong>' . esc_html( $data['error']['message'] ) . '</strong></p>';
		} else {
			$output .= '<p>' . esc_html__( 'There are no posts to show right now.', 'flextension' ) . '</p>';
		}
	} elseif ( ! empty( $data ) ) {

		$classes = array();

		$classes[] = 'flext-grid flext-gallery';

		if ( ! empty( $attributes['gutters'] ) ) {
			$classes[] = 'flext-has-gutters flext-has-' . $attributes['gutters'] . '-gutters';
		}

		$classes[] = 'flext-columns-' . intval( $attributes['columns'] );

		$output .= '<ul class="' . esc_attr( implode( ' ', $classes ) ) . '">';

		$items = array();

		$max = min( $attributes['number'], count( $data ) );

		for ( $i = 0; $i < $max; $i++ ) {
			$type = isset( $data[ $i ]['media_type'] ) ? strtolower( $data[ $i ]['media_type'] ) : 'image';
			$type = preg_replace( '/[^a-z0-9\-]/', '-', $type );

			$items[] = '<li class="flext-grid-item has-media-type-' . esc_attr( $type ) . '">' . flextension_instagram_widget_media_html( $data[ $i ] ) . '</li>';
		}

		$output .= implode( '', $items );

		$output .= '</ul>';
	} else {
		$output .= '<p>' . esc_html__( 'There are no posts to show right now.', 'flextension' ) . '</p>';
	}

	$output .= '</div>';

	return $output;

}

/**
 * Renders an Instagram Feed block.
 *
 * @param array $attributes The attributes list for the block.
 * @return string The HTML content for the block.
 */
function flextension_instagram_feed_block_render( $attributes ) {

	$classes = array();

	$classes[] = 'flext-block-instagram-feed';

	if ( isset( $attributes['align'] ) ) {
		$classes[] = 'align' . $attributes['align'];
	}

	if ( isset( $attributes['className'] ) ) {
		$classes[] = $attributes['className'];
	}

	return flextension_instagram_widget(
		array(
			'title'         => $attributes['title'],
			'columns'       => $attributes['columns'],
			'number'        => $attributes['imagesToShow'],
			'gutters'       => $attributes['gutters'],
			'show_username' => $attributes['showUsername'],
			'class'         => implode( ' ', $classes ),
		)
	);
}
