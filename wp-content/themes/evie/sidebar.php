<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Evie
 * @version 1.0.0
 */

// Hide the sidebar if it does not contain any widgets and if the site is not being previewed in the Customizer.
if ( ! is_active_sidebar( 'main' ) && ! is_customize_preview() ) {
	return;
}
?>
<aside id="site-sidebar" class="main-sidebar evie-drawer">

	<div class="widget-wrapper drawer-inner">
	<?php

	if ( is_active_sidebar( 'main' ) ) {

		dynamic_sidebar( 'main' );

	} elseif ( is_customize_preview() ) {
		// Display placeholder text if the site is being previewed in the Customizer and the sidebar does not contain any widgets.
		echo esc_html__( 'There are no widgets to show right now.', 'evie' );
	}

	?>
	</div>

</aside><!-- #site-sidebar -->
