<?php
/**
 * Template part for displaying post content in the List layout.
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
<article 
<?php
post_class( $args['post_class'] );
evie_attributes(
	array(
		'style' => evie_join(
			';',
			array(
				'background-color:' . evie_portfolio_project_color() => (bool) evie_portfolio_project_color(),
			)
		),
	),
	true
);
?>
>

	<div class="entry-inner">

		<?php
		evie_post_media(
			get_the_ID(),
			'evie-landscape',
			array(
				'link'   => 'post',
				'slider' => array(
					'autoplay'   => 'visible',
					'navigation' => true,
				),
				'video'  => array(
					'autoplay' => 'visible',
				),
				'audio'  => array(
					'autoplay' => 'visible',
				),
			),
			'',
			'inside'
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

					evie_edit_link();

					?>

				</div>

				<?php

				if ( true === $args['show_title'] ) {
					evie_post_title();
				}

				?>

			</header><!-- .entry-header -->

			<div class="entry-summary">

				<?php evie_portfolio_project_attributes( get_the_ID(), 2, 1 ); ?>

				<?php evie_more_link(); ?>

			</div><!-- .entry-summary -->

			<?php if ( ( 'hide' !== $args['show_author'] && false !== $args['show_author'] ) || true === $args['show_date'] || true === $args['show_buttons'] ) : ?>
			<footer class="entry-footer">

				<?php evie_posted_on( get_the_ID(), $args['show_author'], $args['show_date'] ); ?>

				<?php

				if ( true === $args['show_buttons'] ) {
					evie_post_buttons();
				}

				?>

			</footer><!-- .entry-footer -->
			<?php endif; ?>

		</div><!-- .content-inner -->

		<?php do_action( 'evie_content', $args ); ?>

	</div><!-- .entry-inner -->

</article><!-- #post-<?php the_ID(); ?> -->
