<?php
/**
 * Template part for displaying the featured posts in a Carousel.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    Evie_XT
 * @subpackage Modules/Elements/Blocks/Featured_Posts/Templates
 * @version    1.0
 *
 * @var array $attributes The attributes list for the block.
 */

// This template part requires the block attributes.
if ( ! $attributes ) {
	return;
}

$evie_classes = array(
	'evie-block-featured-posts',
	'evie-carousel',
	'evie-fullscreen',
	'alignfull',
);

if ( true === $attributes['mousewheel'] ) {
	$evie_classes[] = 'has-mousewheel';
}

if ( true === $attributes['disableScroll'] ) {
	$evie_classes[] = 'evie-disable-scrolling';
}

$evie_attrs = array();

$evie_attrs['id'] = 'evie-block-' . $attributes['blockId'];

$evie_attrs['class'] = implode( ' ', $evie_classes );

$evie_attrs['data-navigation'] = true;

$evie_featured_posts = get_posts( $attributes['query_vars'] );

$evie_post_count = count( $evie_featured_posts );

?>
<div <?php echo get_block_wrapper_attributes( $evie_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

	<?php

	if ( 0 === $evie_post_count ) {

		echo '<p class="evie-no-posts">' . esc_html__( 'There are no posts to show right now.', 'evie-xt' ) . '</p>';

	} else {

		?>

		<div class="flext-carousel-wrapper">

		<?php

		$evie_item_index = 0;

		foreach ( $evie_featured_posts as $evie_featured_post ) :

			$evie_speed = 0 === $evie_item_index % 2 ? -2 : 2;

			?>
			<div class="flext-slide featured-post featured-post-type-<?php echo esc_attr( $evie_featured_post->post_type ); ?>">

				<div class="slide-image">
					<a href="<?php echo esc_url( get_permalink( $evie_featured_post ) ); ?>" aria-hidden="true" tabindex="-1">
						<?php echo get_the_post_thumbnail( $evie_featured_post, 'evie-large' ); ?>
						<span class="slide-number"><?php echo esc_attr( sprintf( '%02d', ( $evie_item_index + 1 ) ) ); ?></span>
					</a>
				</div>


				<header class="slide-header">

					<?php

					if ( true === $attributes['displayCategory'] ) {
						evie_post_meta_category( $evie_featured_post );
					}

					evie_post_title( $evie_featured_post, 'h2', 'slide-title' );

					?>

				</header><!-- .slide-header -->

			</div>
			<?php

			$evie_item_index++;

		endforeach;
		?>

		</div><!-- .flext-carousel-wrapper -->

		<div class="slider-background"><span class="slider-background-text"></span></div>

	<?php } ?>

</div><!-- .evie-carousel -->
