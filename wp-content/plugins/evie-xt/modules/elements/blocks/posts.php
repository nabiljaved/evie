<?php
/**
 * Posts Block
 *
 * @package    Evie_XT
 * @subpackage Modules/Elements/Blocks
 * @version    1.0.0
 */

/**
 * Registers the block.
 */
function evie_block_posts_register() {
	register_block_type_from_metadata(
		plugin_dir_path( __FILE__ ) . 'posts',
		array(
			'render_callback' => 'evie_block_posts_render',
		)
	);
}

add_action( 'init', 'evie_block_posts_register' );

/**
 * Renders a Posts block.
 *
 * @param array $attributes The attributes list for the block.
 * @return string The HTML content for the block.
 */
function evie_block_posts_render( $attributes ) {
	/**
	 * This is an exclusive block and it only works with Evie theme.
	 *
	 * @since 1.0.4
	 */
	if ( ! evie_can_load_blocks() ) {
		return '';
	}

	$query_vars = wp_parse_args(
		array(
			'ignore_sticky_posts' => 1,
			'paged'               => $attributes['page'],
		),
		flextension_block_get_query_vars( $attributes['query'] )
	);

	if ( ! empty( $attributes['query_vars'] ) ) {
		$query_vars = wp_parse_args(
			$attributes['query_vars'],
			$query_vars
		);
	}

	$attributes['query_vars'] = $query_vars;

	$attrs = array();

	$attrs['id'] = 'evie-block-' . $attributes['blockId'];

	$classes = array( 'evie-block-posts' );

	$extra_attributes = WP_Block_Supports::get_instance()->apply_block_supports();
	if ( ! empty( $extra_attributes ) ) {
		if ( isset( $extra_attributes['class'] ) ) {
			$classes[] = $extra_attributes['class'];
		}

		if ( isset( $extra_attributes['style'] ) ) {
			$attrs['style'] = $extra_attributes['style'];
		}
	}

	$attributes['posts'] = evie_posts_args(
		array(
			'layout'        => $attributes['layout'],
			'parallax'      => $attributes['parallax'],
			'style'         => $attributes['postStyle'],
			'hover_effect'  => $attributes['hoverEffect'],
			'columns'       => $attributes['columns'],
			'animation'     => ( evie_doing_request() || flextension_is_context_editor() ) ? '' : $attributes['animation'],
			'show_title'    => $attributes['displayTitle'],
			'show_category' => $attributes['displayCategory'],
			'show_author'   => $attributes['displayAuthor'],
			'show_date'     => $attributes['displayDate'],
			'show_buttons'  => $attributes['displayButtons'],
			'pagination'    => $attributes['pagination'],
			'query_vars'    => $query_vars,
			'class'         => $classes,
			'attrs'         => $attrs,
		)
	);

	ob_start();

	evie_block_template( 'posts', 'posts', $attributes['layout'], $attributes );

	$output = ob_get_clean();

	return $output;
}

/**
 * Prints out the See more link for the Posts block.
 *
 * @since 1.1.2
 *
 * @param array $attributes The attributes list for the block.
 */
function evie_block_posts_more_link( $attributes = array() ) {
	$url = flextension_block_get_more_link_url( $attributes );
	if ( ! empty( $url ) ) {
		?>
		<nav class="navigation posts-navigation" role="navigation">
			<a href="<?php echo esc_attr( $url ); ?>" class="see-more-link">
				<span>
					<?php
					if ( ! empty( $attributes['linkText'] ) ) {
						echo esc_html( $attributes['linkText'] );
					}
					?>
				</span>
				<i></i>
			</a>
		</nav>
		<?php
	}
}
