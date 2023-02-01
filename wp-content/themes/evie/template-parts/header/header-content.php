<?php
/**
 * Template part for displaying a header content.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @var array $args Optional. Additional arguments passed to the template.
 */

if ( ! empty( $args['description'] ) ) : ?>
<p class="page-description"><?php echo esc_textarea( $args['description'] ); ?></p><!-- .page-description -->
<?php endif; ?>

<?php if ( ! empty( $args['title'] ) ) : ?>
<h1 class="page-title"><?php echo esc_html( $args['title'] ); ?></h1><!-- .page-title -->
<?php endif; ?>

<?php
if ( 'breadcrumb' === $args['layout'] && class_exists( 'Evie_Breadcrumb_Generator', false ) ) {
	Evie_Breadcrumb_Generator::generate();
}
