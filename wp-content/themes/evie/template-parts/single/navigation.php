<?php
/**
 * Template part for displaying a post navigation on single post
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @var array $args Optional. Additional arguments passed to the template. See evie_post_navigation() for all.
 */

?>
<nav class="navigation post-navigation alignfull" role="navigation">

	<div class="post-nav-links">
		<?php

		if ( ! empty( $args['prev_post_thumbnail'] ) ) {

			previous_post_link(
				'<div class="nav-col nav-previous">%link</div>',
				$args['prev_post_thumbnail'] .
				'<div class="nav-text">
					<span class="nav-icon">' .
						esc_html__( 'Previous', 'evie' ) .
					'</span>
					<h4 class="nav-title">%title</h4>
				</div>'
			);

		} else {

			echo '<div class="nav-col nav-none">
					<span class="nav-text">' .
						esc_html(
							sprintf(
								/* translators: %s: Post type */
								__( 'No Older %s', 'evie' ),
								$args['post_type']
							)
						) .
					'</span>
				</div>';

		}
		?>
		<?php

		// Link to main archive page.
		if ( ! empty( $args['archive_link'] ) ) :
			?>

		<div class="nav-col nav-all">
			<a href="<?php echo esc_url( $args['archive_link'] ); ?>">
				<i class="evie-ico-archive"></i>
				<span class="nav-text"><?php echo esc_html( $args['archive_text'] ); ?></span>
			</a>
		</div>

		<?php endif; ?>

		<?php

		if ( ! empty( $args['next_post_thumbnail'] ) ) {

			next_post_link(
				'<div class="nav-col nav-next">%link</div>',
				$args['next_post_thumbnail'] .
				'<div class="nav-text">
					<span class="nav-icon">' .
						esc_html__( 'Next', 'evie' ) .
					'</span>
					<h4 class="nav-title">%title</h4>
				</div>'
			);

		} else {

			echo '<div class="nav-col nav-none">
					<span class="nav-text">' .
						esc_html(
							sprintf(
								/* translators: %s: Post type */
								__( 'No Newer %s', 'evie' ),
								$args['post_type']
							)
						) .
					'</span>
				</div>';

		}

		?>

	</div>

</nav><!-- .post-navigation -->
