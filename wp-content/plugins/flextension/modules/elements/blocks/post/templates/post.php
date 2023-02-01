<?php
/**
 * Template part for displaying post content.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    Flextension
 * @subpackage Modules/Elements/Templates
 * @version    1.0.0
 *
 * @var array $args Arguments passed to the template. See flextension_block_post_content() for more details.
 */

defined( 'ABSPATH' ) || exit;

?>
<article <?php post_class( array( 'flext-post', $args['postClass'] ) ); ?>>

	<?php

	flextension_post_media(
		get_the_ID(),
		'post-thumbnail',
		array(
			'link' => 'post',
		)
	);

	?>

	<div class="content-inner">

		<header class="flext-post-header">
			<?php
			if ( true === $args['displayCategory'] ) {
				?>
				<div class="flext-post-meta">
					<?php

					flextension_post_single_category();

					if ( function_exists( 'flextension_reading_time' ) ) {
						flextension_reading_time( get_the_ID(), true );
					}

					?>
				</div>
				<?php
			}

			if ( true === $args['displayTitle'] ) {
				flextension_post_title();
			}

			?>
		</header><!-- .entry-header -->

		<footer class="flext-post-footer">
			<?php

			flextension_posted_on( get_the_ID(), $args['displayDate'], $args['displayAuthor'] );

			if ( true === $args['displayButtons'] ) {
				?>
				<div class="flext-post-buttons">
					<?php flextension_post_buttons(); ?>
				</div>
				<?php
			}
			?>
		</footer><!-- .entry-footer -->

	</div><!-- .content-inner -->
	<?php

	/**
	 * Fires after render the post content.
	 *
	 * @since 1.0.8
	 *
	 * @param $args Arguments passed to the template.
	 */
	do_action( 'flextension_post_content', $args );
	?>

</article>
