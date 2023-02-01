<?php
/**
 * Template part for displaying the featured posts in a Split Slider.
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
	'evie-split-slider',
	'evie-fullscreen',
	'alignfull',
);

if ( true === $attributes['mousewheel'] ) {
	$evie_classes[] = 'has-mousewheel';
}

if ( true === $attributes['disableScroll'] ) {
	$evie_classes[] = 'evie-disable-scrolling';
}

if ( ! empty( $attributes['backgroundOverlay'] ) ) {
	$evie_classes[] = 'has-background-overlay';
	$evie_classes[] = 'has-scheme-' . $attributes['backgroundOverlay'];
}

$evie_attrs = array();

$evie_attrs['id'] = 'evie-block-' . $attributes['blockId'];

$evie_attrs['class'] = implode( ' ', $evie_classes );

$evie_featured_posts = get_posts( $attributes['query_vars'] );

$evie_post_count = count( $evie_featured_posts );

?>
<div <?php echo get_block_wrapper_attributes( $evie_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

	<?php

	if ( 0 === $evie_post_count ) {

		echo '<p class="evie-no-posts">' . esc_html__( 'There are no posts to show right now.', 'evie-xt' ) . '</p>';

	} else {

		?>
	<div class="evie-slides">

		<div class="evie-column evie-left-column">
			<?php
			$evie_item_index = 0;
			foreach ( $evie_featured_posts as $evie_featured_post ) :
				$evie_item_index++;

				$evie_post_classes   = array( 'evie-slide', 'featured-post' );
				$evie_post_classes[] = 'featured-post-type-' . $evie_featured_post->post_type;
				$evie_post_classes[] = 'item-' . $evie_item_index;
				if ( empty( $attributes['backgroundOverlay'] ) ) {
					$evie_post_classes[] = evie_portfolio_project_scheme( $evie_featured_post );
				}
				?>
			<div class="<?php echo esc_attr( implode( ' ', $evie_post_classes ) ); ?>">

				<?php
				$evie_post_image_url = get_the_post_thumbnail_url( $evie_featured_post, 'evie-fullwidth' );
				if ( ! empty( $evie_post_image_url ) ) {
					echo '<div class="slide-background" style="background-image: url(' . esc_url( $evie_post_image_url ) . ');"></div>';
				}
				?>

				<header class="slide-header">

					<span class="slide-number"><?php echo esc_attr( sprintf( '%02d', ( $evie_item_index ) ) ); ?></span>

					<?php

					if ( true === $attributes['displayCategory'] ) {
						evie_post_meta_category( $evie_featured_post, 'project_category' );
					}

					evie_post_title( $evie_featured_post, 'h2', 'slide-title' );

					?>

				</header><!-- .slide-header -->

				<div class="slide-content">

					<?php evie_portfolio_project_attributes( $evie_featured_post, 3, 1 ); ?>

				</div><!-- .slide-content -->

			</div>
			<?php endforeach; ?>
		</div>

		<div class="evie-column evie-right-column" data-reverse="true">

			<?php
			$evie_item_index = 0;
			foreach ( $evie_featured_posts as $evie_featured_post ) :
				$evie_item_index++;

				$evie_post_classes   = array( 'evie-slide', 'featured-post' );
				$evie_post_classes[] = 'featured-post-type-' . $evie_featured_post->post_type;
				$evie_post_classes[] = 'item-' . $evie_item_index;
				if ( empty( $attributes['backgroundOverlay'] ) ) {
					$evie_post_classes[] = evie_portfolio_project_scheme( $evie_featured_post );
				}
				?>
			<div class="<?php echo esc_attr( implode( ' ', $evie_post_classes ) ); ?>">

				<?php
				$evie_background_image = get_the_post_thumbnail_url( $evie_featured_post, 'evie-fullwidth' );
				if ( ! empty( $evie_background_image ) ) {
					echo '<div class="slide-background" style="background-image: url(' . esc_url( $evie_background_image ) . ');"></div>';
				}
				?>

				<div class="slide-content">

					<?php evie_portfolio_project_attributes( $evie_featured_post, 3, 1 ); ?>

				</div><!-- .slide-content -->

			</div>
		<?php endforeach; ?>

		</div>

	</div><!-- .evie-slides -->

	<div class="slider-navigation slider-pagination"></div><!-- .slider-pagination -->

	<?php } ?>

</div>
