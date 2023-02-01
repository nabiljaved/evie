<?php
/**
 * Template part for displaying the menu widgets.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

if ( is_active_sidebar( 'menu' ) || is_customize_preview() ) : ?>
<aside class="menu-widgets">

	<div class="widget-wrapper">

		<?php
		if ( is_active_sidebar( 'menu' ) ) {
			dynamic_sidebar( 'menu' );
		} elseif ( is_customize_preview() ) {
			// Display placeholder text if the site is being previewed in the Customizer and the sidebar does not contain any widgets.
			echo esc_html__( 'There are no widgets to show right now.', 'evie' );
		}

		?>

	</div>

</aside><!-- .menu-widgets -->
	<?php
endif;
