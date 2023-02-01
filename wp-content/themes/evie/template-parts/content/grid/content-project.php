<?php
/**
 * Template part for displaying project content in the Grid layout.
 *
 * This template file should be loaded by evie_content_template().
 *
 * @see evie_content_template()
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @var array $args Arguments passed to the template. See evie_posts_args() for additional options.
 */

?>
<article <?php post_class( $args['post_class'] ); ?>>

	<?php
	evie_post_media(
		get_the_ID(),
		empty( $args['image_size'] ) ? ( 'card' !== $args['style'] ? 'evie-portrait' : 'evie-square' ) : $args['image_size'],
		array(
			'link' => 'post',
		),
		'',
		'outside'
	);

	?>

	<div class="content-inner">

		<header class="entry-header">

			<div class="entry-meta">

				<?php

				if ( true === $args['show_category'] ) {
					evie_post_meta_category( get_the_ID(), 'project_category' );
				}

				evie_post_meta_data();

				?>

				<?php evie_edit_link(); ?>

			</div>

			<?php

			if ( true === $args['show_title'] ) {

				evie_post_title();

			}

			?>

		</header><!-- .entry-header -->

		<?php

		if ( true === $args['show_buttons'] ) {
			evie_post_buttons();
		}

		?>

	</div><!-- .content-inner -->

	<?php if ( ( 'hide' !== $args['show_author'] && false !== $args['show_author'] ) || true === $args['show_date'] ) : ?>
	<footer class="entry-footer">
		<?php evie_posted_on( get_the_ID(), $args['show_author'], $args['show_date'] ); ?>
	</footer><!-- .entry-footer -->
	<?php endif; ?>

	<?php do_action( 'evie_content', $args ); ?>

</article>
