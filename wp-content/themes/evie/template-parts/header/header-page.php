<?php
/**
 * Template part for displaying a page header.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @var     array $args Optional. Additional arguments passed to the template. See evie_page_header() for all.
 */

?>
<header class="<?php evie_header_class( $args ); ?>">

	<?php evie_header_background( $args ); ?>

	<div class="evie-container<?php evie_classname( "align{$args['width']}", (bool) $args['width'], true, true ); ?>">

		<?php get_template_part( 'template-parts/header/header-content', $args['layout'], $args ); ?>

	</div>

</header><!-- .page-header -->
