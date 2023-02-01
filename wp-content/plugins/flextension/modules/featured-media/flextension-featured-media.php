<?php
/**
 * Featured Media
 *
 * @package    Flextension
 * @subpackage Modules/Featured_Media
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers scripts and stylesheets.
 */
function flextension_featured_media_register_scripts() {
	wp_register_style( 'flextension-featured-media', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension-lightbox-gallery', 'flextension-carousel' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-featured-media', 'rtl', 'replace' );

	wp_register_script( 'flextension-featured-media', plugins_url( 'js/index.js', __FILE__ ), array( 'imagesloaded', 'flextension-lightbox-gallery', 'flextension-carousel' ), flextension_get_setting( 'version' ), true );
}

add_action( 'init', 'flextension_featured_media_register_scripts' );

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_featured_media_enqueue_scripts() {
	wp_enqueue_style( 'flextension-featured-media' );

	wp_enqueue_script( 'flextension-featured-media' );
}

add_action( 'wp_enqueue_scripts', 'flextension_featured_media_enqueue_scripts' );

add_action( 'enqueue_block_editor_assets', 'flextension_featured_media_enqueue_scripts' );

/**
 * Retrieves image IDs for the featured media.
 *
 * @param string $post_id The post ID.
 * @return array The image IDs of the featured media.
 */
function flextension_featured_media_url( $post_id = '' ) {

	if ( ! $post_id ) {
		return;
	}

	$featured_media_url = get_post_meta( $post_id, '_flext_featured_media', true );

	/**
	 * Filters the featured media URL for the post.
	 *
	 * @param array $featured_media_url The image IDs for the featured media.
	 * @param string $post_id           The post ID.
	 */
	return apply_filters( 'flextension_featured_media_url', $featured_media_url, $post_id );
}

/**
 * Retrieves the featured gallery type for the post.
 *
 * @param string $post_id The post ID.
 * @return string The featured gallery type.
 */
function flextension_featured_media_type( $post_id = '' ) {

	if ( ! $post_id ) {
		return;
	}

	$gallery_type = get_post_meta( $post_id, '_flext_featured_media_type', true );

	/**
	 * Filters the featured media type for the post.
	 *
	 * @param array $gallery_type The featured gallery type.
	 * @param string $post_id     The post ID.
	 */
	return apply_filters( 'flextension_featured_media_type', $gallery_type, $post_id );
}

/**
 * Returns an array list of the post types for featured media.
 *
 * @return array An array list of the post types.
 */
function flextension_featured_media_post_types() {
	$post_types = flextension_get_theme_support( 'featured-media', true );
	if ( true === $post_types ) {
		$post_types = array( 'post' );
	}
	return $post_types;
}

/**
 * Removes hentry class from the page and adds custom class 'entry' to the array of posts classes.
 *
 * @param string[] $classes An array of post class names.
 * @param string[] $class   An array of additional class names added to the post.
 * @param int      $post_id The post ID.
 * @return string[] $classes An array of post class names.
 */
function flextension_featured_media_post_classes( $classes, $class, $post_id ) {

	if ( ! has_post_thumbnail( $post_id ) ) {

		$format = get_post_format( $post_id );

		if ( ( 'gallery' === $format && count( flextension_featured_gallery_image_ids( $post_id ) ) > 0 )
			|| ( in_array( $format, array( 'video', 'audio' ), true ) && flextension_featured_media_url( $post_id ) )
			) {
			$classes[] = 'has-post-thumbnail';
		}
	}

	return $classes;

}

add_filter( 'post_class', 'flextension_featured_media_post_classes', 10, 3 );

/**
 * Retrieves image IDs for the featured gallery.
 *
 * @param string $post_id The post ID.
 * @return array The image IDs of the featured gallery.
 */
