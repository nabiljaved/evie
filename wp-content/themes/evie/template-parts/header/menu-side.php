<?php
/**
 * Template part for displaying a side navigation menu.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

?>
<div class="side-menu evie-drawer">

	<nav id="side-navigation" class="side-navigation drawer-inner">

		<?php
		if ( has_nav_menu( 'mobile' ) ) {

			wp_nav_menu(
				array(
					'theme_location' => 'mobile',
					'menu_id'        => 'mobile-menu',
					'menu_class'     => 'vertical-menu with-counters',
					'container'      => is_customize_preview() ? 'div' : false,
					'after'          => '<button class="sub-menu-button" aria-label="' . esc_attr( esc_html__( 'Open Submenu', 'evie' ) ) . '"></button>',
				)
			);

		}

		get_template_part( 'template-parts/header/menu', 'widgets' );

		?>

	</nav><!-- #side-navigation -->

</div><!-- .side-menu -->
