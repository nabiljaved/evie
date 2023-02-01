<?php
/**
 * Editor
 *
 * @package    Flextension
 * @subpackage Extensions/Editor
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Determines whether the current request is from WordPress Editor.
 *
 * @return bool Whether the current request is from WordPress Editor.
 */
function flextension_is_context_editor() {
	if ( isset( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return true;
	}
	return false;
}

/**
 * Returns a list of blocks that support all additional extensions.
 *
 * @return array An array list of supported blocks.
 */
function flextension_editor_get_supported_blocks() {
	// Most of core blocks are supported by default.
	$blocks = array(
		'core/archives',
		'core/audio',
		'core/button',
		'core/buttons',
		'core/calendar',
		'core/categories',
		'core/code',
		'core/column',
		'core/columns',
		'core/comments',
		'core/cover',
		'core/embed',
		'core/file',
		'core/gallery',
		'core/group',
		'core/heading',
		'core/image',
		'core/latest-comments',
		'core/latest-posts',
		'core/list-item',
		'core/list',
		'core/media-text',
		'core/navigation',
		'core/page-list',
		'core/paragraph',
		'core/pullquote',
		'core/quote',
		'core/read-more',
		'core/rss',
		'core/search',
		'core/separator',
		'core/social-link',
		'core/social-links',
		'core/spacer',
		'core/table-of-contents',
		'core/table',
		'core/tag-cloud',
		'core/text-columns',
		'core/video',
	);

	/**
	 * Filters a list of default blocks that support all additional extensions.
	 *
	 * @param array $blocks An array list of supported blocks.
	 */
	return apply_filters( 'flextension_extension_support_blocks', $blocks );
}

/**
 * Returns whether the block supports additional extensions.
 *
 * @param WP_Block_Type $block_type Block Type.
 * @param string        $name       Block support name.
 * @return bool Whether the block supports additional extensions.
 */
function flextension_has_block_support( $block_type, $name ) {
	$has_support = false;

	if ( property_exists( $block_type, 'supports' ) ) {
		$has_support = array_key_exists( $name, (array) $block_type->supports );
	}

	if ( false === $has_support ) {

		$supported_blocks = flextension_editor_get_supported_blocks();

		$has_support = (
			! empty( $block_type->name )
			&& in_array( $block_type->name, $supported_blocks, true )
		);
	}

	/**
	 * Filters whether the block supports additional extensions.
	 *
	 * @param bool          $has_support Whether the block supports additional extensions.
	 * @param WP_Block_Type $block_type  Block Type.
	 * @param string        $name        Block support name.
	 */
	return apply_filters( 'flextension_has_block_support', $has_support, $block_type, $name );
}

/**
 * Registers the scripts and stylesheets.
 */
function flextension_editor_register_scripts() {

	$settings = flextension_get_theme_support( 'editor', array() );

	$posts_page_enabled = false;
	if ( isset( $settings['posts_page'] ) ) {
		$posts_page_enabled = (bool) $settings['posts_page'];
	}

	if ( true === $posts_page_enabled ) {
		/**
		 * By default, the Block Editor will be disabled if the Posts page content is empty.
		 * So, we need to replace an empty content with ' ' (a space) to enable the Block Editor on the Posts page.
		 */
		add_action( 'update_option_page_for_posts', 'flextension_editor_update_posts_page', 10, 2 );

		add_action( 'wp_insert_post_data', 'flextension_editor_update_post_data', 10, 2 );
	}

	wp_register_style( 'flextension-extensions', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-extensions', 'rtl', 'replace' );

	wp_register_style( 'flextension-editor', plugins_url( 'css/editor.css', __FILE__ ), array( 'flextension', 'flextension-extensions' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-editor', 'rtl', 'replace' );

	wp_register_script( 'flextension-extensions', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );

	wp_register_script(
		'flextension-editor',
		plugins_url( 'js/editor.js', __FILE__ ),
		array(
			'wp-blob',
			'wp-block-editor',
			'wp-blocks',
			'wp-components',
			'wp-compose',
			'wp-core-data',
			'wp-data',
			'wp-element',
			'wp-hooks',
			'wp-i18n',
			'wp-rich-text',
			'flextension',
		),
		filemtime( plugin_dir_path( __FILE__ ) . 'js/editor.js' ),
		true
	);
}

add_action( 'init', 'flextension_editor_register_scripts', 5 );

/**
 * Enqueues block editor scripts and stylesheets.
 */
function flextension_editor_enqueue_block_editor_assets() {

	wp_enqueue_style( 'flextension-editor' );

	wp_enqueue_style( 'flextension-extensions' );

	wp_enqueue_script( 'flextension-editor' );

	$settings = array(
		'blocks'      => flextension_editor_get_supported_blocks(),
		'breakpoints' => flextension_editor_get_breakpoints(),
	);

	wp_localize_script( 'flextension-editor', 'flextensionEditor', $settings );
}

add_action( 'enqueue_block_editor_assets', 'flextension_editor_enqueue_block_editor_assets', 5 );

/**
 * Returns the ID for the current page.
 *
 * @return int The ID for the current page.
 */
function flextension_editor_get_page_id() {
	$id = get_queried_object_id();

	/**
	 * Filters the ID for the current page.
	 *
	 * @param int $id The ID for the current page.
	 */
	return apply_filters( 'flextension_editor_page_id', $id );
}

/**
 * Retrieves custom CSS for blocks in the post.
 *
 * @param int $post_id The post ID.
 * @return string CSS styles for blocks in the post.
 */
function flextension_editor_get_custom_css( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = flextension_editor_get_page_id();
	}

	if ( absint( $post_id ) > 0 ) {
		return get_post_meta( $post_id, '_flext_custom_css', true );
	}

	return '';
}

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_editor_enqueue_scripts() {

	wp_enqueue_style( 'flextension-extensions' );

	wp_enqueue_script( 'flextension-extensions' );

	$custom_css = flextension_editor_get_custom_css();
	if ( ! empty( $custom_css ) ) {
		wp_add_inline_style( 'flextension-extensions', $custom_css );
	}

}

add_action( 'wp_enqueue_scripts', 'flextension_editor_enqueue_scripts', 5 );

/**
 * Updates the content for the Posts page if it is empty,
 * to enable the Block Editor on the Posts page.
 *
 * @param mixed $old_value The old option value.
 * @param mixed $value     The new option value.
 */
function flextension_editor_update_posts_page( $old_value, $value ) {
	$post = $value ? get_post( $value ) : null;
	if ( $post && empty( $post->post_content ) ) {
		wp_update_post(
			array(
				'ID'           => $post->ID,
				'post_content' => ' ',
			)
		);
	}
}

/**
 * Updates the content for the Posts page if it is empty,
 * to enable the Block Editor on the Posts page.
 *
 * @param array $data    An array of slashed, sanitized, and processed post data.
 * @param array $postarr An array of sanitized (and slashed) but otherwise unmodified post data.
 */
function flextension_editor_update_post_data( $data, $postarr ) {
	if ( isset( $postarr['ID'] ) && absint( get_option( 'page_for_posts' ) ) === absint( $postarr['ID'] ) && empty( $data['post_content'] ) ) {
		$data['post_content'] = ' ';
	}
	return $data;
}

/**
 * Saves custom CSS styles metadata for the blocks in the current post.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function flextension_editor_save_custom_css_meta( $post_id, $post ) {
	$custom_css = flextension_editor_parse_custom_styles( $post );
	if ( ! empty( $custom_css ) ) {
		update_post_meta( $post_id, '_flext_custom_css', $custom_css );
	} else {
		delete_post_meta( $post_id, '_flext_custom_css' );
	}
}

add_action( 'save_post', 'flextension_editor_save_custom_css_meta', 10, 2 );

/**
 * Parses custom CSS styles of the blocks in the current post.
 *
 * @param WP_Post $post Post object.
 * @return string Custom CSS styles for the blocks.
 */
function flextension_editor_parse_custom_styles( $post ) {
	if ( ! function_exists( 'has_blocks' ) || ! function_exists( 'parse_blocks' ) || ! has_blocks( $post ) ) {
		return '';
	}

	$blocks = parse_blocks( $post->post_content );

	return flextension_editor_parse_blocks_css( $blocks );
}

/**
 * Parses custom CSS styles for the blocks.
 *
 * @param array $blocks An array list of the blocks.
 *
 * @return string Custom CSS styles for the blocks.
 */
function flextension_editor_parse_blocks_css( $blocks ) {

	if ( empty( $blocks ) || ! is_array( $blocks ) ) {
		return '';
	}

	$custom_styles = array();

	$breakpoints = flextension_editor_get_breakpoints();

	// Spacing attributes.
	$spacing_attributes = array(
		'margin',
		'padding',
	);

	// Reusable blocks.
	foreach ( $blocks as $name => $block ) {
		if ( isset( $block['blockName'] ) && isset( $block['attrs']['ref'] ) ) {
			if ( 'core/block' === $block['blockName'] ) {
				$content = get_post( $block['attrs']['ref'] );

				if ( $content ) {
					$inner_blocks = parse_blocks( $content->post_content );

					$blocks[ $name ]['innerBlocks'] = $inner_blocks;
				}
			}
		}
	}

	foreach ( $blocks as $block ) {

		if ( isset( $block['attrs'] ) ) {

			if ( isset( $block['attrs']['flextClassName'] ) && ! empty( $block['attrs']['flextClassName'] ) ) {

				$block_selector = '.' . $block['attrs']['flextClassName'];

				$block_styles = array();

				$prev_width = 0;

				foreach ( $breakpoints as $breakpoint => $width ) {

					$max_width = 0;

					$suffix = flextension_editor_get_responsive_suffix( $breakpoint );

					if ( $suffix ) {
						$max_width = $prev_width - 1;
					}

					$prev_width = $width;

					$css_props = array();

					foreach ( $spacing_attributes as $attribute ) {
						$attr_name = flextension_editor_get_attribute_name( $attribute, $suffix );
						if ( isset( $block['attrs'][ $attr_name ] ) && ! empty( $block['attrs'][ $attr_name ] ) ) {
							$values = (array) $block['attrs'][ $attr_name ];
							foreach ( $values as $side => $value ) {
								if ( ! empty( $value ) ) {
									$css_props[] = "{$attribute}-{$side}: {$value} !important;";
								}
							}
						}
					}

					$attr_name = flextension_editor_get_attribute_name( 'hidden', $suffix );
					if ( isset( $block['attrs'][ $attr_name ] ) && ! empty( $block['attrs'][ $attr_name ] ) ) {
						$hide = $block['attrs'][ $attr_name ];
						if ( $hide ) {
							$css_props[] = 'display: none !important;';
						}
					}

					if ( ! empty( $css_props ) ) {
						$media_rules = array();

						if ( intval( $width ) > 0 ) {
							$media_rules[] = '(min-width: ' . intval( $width ) . 'px)';
						}

						if ( $max_width > 0 ) {
							$media_rules[] = '(max-width: ' . $max_width . 'px)';
						}

						$block_styles[] = '@media ' . implode( ' and ', $media_rules ) . ' { ' . $block_selector . ' { ' . implode( ' ', $css_props ) . ' } }';
					}
				}

				if ( ! empty( $block_styles ) ) {
					$custom_styles[] = implode( ' ', array_reverse( $block_styles ) );
				}
			}
		}

		// Inner blocks.
		if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
			$custom_styles[] = flextension_editor_parse_blocks_css( $block['innerBlocks'] );
		}
	}

	$custom_css = '';
	if ( ! empty( $custom_styles ) ) {
		$custom_css = trim( implode( ' ', $custom_styles ) );
	}

	/**
	 * Filters the custom CSS styles for the blocks.
	 *
	 * @param string $custom_css The custom CSS styles for the blocks.
	 * @param array  $blocks     An array list of the blocks.
	 */
	return apply_filters( 'flextension_blocks_custom_css', $custom_css, $blocks );
}

/**
 * Returns a responsive suffix.
 *
 * @param string $breakpoint Breakpoint name.
 *
 * @return string Responsive suffix.
 */
function flextension_editor_get_responsive_suffix( $breakpoint ) {
	$suffix = '';

	if ( $breakpoint && 'desktop' !== $breakpoint ) {
		$suffix = $breakpoint;
	}

	return $suffix;
}

/**
 * Returns attribute name with responsive suffix.
 *
 * @param string $name   Attribute name.
 * @param string $suffix Responsive suffix.
 *
 * @return string Attribute name.
 */
function flextension_editor_get_attribute_name( $name, $suffix ) {
	return 'flext' . ucfirst( $name ) . ucfirst( $suffix );
}

/**
 * Returns breakpoints.
 *
 * @return array An array list of the breakpoints.
 */
function flextension_editor_get_breakpoints() {
	/**
	 * Filters the breakpoints for the block responsive styles.
	 *
	 * @param array An array of breakpoints.
	 */
	$breakpoints = apply_filters(
		'flextension_breakpoints',
		array(
			'desktop' => 1024, // >= 1024
			'tablet'  => 768, // >= 768
			'mobile'  => 0, // >= 0
		)
	);

	// Sort by breakpoint width in descending order.
	arsort( $breakpoints );

	return $breakpoints;
}