function flextension_featured_gallery_image_ids( $post_id = '' ) {

	if ( ! $post_id ) {
		return;
	}

	$image_ids = array();

	$featured_images = get_post_meta( $post_id, '_flext_featured_images', true );

	if ( ! empty( $featured_images ) ) {
		$image_ids = explode( ',', $featured_images );
	}

	/**
	 * Filters the image IDs for the post.
	 *
	 * @param array $image_ids The image IDs for the featured gallery.
	 * @param string $post_id The post ID.
	 */
	return apply_filters( 'flextension_featured_gallery_image_ids', $image_ids, $post_id );
}

/**
 * Retrieves the post thumbnail.
 *
 * Wraps the post thumbnail in an figure element, and an anchor element on index views.
 *
 * @param int|WP_Post  $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string|array $size The post thumbnail size. Image size or array of width and height values (in that order). Default 'post-thumbnail'.
 * @param array        $args Optional. Arguments to retrieve the featured media. See flextension_get_featured_media() for all.
 * @return string The HTML content for the post thumbnail.
 */
function flextension_get_post_thumbnail( $post = 0, $size = 'post-thumbnail', $args = array() ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$html = '';

	if ( has_post_thumbnail( $post ) ) {

		$thumbnail = get_the_post_thumbnail( $post, $size );

		$rollover_image = flextension_get_rollover_image( $post, $size );
		if ( ! empty( $rollover_image ) ) {
			$thumbnail .= '<div class="flext-featured-image-rollover">' . $rollover_image . '</div>';
		}

		$args = wp_parse_args(
			$args,
			array(
				'classes' => array(
					'post-thumbnail',
				),
			)
		);

		$classes = $args['classes'];

		$link = '';

		if ( 'post' === $args['link'] ) {
			$link = get_permalink( $post );
		} elseif ( 'lightbox' === $args['link'] ) {
			$classes[] = 'flext-has-lightbox';

			$link = get_the_post_thumbnail_url( $post, $args['lightbox_size'] );
		}

		$html = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">';

		if ( ! empty( $link ) ) {
			$html .= sprintf(
				'<a href="%s" aria-hidden="true">%s</a>',
				esc_url( $link ),
				$thumbnail
			);
		} else {
			$html .= $thumbnail;
		}

		$html .= '</div><!-- .post-thumbnail -->';

	}

	return $html;
}

/**
 * Returns HTML content of the images list.
 *
 * @param int|WP_Post  $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string|array $size The post thumbnail size. Image size or array of width and height values (in that order). Default 'post-thumbnail'.
 * @param array        $args Optional. Arguments to retrieve the featured media. See flextension_get_featured_media() for all.
 * @return string The HTML content for the post gallery.
 */
function flextension_get_post_images( $post = 0, $size = 'post-thumbnail', $args = array() ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return array();
	}

	$args = array_replace_recursive(
		array(
			'classes' => array(
				'flext-featured-media',
				'flext-post-gallery',
			),
			'images'  => array(
				'max' => 5,
			),
		),
		$args
	);

	$image_ids = flextension_featured_gallery_image_ids( $post->ID );

	if ( true === $args['include_featured'] ) {
		array_unshift( $image_ids, get_post_thumbnail_id( $post->ID ) );
	}

	$count = count( $image_ids );

	if ( $count < 1 ) {
		return '';
	}

	if ( -1 === $args['images']['max'] ) {
		$args['images']['max'] = $count;
	}

	$max = min( absint( $args['images']['max'] ), $count );

	$classes = $args['classes'];

	if ( 'lightbox' === $args['link'] ) {
		$classes[] = 'flext-has-lightbox';
	}

	$media = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">';

	$more = '';
	if ( $count > $max ) {
		$more = '<span class="more-items">+' . ( $count - $max ) . '</span>';
	}

	for ( $i = 0; $i < $max; $i++ ) {

		$link = '';
		if ( 'post' === $args['link'] ) {
			$link = get_permalink( $post );
		} elseif ( 'lightbox' === $args['link'] ) {
			$link = wp_get_attachment_image_url( $image_ids[ $i ], $args['lightbox_size'] );
		}

		if ( ! empty( $link ) ) {
			$media .= sprintf(
				'<a href="%s">%s%s</a>',
				esc_url( $link ),
				wp_get_attachment_image( $image_ids[ $i ], $size ),
				$i === $max - 1 ? $more : ''
			);
		} else {
			if ( $i === $max - 1 ) {
				$media .= '<div>' . wp_get_attachment_image( $image_ids[ $i ], $size ) . $more . '</div>';
			} else {
				$media .= wp_get_attachment_image( $image_ids[ $i ], $size );
			}
		}
	}

	$media .= '</div>';

	return $media;
}

