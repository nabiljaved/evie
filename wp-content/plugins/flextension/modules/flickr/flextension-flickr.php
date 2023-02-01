<?php
/**
 * Flickr
 *
 * @package    Flextension
 * @subpackage Modules/Flickr
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders Flickr widget.
 *
 * @param array $attributes The attributes list for the widget.
 * @return string The HTML content for the widget.
 */
function flextension_flickr_widget( $attributes = array() ) {

	$defaults = array(
		'flickr_id'  => '',
		'type'       => 'user',
		'image_size' => 'small',
		'number'     => 9,
		'columns'    => 3,
		'gutters'    => '',
	);

	$attributes = wp_parse_args( $attributes, $defaults );

	/**
	 * Filters Flickr Feed widget attributes.
	 *
	 * @param array $attributes The widget attributes.
	 */
	$attributes = apply_filters( 'flextension_flickr_feed_widget_attributes', $attributes );

	$output = '<div class="flext-flickr">';

	$classes = array();

	$classes[] = 'flext-grid flext-gallery';

	if ( ! empty( $attributes['gutters'] ) ) {
		$classes[] = 'flext-has-gutters flext-has-' . $attributes['gutters'] . '-gutters';
	}

	$classes[] = 'flext-columns-' . intval( $attributes['columns'] );

	$output .= '<ul class="' . esc_attr( implode( ' ', $classes ) ) . '">';

	$photos = flextension_flickr_get_photos( $attributes['type'], $attributes['flickr_id'] );

	if ( $photos && ! empty( $photos ) ) {

		$max = min( $attributes['number'], count( $photos ) );

		for ( $i = 0; $i < $max; $i++ ) {

			$image_url = $photos[ $i ]->media->m;

			$image_width  = '';
			$image_height = '';

			switch ( $attributes['image_size'] ) {
				case 'medium':
					$image_url    = str_replace( '_m.', '_s.', $image_url );
					$image_width  = '240';
					$image_height = '171';
					break;
				case 'large':
					$image_url    = str_replace( '_m.', '_n.', $image_url );
					$image_width  = '320';
					$image_height = '229';
					break;
				default:
					$image_url    = str_replace( '_m.', '_q.', $image_url );
					$image_width  = '150';
					$image_height = '150';
			}

			$attrs = array(
				'src'    => esc_attr( $image_url ),
				'height' => esc_attr( $image_height ),
				'width'  => esc_attr( $image_width ),
				'alt'    => esc_attr( $photos[ $i ]->title ),
			);

			/**
			 * Filters the image attributes.
			 *
			 * @param array $attrs An associative array of the image attributes.
			 */
			$attrs = apply_filters( 'flextension_widget_image_attributes', $attrs );

			$output .= sprintf(
				'<li class="flext-grid-item"><a href="%s" title="%s" target="_blank"><img%s/></a></li>',
				esc_url( $photos[ $i ]->link ),
				esc_attr( $photos[ $i ]->title ),
				flextension_get_attributes( $attrs )
			);
		}
	}

	$output .= '</ul>
            </div>';

	return $output;

}

/**
 * Retrieves photos from the Flickr feed.
 *
 * @param string $type A Type of feed to fetch.
 * @param string $id An ID of the user or group to fetch for.
 * @return array An array list of the photos.
 */
function flextension_flickr_get_photos( $type = '', $id = '' ) {

	$suffix = $type . '_' . $id;

	$photos = get_transient( 'flextension_flickr_' . $suffix );

	if ( ! $photos ) {
		$api_uri = 'https://api.flickr.com/services/feeds/photos_public.gne';
		if ( 'group' === $type ) {
			$api_uri = 'https://api.flickr.com/services/feeds/groups_pool.gne';
		}

		$api_uri = add_query_arg(
			array(
				'format' => 'json',
				'id'     => $id,
			),
			$api_uri
		);

		$data = flextension_flickr_fetch( $api_uri );

		if ( $data && isset( $data->items ) ) {
			$photos = $data->items;

			set_transient( 'flextension_flickr_' . $suffix, $photos, 2 * HOUR_IN_SECONDS );
		}
	}

	return $photos;

}

/**
 * Retrieves feed from Flickr API.
 *
 * @param string $url The Flickr API enpoint URL.
 * @return string The feed string.
 */
function flextension_flickr_fetch( $url ) {

	$data = '';

	$response = wp_safe_remote_get( $url );

	$body = wp_remote_retrieve_body( $response );
	if ( ! empty( $body ) ) {
		if ( strpos( $body, 'jsonFlickrFeed(' ) !== false ) {
			$prefix_length = mb_strlen( 'jsonFlickrFeed(' );
			$body          = mb_substr( $body, $prefix_length, mb_strlen( $body ) - $prefix_length - 1 );
		}

		$data = json_decode( $body );
	}

	return $data;
}
