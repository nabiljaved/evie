<?php
/**
 * Template part for displaying a navigation menu.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @var array $args Optional. Additional arguments passed to the template. See evie_posts() for all.
 */

?>
<div class="main-menu<?php evie_classname( "menu-align-{$args['align']}", (bool) $args['align'], true, true ); ?>">

	<?php evie_site_logo(); ?>

	<nav id="site-navigation" class="main-navigation">

		<?php
		if ( has_nav_menu( 'primary' ) ) {

			wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'menu_id'         => 'primary-menu',
					'menu_class'      => 'dropdown-menu with-counters',
					'container'       => is_customize_preview() ? 'div' : false,
					'container_class' => 'main-navigation-menu',
					'link_before'     => '<span class="menu-text">',
					'link_after'      => '</span>',
				)
			);

		}
		?>

		<div class="main-search-bar">

			<?php evie_live_search(); ?>

		</div>

	</nav><!-- #site-navigation -->

	<?php get_template_part( 'template-parts/header/menu', 'extra', $args ); ?>

	<button type="button" class="menu-button" aria-label="<?php echo esc_attr( esc_html__( 'Show Menu', 'evie' ) ); ?>">
		<div class="menu-icon">
			<span></span>
			<span></span>
			<span></span>
		</div>
	</button><!-- .menu-button -->

	<?php get_template_part( 'template-parts/header/menu', 'widgets' ); ?>

</div><!-- .main-menu -->