/**
 * Returns HTML content of the featured gallery for the post.
 *
 * @param int|WP_Post  $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string|array $size The post thumbnail size. Image size or array of width and height values (in that order). Default 'post-thumbnail'.
 * @param array        $args Optional. Arguments to retrieve the featured media. See flextension_get_featured_media() for all.
 * @return string The HTML content of the featured gallery for the post.
 */
function flextension_get_post_gallery( $post = 0, $size = 'post-thumbnail', $args = array() ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return array();
	}

	$media     = '';
	$image_ids = flextension_featured_gallery_image_ids( $post->ID );

	// Find the gallery in the post content.
	if ( empty( $image_ids ) && true === $args['in_content'] ) {
		$image_ids = flextension_get_gallery_block_image_ids( $post->content );
	}

	$count = count( $image_ids );

	if ( $count < 1 ) {
		return $media;
	}

	$args = array_replace_recursive(
		array(
			'classes' => array(
				'flext-featured-media',
				'flext-post-gallery',
			),
			'gallery' => array(
				'max'    => 4,
				'layout' => 'grid',
			),
		),
		$args
	);

	if ( isset( $args['gallery']['layout'] ) ) {
		array_push( $args['classes'], 'flext-gallery-' . $args['gallery']['layout'] );
	}

	if ( isset( $args['gallery']['image_size'] ) && ! empty( $args['gallery']['image_size'] ) ) {
		$size = $args['gallery']['image_size'];
	}

	$attrs = array();

	$classes = $args['classes'];

	if ( 'lightbox' === $args['link'] ) {
		$classes[] = 'flext-has-lightbox';
	}

	$attrs['class'] = implode( ' ', $classes );

	if ( ! empty( $args['attributes'] ) ) {
		$attrs = array_merge( $attrs, (array) $args['attributes'] );
	}

	$media = '<div' . flextension_get_attributes( $attrs ) . '>';

	if ( -1 === $args['gallery']['max'] ) {
		$args['gallery']['max'] = $count;
	}

	$max = min( absint( $args['gallery']['max'] ), $count );

	$more = '';
	if ( $count > $max ) {
		$more = '<span class="more-items">+' . ( $count - $max ) . '</span>';
	}

	for ( $i = 0; $i < $max; $i++ ) {
		$url = '';
		if ( 'post' === $args['link'] ) {
			$url = get_permalink( $post );
		} elseif ( 'lightbox' === $args['link'] ) {
			$url = wp_get_attachment_image_url( $image_ids[ $i ], $args['lightbox_size'] );
		}

		if ( ! empty( $url ) ) {
			$media .= sprintf(
				'<a class="flext-gallery-item" href="%s">%s%s</a>',
				esc_url( $url ),
				wp_get_attachment_image( $image_ids[ $i ], $size ),
				$i === $max - 1 ? $more : ''
			);
		} else {
			$media .= sprintf(
				'<div class="flext-gallery-item">%s%s</div>',
				wp_get_attachment_image( $image_ids[ $i ], $size ),
				$i === $max - 1 ? $more : ''
			);
		}
	}

	$media .= '</div>';

	return $media;
}

