<?php
/**
 * Template part for displaying a header navigation.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

?>
<header id="site-header" class="main-header">

	<?php

	get_template_part( 'template-parts/header/menu', $args['type'], $args );

	get_template_part( 'template-parts/header/menu', 'side', $args );

	?>

</header><!-- #site-header -->
