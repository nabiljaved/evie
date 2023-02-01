<?php
/**
 * The header for the theme.
 *
 * This is the template that displays all of the <head> section and everything before the content
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Evie
 * @version 1.0.0
 */

?><!doctype html>
<html <?php language_attributes(); ?>>

	<head>

		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<link rel="profile" href="https://gmpg.org/xfn/11" />

		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<?php wp_body_open(); ?>

		<div id="page" class="site">
			<?php

			evie_header_navigation();

			do_action( 'evie_before_main' );

			?>
			<main id="site-content" class="main-content">
