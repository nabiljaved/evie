<?php
/**
 * Template part for displaying a single page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

?>
<article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * Hook: evie_before_content.
	 *
	 * @hooked evie_content_header
	 */
	do_action( 'evie_before_content' );
	?>

	<div class="entry-content">

		<?php

		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'evie' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);

		?>

	</div>

	<?php wp_link_pages(); ?>

	<?php do_action( 'evie_after_content' ); ?>

</article><!-- #page-<?php the_ID(); ?> -->