/**
 * Returns HTML content of the featured slider.
 *
 * @param int|WP_Post  $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string|array $size The post thumbnail size. Image size or array of width and height values (in that order). Default 'post-thumbnail'.
 * @param array        $args Optional. Arguments to retrieve the featured media. See flextension_get_featured_media() for all.
 * @return string The HTML content for the featured slider.
 */
function flextension_get_post_slider( $post = 0, $size = 'post-thumbnail', $args = array() ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return array();
	}

	$args = array_replace_recursive(
		array(
			'classes'    => array(
				'flext-featured-media',
				'flext-post-gallery',
				'flext-gallery-slider',
			),
			'slider'     => array(
				'autoplay'   => false,
				'loop'       => false,
				'navigation' => false,
				'pagination' => false,
				'show_count' => false,
			),
			'attributes' => array(),
		),
		$args
	);

	$image_ids = flextension_featured_gallery_image_ids( $post->ID );

	if ( true === $args['include_featured'] ) {
		$featured_id = get_post_thumbnail_id( $post->ID );
		if ( ! empty( $featured_id ) ) {
			array_unshift( $image_ids, $featured_id );
		}
	}

	$count = count( $image_ids );

	if ( $count < 2 ) {
		return '';
	}

	$attrs = array();

	$classes = $args['classes'];

	if ( 'lightbox' === $args['link'] ) {
		$classes[] = 'flext-has-lightbox';
	}

	$attrs['class'] = esc_attr( implode( ' ', $classes ) );

	if ( ! empty( $args['slider']['autoplay'] ) ) {
		$attrs['data-autoplay'] = $args['slider']['autoplay'];
	}

	if ( ! empty( $args['slider']['loop'] ) ) {
		$attrs['data-loop'] = $args['slider']['loop'];
	}

	if ( ! empty( $args['slider']['slides-per-view'] ) ) {
		$attrs['data-slides-per-view'] = $args['slider']['slides-per-view'];
	}

	if ( ! empty( $args['slider']['space-between'] ) ) {
		$attrs['data-space-between'] = $args['slider']['space-between'];
	}

	if ( ! empty( $args['slider']['speed'] ) ) {
		$attrs['data-speed'] = $args['slider']['speed'];
	}

	if ( ! empty( $args['slider']['navigation'] ) ) {
		$attrs['data-navigation'] = $args['slider']['navigation'];
	}

	if ( ! empty( $args['slider']['pagination'] ) ) {
		$attrs['data-pagination'] = $args['slider']['pagination'];
	}

	if ( isset( $args['slider']['image_size'] ) && ! empty( $args['slider']['image_size'] ) ) {
		$size = $args['slider']['image_size'];
	}

	if ( ! empty( $args['attributes'] ) ) {
		$attrs = array_merge( $attrs, (array) $args['attributes'] );
	}

	$media = '<div' . flextension_get_attributes( $attrs ) . '>';

	$media .= '<div class="flext-carousel-wrapper">';

	for ( $i = 0; $i < $count; $i++ ) {
		$url = '';
		if ( 'post' === $args['link'] ) {
			$url = get_permalink( $post );
		} elseif ( 'lightbox' === $args['link'] ) {
			$url = wp_get_attachment_image_url( $image_ids[ $i ], $args['lightbox_size'] );
		}

		if ( ! empty( $url ) ) {
			$media .= sprintf(
				'<a href="%s" class="flext-slide">%s</a>',
				esc_url( $url ),
				wp_get_attachment_image( $image_ids[ $i ], $size )
			);
		} else {
			$media .= sprintf(
				'<div class="flext-slide">%s</div>',
				wp_get_attachment_image( $image_ids[ $i ], $size )
			);
		}
	}

	$media .= '</div>';

	if ( true === $args['slider']['show_count'] ) {
		$media .= '<span class="total-images">' . absint( $count ) . '</span>';
	}

	$media .= '</div>';

	return $media;
}

