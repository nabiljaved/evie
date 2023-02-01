<?php
/**
 * Template part for displaying project content in the Quick View
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

?>
<article <?php post_class( 'quick-view-content has-scheme-dark' ); ?>>

	<?php
	evie_post_media(
		get_the_ID(),
		'evie-fullwidth',
		array(
			'link'   => 'post',
			'slider' => array(
				'autoplay'   => 'visible',
				'navigation' => true,
			),
			'video'  => array(
				'autoplay' => 'visible',
			),
		)
	);
	?>

	<div class="content-inner">

		<div class="entry-header">

			<div class="entry-meta">

				<?php
				if ( true === get_theme_mod( 'portfolio_quick_view_category', true ) ) {
					evie_post_meta_categories( get_the_ID(), 'project_category' );
				}

				evie_post_meta_data();
				?>

				<?php evie_edit_link(); ?>

			</div>

			<?php evie_post_title( get_the_ID(), 'h1' ); ?>

		</div><!-- .entry-header -->

		<div class="entry-summary">

			<?php the_excerpt(); ?>

			<?php evie_portfolio_project_attributes( get_the_ID(), 3, 1 ); ?>

			<?php evie_more_link(); ?>

		</div><!-- .entry-summary -->

		<footer class="entry-footer">

			<?php evie_posted_on( get_the_ID(), get_theme_mod( 'portfolio_quick_view_author', true ), get_theme_mod( 'portfolio_quick_view_date', true ) ); ?>

			<?php

			if ( true === get_theme_mod( 'portfolio_quick_view_buttons', true ) ) {
				evie_post_buttons();
			}

			?>

		</footer><!-- .entry-footer -->

	</div><!-- .content-inner -->

</article><!-- .quick-view-content -->
