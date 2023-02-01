<?php
/**
 * Template part for displaying post content in the Quick View
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    Flextension
 * @subpackage Modules/Quick_View/Templates
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<article <?php post_class( 'quick-view-content' ); ?>>

	<div class="entry-media">
		<?php the_post_thumbnail( 'large' ); ?>
	</div>

	<div class="content-inner">

		<div class="entry-header">

			<div class="entry-meta">

				<?php the_category( ', ' ); ?>

				<?php edit_post_link(); ?>

			</div>

			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		</div><!-- .entry-header -->

		<div class="entry-summary">

			<?php the_excerpt(); ?>

		</div>

		<footer class="entry-footer">
			<?php
			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
				comments_popup_link();
			}
			?>

		</footer><!-- .entry-footer -->

	</div><!-- .content-inner -->

</article><!-- .quick-view-content -->