/**
 * Retrieves an image attachment ID of the poster image.
 *
 * @param int $post_id (Required) The post ID.
 * @return int Image attachment ID.
 */
function flextension_get_poster_image_id( $post_id ) {
	return get_post_meta( $post_id, '_flext_featured_media_poster', true );
}

/**
 * Returns a poster image HTML.
 *
 * @param int|WP_Post  $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string|int[] $size    Requested image size. Can be any registered image size name, or
 *                              an array of width and height values in pixels (in that order).
 * @param string|array $attr    Attributes for the image markup.
 * @return string The Rollover image HTML.
 */
function flextension_get_poster_image( $post, $size = 'post-thumbnail', $attr = array() ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$image = '';

	$image_id = flextension_get_poster_image_id( $post->ID );
	if ( ! empty( $image_id ) ) {
		$image = wp_get_attachment_image( $image_id, $size, false, $attr );
	}

	if ( empty( $image ) ) {
		$image = get_the_post_thumbnail( $post, $size );
	}

	return $image;
}

/**
 * Returns HTML content for the featured video.
 *
 * @param int|WP_Post  $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string|array $size The post thumbnail size. Image size or array of width and height values (in that order). Default 'post-thumbnail'.
 * @param array        $args Optional. Arguments to retrieve the featured media. See flextension_get_featured_media() for all.
 * @return string The HTML content for the featured video.
 */
function flextension_get_post_video( $post = 0, $size = 'post-thumbnail', $args = array() ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$args = array_replace_recursive(
		array(
			'classes'    => array(
				'flext-featured-media',
				'flext-post-video',
			),
			'video'      => array(
				'autoplay' => false,
				'controls' => true,
				'height'   => '',
				'width'    => '',
			),
			'attributes' => array(),
		),
		$args
	);

	$media     = '';
	$media_url = flextension_featured_media_url( $post->ID );

	// If the media URL is empty, retrieves media URL from the post content.
	if ( empty( $media_url ) && true === $args['in_content'] ) {
		$media_url = flextension_featured_media_get_video_url( $post->content );
	}

	if ( ! empty( $media_url ) ) {

		$attrs = array();

		$attrs['id']        = 'flext-post-video-' . $post->ID;
		$attrs['class']     = implode( ' ', $args['classes'] );
		$attrs['data-src']  = esc_url( $media_url );
		$attrs['data-type'] = 'video';

		if ( ! empty( $args['video']['width'] ) ) {
			$attrs['data-width'] = $args['video']['width'];
		}

		if ( ! empty( $args['video']['height'] ) ) {
			$attrs['data-height'] = $args['video']['height'];
		}

		if ( isset( $args['video']['image_size'] ) && ! empty( $args['video']['image_size'] ) ) {
			$size = $args['video']['image_size'];
		}

		if ( ! empty( $args['video']['autoplay'] ) ) {
			$attrs['data-autoplay'] = $args['video']['autoplay'];
		}

		if ( ! empty( $args['attributes'] ) ) {
			$attrs = array_merge( $attrs, (array) $args['attributes'] );
		}

		$media = '<div' . flextension_get_attributes( $attrs ) . '>';

		$media .= '<a class="flext-media-link" href="' . esc_url( $media_url ) . '">
					<button class="flext-media-button" aria-label="' . esc_attr( esc_html__( 'Play', 'flextension' ) ) . '">
						<i class="flext-ico-play"></i>
					</button>' .
					flextension_get_poster_image( $post, $size ) .
					'</a>';

		if ( true === $args['video']['controls'] ) {

			$media .= '<div class="flext-media-controls">
							<button class="flext-play-button" aria-label="' . esc_attr( esc_html__( 'Play/Pause', 'flextension' ) ) . '">
								<i class="flext-ico-play"></i>
							</button>
							<button class="flext-volume-button" aria-label="' . esc_attr( esc_html__( 'Mute/Unmute', 'flextension' ) ) . '">
								<i class="flext-ico-volume"></i>
							</button>
							<button class="flext-fullscreen-button" aria-label="' . esc_attr( esc_html__( 'Fullscreen', 'flextension' ) ) . '">
								<i class="flext-ico-resize-full"></i>
							</button>
						</div>';

		}

		$media .= '</div>';

	}

	return $media;
}

