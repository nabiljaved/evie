<?php
/**
 * Template part for displaying a fullscreen navigation menu.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

?>
<div class="main-menu">

	<?php evie_site_logo(); ?>

	<div class="full-nav-wrapper has-scheme-dark">

		<nav id="site-navigation" class="main-navigation">
			<?php

			if ( has_nav_menu( 'primary' ) ) {

				wp_nav_menu(
					array(
						'depth'          => 3,
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'split-menu with-counters',
						'container'      => false,
						'link_before'    => '<span class="menu-text">',
						'link_after'     => '</span>',
					)
				);

			}

			?>

			<?php if ( is_active_sidebar( 'menu' ) ) : ?>

			<div class="menu-widgets">
				<div class="widget-wrapper">
				<?php
				if ( is_active_sidebar( 'menu' ) ) {
					dynamic_sidebar( 'menu' );
				} elseif ( is_customize_preview() ) {
					// Display placeholder text if the site is being previewed in the Customizer and the sidebar does not contain any widgets.
					echo esc_html__( 'There are no widgets to show right now.', 'evie' );
				}
				?>
				</div">
			</div><!-- .menu-widgets -->

			<?php endif; ?>
		</nav><!-- #site-navigation -->

	</div><!-- .full-nav-wrapper -->

	<div class="main-search-bar">

		<?php evie_live_search(); ?>

	</div><!-- .main-search-bar -->

	<?php get_template_part( 'template-parts/header/menu', 'extra' ); ?>

	<button type="button" class="menu-button" aria-label="<?php echo esc_attr( esc_html__( 'Show Menu', 'evie' ) ); ?>">
		<div class="menu-icon">
			<span></span>
			<span></span>
			<span></span>
		</div>
	</button><!-- .menu-button -->

</div><!-- .main-menu -->
