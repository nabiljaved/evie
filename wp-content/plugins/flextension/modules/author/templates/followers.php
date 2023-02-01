<?php
/**
 * Template part for displaying followers list.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    Flextension
 * @subpackage Modules/Author/Templates
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<h4 class="flext-lightbox-title"><?php echo esc_html__( 'Followers', 'flextension' ); ?></h4>

<div class="flext-author-followers-list">

	<?php flextension_author_followers_list( 'followers', $args['id'] ); ?>

</div>