/**
 * Returns HTML content for the featured audio.
 *
 * @param int|WP_Post  $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string|array $size The post thumbnail size. Image size or array of width and height values (in that order). Default 'post-thumbnail'.
 * @param array        $args Optional. Arguments to retrieve the featured media. See flextension_get_featured_media() for all.
 * @return string The HTML content for the featured audio.
 */
function flextension_get_post_audio( $post = 0, $size = 'post-thumbnail', $args = array() ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$args = array_replace_recursive(
		array(
			'classes'    => array(
				'flext-featured-media',
				'flext-post-audio',
			),
			'audio'      => array(
				'autoplay' => false,
				'controls' => true,
			),
			'attributes' => array(),
		),
		$args
	);

	$media     = '';
	$media_url = flextension_featured_media_url( $post->ID );
	if ( empty( $media_url ) && true === $args['in_content'] ) { // Find the audio URL in the content.
		$media_url = flextension_featured_media_get_audio_url( $post->content );
	}

	if ( ! empty( $media_url ) ) {

		$attrs = array();

		$classes = (array) $args['classes'];

		$attrs['id']        = 'flext-post-audio-' . $post->ID;
		$attrs['class']     = implode( ' ', $classes );
		$attrs['data-src']  = esc_url( $media_url );
		$attrs['data-type'] = 'audio';

		if ( ! empty( $args['audio']['autoplay'] ) ) {
			$attrs['data-autoplay'] = $args['audio']['autoplay'];
		}

		if ( ! empty( $args['attributes'] ) ) {
			$attrs = array_merge( $attrs, (array) $args['attributes'] );
		}

		if ( isset( $args['audio']['image_size'] ) && ! empty( $args['audio']['image_size'] ) ) {
			$size = $args['audio']['image_size'];
		}

		$media .= '<div' . flextension_get_attributes( $attrs ) . '>';

		$media .= '<a class="flext-media-link" href="' . esc_url( $media_url ) . '">
					<button class="flext-media-button" aria-label="' . esc_attr( esc_html__( 'Play', 'flextension' ) ) . '">
						<i class="flext-ico-music"></i>
					</button>
					<div class="flext-sound-wave-icon">
						<span></span>
						<span></span>
						<span></span>
						<span></span>
					</div>
					' . flextension_get_poster_image( $post, $size )
				. '</a>';

		if ( true === $args['audio']['controls'] ) {

			$media .= '<div class="flext-media-controls">
							<button class="flext-play-button" aria-label="' . esc_attr( esc_html__( 'Play/Pause', 'flextension' ) ) . '">
								<i class="flext-ico-play"></i>
							</button>
							<button class="flext-volume-button" aria-label="' . esc_attr( esc_html__( 'Mute/Unmute', 'flextension' ) ) . '">
								<i class="flext-ico-volume"></i>
							</button>
							<button class="flext-fullscreen-button" aria-label="' . esc_attr( esc_html__( 'Fullscreen', 'flextension' ) ) . '">
								<i class="flext-ico-resize-full"></i>
							</button>
						</div>';

		}

		$media .= '</div><!-- .flext-post-audio -->';

	}

	return $media;
}

/**
 * Retrieves the first instance of a block in the content, and then break away.
 *
 * @param string      $block_name The full block type name, or a partial match.
 *                                Example: `core/image`, `core-embed/*`.
 * @param string|null $content    Optional. The content to search in. Use null for get_the_content().
 * @param array       $attributes Optional. The block attributes to match. Default empty.
 * @return string The media content for the first instance of a block.
 */
