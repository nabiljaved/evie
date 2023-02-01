<?php
/**
 * Template part for displaying a login form.
 *
 * @package    Flextension
 * @subpackage Modules/Lightbox_Login/Templates
 * @version    1.0.0
 *
 * @var array $args Optional. Additional arguments passed to the template. See wp_login_form() for all.
 */

defined( 'ABSPATH' ) || exit;

?>

<h4 class="flext-lightbox-title"><?php echo esc_html__( 'Log In', 'flextension' ); ?></h4>

<?php wp_login_form( $args ); ?>

<div class="flext-form-nav-links">
	<?php wp_register( '', '' ); ?>
	<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php echo esc_html__( 'Lost your password?', 'flextension' ); ?></a>
</div>
