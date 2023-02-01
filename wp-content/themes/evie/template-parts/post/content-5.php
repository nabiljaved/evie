<?php
/**
 * Template part for displaying a single project in a layout #1.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * Hook: evie_before_content.
	 *
	 * @hooked evie_content_header
	 */
	do_action( 'evie_before_content' );
	?>

	<header class="entry-header single-entry-header alignfull">
		<div class="header-content">
			<div class="entry-meta">
				<?php

				evie_post_meta_categories();

				evie_single_post_meta_data();

				?>
			</div>
			<?php evie_post_title(); ?>
		</div>
		<div class="evie-container parallax-background">
			<?php

			evie_post_media(
				get_the_ID(),
				'evie-fullwidth'
			);

			?>
		</div>
	</header><!-- .entry-header -->

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

	</div><!-- .entry-content -->

	<?php wp_link_pages(); ?>

	<?php evie_single_post_footer(); ?>

	<?php do_action( 'evie_after_content' ); ?>

</article><!-- #post-<?php the_ID(); ?> -->