function flextension_get_featured_block( $block_name, $content = null, $attributes = array() ) {

	$result = null;

	if ( ! $content ) {
		$content = get_the_content();
	}

	// Parse blocks in the content.
	$blocks = parse_blocks( $content );

	// Loop blocks.
	foreach ( $blocks as $block ) {

		// Sanity check.
		if ( ! isset( $block['blockName'] ) ) {
			continue;
		}

		// Check if this the block matches the $block_name.
		$is_matching_block = false;

		// If the block ends with *, try to match the first portion.
		if ( '*' === $block_name[-1] ) {
			$is_matching_block = 0 === strpos( $block['blockName'], rtrim( $block_name, '*' ) );
		} else {
			$is_matching_block = $block_name === $block['blockName'];
		}

		if ( $is_matching_block ) {
			if ( is_array( $attributes ) && ! empty( $attributes ) && isset( $block['attrs'] ) ) {
				$matched = true;
				$names   = array_keys( $attributes );
				foreach ( $names as $name ) {
					if ( isset( $block['attrs'][ $name ] ) && $block['attrs'][ $name ] !== $attributes[ $name ] ) {
						$matched = false;
						break;
					}
				}
				if ( true === $matched ) {
					$result = $block;
				}
			} else {
				$result = $block;
			}
		}

		if ( null !== $result ) {
			break;
		}
	}

	return $result;
}

/**
 * Retrieves image IDs from the gallery block in the post content.
 *
 * @param string $content The content to retrieve the video URL.
 * @return array Array of the image IDs.
 */
function flextension_get_gallery_block_image_ids( $content = '' ) {
	$image_ids     = array();
	$gallery_block = flextension_get_featured_block( 'core/gallery', $content );
	if ( ! empty( $gallery_block ) && isset( $gallery_block['attrs']['ids'] ) && ! empty( $gallery_block['attrs']['ids'] ) ) {
		$image_ids = $gallery_block['attrs']['ids'];
	}
	return $image_ids;
}

/**
 * Retrieves a URL of the first video block in the post content.
 *
 * @param string $content The content to retrieve the video URL.
 * @return string A URL of the first video in the post.
 */
function flextension_featured_media_get_video_url( $content = '' ) {

	$video_url   = '';
	$video_block = flextension_get_featured_block( 'core/video', $content );
	if ( ! empty( $video_block ) && isset( $video_block['attrs']['id'] ) && ! empty( $video_block['attrs']['id'] ) ) {
		$video_url = wp_get_attachment_url( $video_block['attrs']['id'] );
	} else {
		$video_block = flextension_get_featured_block( 'core/embed', $content, array( 'type' => 'video' ) );
		if ( ! empty( $video_block ) && isset( $video_block['attrs']['url'] ) && ! empty( $video_block['attrs']['url'] ) ) {
			$video_url = $video_block['attrs']['url'];
		}
	}

	return $video_url;
}

/**
 * Retrieves a URL of the first audio block in the post content.
 *
 * @param string $content The content to retrieve the audio URL.
 * @return string A URL of the first audio in the post.
 */
function flextension_featured_media_get_audio_url( $content = '' ) {

	$audio_url   = '';
	$audio_block = flextension_get_featured_block( 'core/audio', $content );
	if ( ! empty( $audio_block ) && isset( $audio_block['attrs']['id'] ) && ! empty( $audio_block['attrs']['id'] ) ) {
		$audio_url = wp_get_attachment_url( $audio_block['attrs']['id'] );
	} else {
		$audio_block = flextension_get_featured_block( 'core/embed', $content, array( 'type' => 'rich' ) );
		if ( ! empty( $audio_block ) && isset( $audio_block['attrs']['url'] ) && ! empty( $audio_block['attrs']['url'] ) ) {
			$audio_url = $audio_block['attrs']['url'];
		}
	}

	return $audio_url;
}

