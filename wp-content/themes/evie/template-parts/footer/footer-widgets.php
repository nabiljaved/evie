<?php
/**
 * Template part for displaying footer widgets.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @var array $args Optional. Additional arguments passed to the template.
 */

if ( empty( $args['columns'] ) ) {
	return;
}
?>
<div class="footer-widgets<?php evie_classnames( array( "evie-grid has-{$args['columns']}-columns" => ! empty( $args['columns'] ) && absint( $args['columns'] ) > 1 ), true, true ); ?>">

	<?php evie_footer_columns( $args['columns'] ); ?>

</div><!-- .footer-widgets -->
