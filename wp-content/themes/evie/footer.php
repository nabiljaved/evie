<?php
/**
 * The template for displaying the footer
 *
 * Contains all content after the main content.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Evie
 * @version 1.0.0
 */

?>
			</main><!-- #site-content -->

			<?php do_action( 'evie_after_main' ); ?>

			<?php get_sidebar(); ?>

			<footer id="site-footer" class="main-footer">

			<div class="upper_footer_section">
				<div class="upper_footer_section_dropshadow"></div>
				<div class="upper_footer_section_inner">
					<div class="upper_footer_section_inner_left">
						<h1> Feed Your <span style="color: tomato">Eyes</span> .</h1>
						<p>Subscribe to our newsletter and never miss the new updates.</p>
					</div>
					<div class="upper_footer_section_inner_right">
					      <input size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" placeholder="Your email address" value="" type="email" name="email-278">
						  <input style="margin-left:10px" class="wpcf7-form-control has-spinner wpcf7-submit" type="submit" value="Subscribe">
				    </div>
				</div>
			</div>

				<?php

				evie_footer_widgets();

				evie_footer_info();

				?>

			</footer><!-- #site-footer -->

			<div id="site-content-overlay" class="main-content-overlay"></div><!-- #site-content-overlay -->

			<a href="#top" id="back-to-top" class="to-top-button">
				<i class="evie-ico-arrow-up"></i>
				<span><?php echo esc_html__( 'Back To Top', 'evie' ); ?></span>
			</a><!-- #back-to-top -->

		</div><!-- #page -->

		<?php wp_footer(); ?>

	</body>

</html>