/**
 * Retrieves an image attachment ID of the rollover image.
 *
 * @param int $post_id (Required) The post ID.
 * @return int Image attachment ID.
 */
function flextension_get_rollover_image_id( $post_id ) {
	return get_post_meta( $post_id, '_flext_featured_rollover_image', true );
}

/**
 * Returns a Rollover image HTML.
 *
 * @param int|WP_Post  $post    Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string|int[] $size    Requested image size. Can be any registered image size name, or
 *                              an array of width and height values in pixels (in that order).
 * @param string|array $attr    Attributes for the image markup.
 * @return string The Rollover image HTML.
 */
function flextension_get_rollover_image( $post, $size = 'post-thumbnail', $attr = array() ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	$image = '';

	$image_id = flextension_get_rollover_image_id( $post->ID );
	if ( ! empty( $image_id ) ) {
		$defaults = array(
			'data-lazyload' => 'disabled',
		);

		$attr = wp_parse_args( $attr, $defaults );

		$image = wp_get_attachment_image( $image_id, $size, false, $attr );
	}

	return $image;
}

/**
 * Retrieves the media content for the post.
 *
 * @param int|WP_Post  $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string|array $size The post thumbnail size. Image size or array of width and height
 *                           values (in that order). Default 'post-thumbnail'.
 * @param array        $args {
 *     Optional. Arguments to retrieve the featured media.
 *
 *     @type string $type             The featured media type to display. Default is based on the post format.
 *     @type string $link             The link type of the image. Accepts 'post' or 'file'. Default empty.
 *     @type string $lightbox_size    The image size to show in the lightbox.
 *     @type bool   $include_featured Whether to include the featured image in the featured media.
 *     @type bool   $in_content       Whether to find the media in the post content when the featured media is not set.
 *     @type array  $attributes       An array list of the HTML attributes.
 * }
 * @return string HTML content for the post media.
 */
function flextension_get_featured_media( $post = 0, $size = 'post-thumbnail', $args = array() ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	$args = wp_parse_args(
		$args,
		array(
			'type'             => flextension_featured_media_type( $post->ID ),
			'link'             => '',
			'lightbox_size'    => 'full',
			'include_featured' => true,
			'in_content'       => true,
			'attributes'       => array(),
		)
	);

	if ( empty( $args['type'] ) ) {
		if ( 'post' === $post->post_type ) {
			$args['type'] = get_post_format( $post->ID );
		}
	}

	/**
	 * Filters the arguments to retrieve the featured media.
	 *
	 * @param array       $args An array of the arguments to retrieve the featured media.
	 * @param int|WP_Post $post Post ID or WP_Post object.
	 */
	$args = apply_filters( 'flextension_featured_media_args', $args, $post );

	$html = '';

	switch ( $args['type'] ) {
		case 'gallery':
			$html = flextension_get_post_gallery( $post, $size, $args );
			break;
		case 'images':
			$html = flextension_get_post_images( $post, $size, $args );
			break;
		case 'slider':
			$html = flextension_get_post_slider( $post, $size, $args );
			break;
		case 'audio':
			$html = flextension_get_post_audio( $post, $size, $args );
			break;
		case 'video':
			$html = flextension_get_post_video( $post, $size, $args );
			break;
	}

	if ( empty( $html ) && true === $args['include_featured'] ) {
		$html = flextension_get_post_thumbnail( $post, $size, $args );
	}

	/**
	 * Filters the HTML content for the post featured media.
	 *
	 * @param string       $html The HTML content for the post featured media.
	 * @param int|WP_Post  $post Optional. Post ID or WP_Post object. Default is global `$post`.
	 * @param string|array $size The post thumbnail size. Image size or array of width and height
	 *                           values (in that order). Default 'post-thumbnail'.
	 * @param array        $args Arguments to retrieve the featured media.
	 */
	return apply_filters( 'flextension_featured_media_html', $html, $post, $size, $args );
}
