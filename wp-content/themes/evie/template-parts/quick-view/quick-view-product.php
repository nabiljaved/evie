<?php
/**
 * Template part for displaying post content in the Quick View
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

?>
<div class="woocommerce single-product">

	<article <?php post_class( 'quick-view-content single-entry' ); ?>>

		<div class="product-gallery-wrapper">
			<?php
			/**
			 * Hook: evie_wc_quick_view_product_images.
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10
			 * @hooked woocommerce_show_product_images - 20
			 */
			do_action( 'evie_wc_quick_view_product_images' );
			?>
		</div>

		<div class="summary entry-summary">
			<?php
			/**
			 * Hook: evie_wc_product_summary
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 */
			do_action( 'evie_wc_quick_view_product_summary' );
			?>

			<footer class="single-entry-footer">

				<?php evie_wc_product_buttons(); ?>

			</footer><!-- .entry-footer -->

		</div><!-- .content-inner -->

	</article><!-- .quick-view-content -->

</div>
