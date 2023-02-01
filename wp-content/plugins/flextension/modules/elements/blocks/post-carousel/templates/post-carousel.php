<?php
/**
 * Template part for displaying a Post Carousel.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    Flextension
 * @subpackage Modules/Elements/Templates
 * @version    1.0.0
 *
 * @var array $args Arguments passed to the template. See flextension_block_post_carousel_render() for more details.
 */

defined( 'ABSPATH' ) || exit;

?>
<div <?php echo get_block_wrapper_attributes( flextension_block_post_carousel_wrapper_attributes( $args['attributes'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

	<?php if ( true === $args['attributes']['navigation'] || ! empty( $args['attributes']['title'] ) ) : ?>

	<div class="post-carousel-header">

		<?php if ( ! empty( $args['attributes']['title'] ) ) { ?>
		<h2 class="block-title"><?php echo esc_html( $args['attributes']['title'] ); ?></h2>
		<?php } ?>

		<?php flextension_block_post_carousel_link( $args['attributes'] ); ?>

	</div><!-- .post-carousel-header -->

	<?php endif; ?>

	<div <?php flextension_attributes( flextension_block_post_carousel_slider_attributes( $args['attributes'] ) ); ?>>

		<div class="flext-carousel-wrapper">

			<?php

			while ( $args['posts_query']->have_posts() ) {
				$args['posts_query']->the_post();
				flextension_block_post_content( $args['attributes'] );
			}

			wp_reset_postdata();
			?>

		</div><!-- .flext-carousel-wrapper -->

	</div>

</div>
