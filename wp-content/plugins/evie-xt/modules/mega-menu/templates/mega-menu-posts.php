<?php
/**
 * Template part for displaying recent posts in the Mega Menu.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    Evie_XT
 * @subpackage Modules/Mega_Menu/Templates
 * @version    1.0
 *
 * @var array $args Optional. Arguments to retrieve posts. See WP_Query::parse_query() for all available arguments.
 */

$evie_recent_posts = get_posts( $args );

if ( count( $evie_recent_posts ) ) :

	foreach ( $evie_recent_posts as $evie_recent_post ) :

		?>

		<article <?php post_class( 'mega-menu-post', $evie_recent_post ); ?>>

			<?php
			evie_post_media(
				$evie_recent_post,
				'post-thumbnail',
				array(
					'link'   => 'post',
					'slider' => array(
						'autoplay'        => 'hover',
						'show_count'      => true,
						'slides-per-view' => 'auto',
					),
					'video'  => array(
						'autoplay' => 'hover',
						'controls' => false,
					),
					'audio'  => array(
						'autoplay' => 'hover',
						'controls' => false,
					),
				),
				'',
				'project' === $evie_recent_post->post_type ? 'inside' : false
			);
			?>

			<header class="entry-header">

				<?php evie_post_title( $evie_recent_post ); ?>

			</header><!-- .entry-header -->

		</article>

		<?php

	endforeach;

else :
	?>
	<div class="entry">
		<?php echo esc_html__( 'There are no posts to show right now.', 'evie-xt' ); ?>
	</div>
	<?php
endif;
