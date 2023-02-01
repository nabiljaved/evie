<?php
/**
 * Template part for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Evie
 * @version 1.0.0
 */

/**
 * Hook: evie_before_content.
 *
 * @hooked evie_content_header
 */
do_action( 'evie_before_content' );

?>

<div class="error-404 not-found entry-content">
	<div class="page-404-error">
		<div class="page-error-code"><?php echo esc_html__( '404', 'evie' ); ?></div>
		<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'evie' ); ?></h1>
		<p><?php echo esc_html__( 'It looks like nothing was found at this location. Maybe try a search?', 'evie' ); ?></p>
	</div>
	<?php get_search_form(); ?>

	<div class="page-suggestions">
		<?php do_action( 'evie_page_suggestions' ); ?>
	</div><!-- .page-suggestions -->

</div><!-- .error-404 -->

<?php

do_action( 'evie_after_content' );
