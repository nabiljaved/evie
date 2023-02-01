<?php
/**
 * Template part for displaying a list of posts.
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
	'evie-vertical-slider',
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
				?>
			<div class="<?php echo esc_attr( implode( ' ', $evie_post_classes ) ); ?>">

				<header class="slide-header">

					<?php

					if ( true === $attributes['displayCategory'] ) {
						evie_post_meta_categories( $evie_featured_post, 'project_category', '/' );
					}

					evie_post_title( $evie_featured_post, 'h2', 'slide-title' );

					?>

				</header><!-- .slide-header -->

				<div class="slide-text">

					<?php evie_portfolio_project_attributes( $evie_featured_post, 2, 1 ); ?>					

				</div><!-- .slide-text -->

				<?php evie_block_featured_posts_more_link( $evie_featured_post ); ?>

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
				?>
			<div class="<?php echo esc_attr( implode( ' ', $evie_post_classes ) ); ?>">

				<div class="slide-image">

					<a href="<?php echo esc_url( get_permalink( $evie_featured_post ) ); ?>" aria-hidden="true" tabindex="-1">
						<?php echo get_the_post_thumbnail( $evie_featured_post, 'evie-large' ); ?>
					</a>

				</div><!-- .slide-image -->

			</div>
			<?php endforeach; ?>
		</div>

	</div><!-- .evie-slides -->

	<div class="slider-navigation slider-pagination"></div><!-- .slider-pagination -->

	<div class="slider-background"><span class="slider-background-text"></span></div>

	<?php } ?>

</div>
