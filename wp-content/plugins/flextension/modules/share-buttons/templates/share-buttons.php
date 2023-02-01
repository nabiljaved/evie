<?php
/**
 * Template part for displaying a share modal with the share links.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    Flextension
 * @subpackage Modules/Share_Buttons/Templates
 * @version    1.0.0
 *
 * @var array $args Optional. Additional arguments passed to the template.
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="modal-header">	
	<?php
	if ( has_post_thumbnail( $args['post_id'] ) ) {
		echo '<div class="modal-image">' . get_the_post_thumbnail( $args['post_id'], 'medium_large' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>
</div>
<div class="modal-body">
	<h4 class="modal-title"><?php echo esc_html( get_the_title( $args['post_id'] ) ); ?></h4>
	<div class="modal-links">
	<?php flextension_share_icons( $args['post_id'] ); ?>
	</div>
	<div class="modal-form">
		<input type="url" class="modal-permalink" readonly="readonly" value="<?php echo esc_url( get_permalink( $args['post_id'] ) ); ?>" />
		<button class="copy-clipboard" type="button" title="<?php echo esc_attr( esc_html__( 'Copy', 'flextension' ) ); ?>" aria-label="<?php echo esc_attr( esc_html__( 'Copy', 'flextension' ) ); ?>"><i class="flext-ico-copy"></i></button>
	</div>
</div>
