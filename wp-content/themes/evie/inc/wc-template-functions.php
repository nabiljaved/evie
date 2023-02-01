<?php
/**
 * Additional functions to allow styling of the WooCommerce templates.
 *
 * This file should be loaded only when the WooCommerce plugin is active.
 *
 * @package Evie
 * @version 1.0.0
 */

/**
 * Adds theme supports for WooCommerce plugin.
 */
function evie_wc_add_theme_support() {

	add_editor_style( 'assets/css/woocommerce-editor.css' );

	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width'         => 360,
			'single_image_width'            => 660,
			'gallery_thumbnail_image_width' => 145,
		)
	);

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

add_action( 'after_setup_theme', 'evie_wc_add_theme_support' );

/**
 * Registers product filter widgets area.
 */
function evie_wc_register_sidebars() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Product Filters', 'evie' ),
			'id'            => 'product-filters',
			'description'   => esc_html__( 'Add product filter widgets.', 'evie' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h2>',
			'after_title'   => '</h2></div>',
		)
	);
}

add_action( 'widgets_init', 'evie_wc_register_sidebars' );

/**
 * Dequeues default styles from WooCommerce and adds new ones from the theme.
 */
function evie_wc_enqueue_scripts() {
	// WooCommerce CSS.
	wp_dequeue_style( 'woocommerce-general' );

	wp_dequeue_style( 'woocommerce-layout' );

	wp_dequeue_style( 'woocommerce-smallscreen' );

	// Theme CSS.
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'evie-woocommerce', get_theme_file_uri( 'assets/css/woocommerce.css' ), array( 'evie' ), $theme_version );
	wp_style_add_data( 'evie-woocommerce', 'rtl', 'replace' );

	if ( true === get_theme_mod( 'wc_product_quick_view', false ) ) {

		if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
			wp_enqueue_script( 'flexslider' );
		}

		if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
			wp_enqueue_script( 'zoom' );
		}

		if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {

			wp_enqueue_script( 'photoswipe-ui-default' );

			if ( false === has_action( 'wp_footer', 'woocommerce_photoswipe' ) ) {
				add_action( 'wp_footer', 'woocommerce_photoswipe', 15 );
			}
		}

		wp_enqueue_script( 'wc-single-product' );

		wp_enqueue_script( 'wc-add-to-cart-variation' );

	}

	// If Flextension Lightbox Gallery is available, remove PhotoSwipe's default skin CSS.
	if ( wp_style_is( 'flextension-lightbox-gallery', 'registered' ) ) {
		wp_dequeue_style( 'photoswipe-default-skin' );
		wp_dequeue_script( 'photoswipe-ui-default' );
	}

	wp_enqueue_script( 'evie-woocommerce', get_theme_file_uri( 'assets/js/woocommerce.js' ), array( 'evie' ), $theme_version, true );

}

add_action( 'wp_enqueue_scripts', 'evie_wc_enqueue_scripts' );

/**
 * Adds a Quick View setting to the Product catalog settings section.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function evie_wc_add_product_quick_view_setting( $wp_customize ) {
	// WooCommerce -> Product catalog -> Product Quick View.
	$wp_customize->add_setting(
		'wc_product_quick_view',
		array(
			'default'           => false,
			'capability'        => 'manage_woocommerce',
			'sanitize_callback' => 'evie_customize_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'wc_product_quick_view',
		array(
			'section'     => 'woocommerce_product_catalog',
			'label'       => esc_html__( 'Product Quick View', 'evie' ),
			'description' => esc_html__( 'Adds a Quick View button to the products.', 'evie' ),
			'type'        => 'checkbox',
		)
	);

}

add_action( 'customize_register', 'evie_wc_add_product_quick_view_setting', 50 );

/**
 * Sets posts query for the shop page.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function evie_wc_shop_query( $query ) {
	// Only want to affect the main query.
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'product' ) && $query->is_search ) {
		$query->is_post_type_archive = false;
	}
}

add_action( 'pre_get_posts', 'evie_wc_shop_query' );

/**
 * Filters the template files for the search page.
 *
 * @param array $templates An array list of template files.
 * @return array An array list of template files.
 */
function evie_wc_search_template_files( $templates = array() ) {
	if ( is_search() ) {
		$templates[] = 'search.php';
	}

	return $templates;
}

add_filter( 'woocommerce_template_loader_files', 'evie_wc_search_template_files' );

/**
 * Returns the Shop page ID if on a product post type archive.
 *
 * @param int $id The page ID.
 * @return int The page ID.
 */
function evie_wc_get_page_id( $id ) {
	if ( is_post_type_archive( 'product' ) || ( ! is_search() && evie_is_archive( 'product' ) ) ) {
		$id = wc_get_page_id( 'shop' );
	}
	return $id;
}

add_filter( 'evie_get_page_id', 'evie_wc_get_page_id' );

/**
 * Filters the Single Page excluding list,
 * excludes product archive pages from Single Page loader.
 *
 * @param array $excluding_list An array list of the excluded links and selectors.
 * @return array An array list of the excluded links and selectors.
 */
function evie_wc_add_excluding_list( $excluding_list = array() ) {
	$permalinks = wc_get_permalink_structure();

	if ( ! empty( $permalinks ) && is_array( $permalinks ) ) {

		$links = array();

		$permalink_structure = get_option( 'permalink_structure' );

		if ( ! empty( $permalink_structure ) ) { // Using permalink.

			if ( ! empty( $permalinks['product_rewrite_slug'] ) ) {
				$links[] = $permalinks['product_rewrite_slug'] . '/';
			}

			if ( ! empty( $permalinks['category_rewrite_slug'] ) ) {
				$links[] = '/' . $permalinks['category_rewrite_slug'] . '/';
			}

			if ( ! empty( $permalinks['tag_rewrite_slug'] ) ) {
				$links[] = '/' . $permalinks['tag_rewrite_slug'] . '/';
			}

			if ( ! empty( $permalinks['attribute_rewrite_slug'] ) ) {
				$links[] = '/' . $permalinks['attribute_rewrite_slug'] . '/';
			}

			$pages = array( 'cart', 'checkout', 'myaccount' );
			foreach ( $pages as $page ) {
				$page_id = wc_get_page_id( $page );
				$slug    = get_post_field( 'post_name', $page_id );
				if ( ! empty( $slug ) ) {
					$links[] = '/' . $slug . '/';
				}
			}
		} else {

			if ( ! empty( $permalinks['product_rewrite_slug'] ) ) {
				$links[] = $permalinks['product_rewrite_slug'] . '=(.*)';
			}

			if ( ! empty( $permalinks['category_rewrite_slug'] ) ) {
				$links[] = $permalinks['category_rewrite_slug'] . '=(.*)';
			}

			if ( ! empty( $permalinks['tag_rewrite_slug'] ) ) {
				$links[] = $permalinks['tag_rewrite_slug'] . '=(.*)';
			}

			if ( ! empty( $permalinks['attribute_rewrite_slug'] ) ) {
				$links[] = $permalinks['attribute_rewrite_slug'] . '=(.*)';
			}

			$pages = array( 'cart', 'checkout', 'myaccount' );
			foreach ( $pages as $page ) {
				$page_id = wc_get_page_id( $page );
				if ( ! empty( $page_id ) ) {
					$links[] = 'p=' . $page_id;
				}
			}
		}

		if ( isset( $excluding_list['links'] ) ) {
			$excluding_list['links'] = array_merge( (array) $excluding_list['links'], $links );
		} else {
			$excluding_list['links'] = $links;
		}
	}

	$selectors = array( '.button', '.add_to_cart_button', '.remove_from_cart_button', '.remove' );

	// If YITH WooCommerce Wishlist is active.
	if ( defined( 'YITH_WCWL' ) && YITH_WCWL ) {
		$selectors[] = '.add_to_wishlist';
		$selectors[] = '.yith-wcwl-add-to-wishlist a';
	}

	$excluding_list['selectors'] = array_merge( (array) $excluding_list['selectors'], $selectors );

	return $excluding_list;
}

add_filter( 'evie_single_page_excluding_list', 'evie_wc_add_excluding_list' );

/**
 * Adds a shopping cart icon to the extra menu items.
 *
 * @param array $items An array of the extra menu items.
 * @return array An array of the extra menu items.
 */
function evie_wc_add_shopping_cart_icon( $items = array() ) {
	$items['shopping-cart'] = '<a class="shopping-cart-button" href="#" aria-label="' . esc_attr__( 'Show/Hide Shopping Cart', 'evie' ) . '"><i class="evie-ico-shopping-bag"></i>' . evie_wc_menu_cart_items() . '</a>';
	return $items;
}

add_filter( 'evie_extra_menu_items', 'evie_wc_add_shopping_cart_icon' );

/**
 * Sets the product breadcrumb options.
 *
 * @param array $options Breadcrumb options.
 * @return array New breadcrumb options.
 */
function evie_wc_breadcrumb_options( $options = array() ) {
	if ( is_product() ) {
		$options['taxonomy'] = 'product_cat';
	}
	return $options;
}

add_filter( 'evie_breadcrumb_options', 'evie_wc_breadcrumb_options' );

/**
 * Sets the shop page title for the product post type archive.
 *
 * @param array  $link      The link object of the post type archive.
 * @param string $post_type Post type.
 * @return string The link object of the post type archive.
 */
function evie_wc_breadcrumb_shop_link( $link, $post_type ) {
	if ( 'product' === $post_type || is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
		$link['text'] = get_the_title( wc_get_page_id( 'shop' ) );
		$link['url']  = wc_get_page_permalink( 'shop' );
	}
	return $link;
}

add_filter( 'evie_breadcrumb_post_type_archive_link', 'evie_wc_breadcrumb_shop_link', 10, 2 );

/**
 * Appends the Shop page link to the breadcrumb root items.
 *
 * @param array $items An array list of the items.
 * @return array An array list of the items.
 */
function evie_wc_breadcrumb_root_items( $items = array() ) {
	// If Homepage is the Shop page, don't add it.
	if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) === wc_get_page_id( 'shop' ) ) {
		return $items;
	}

	if ( ! is_shop() && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() || ( function_exists( 'yith_wcwl_is_wishlist_page' ) && true === yith_wcwl_is_wishlist_page() ) ) ) {
		$items[] = array(
			'text' => get_the_title( wc_get_page_id( 'shop' ) ),
			'url'  => wc_get_page_permalink( 'shop' ),
		);
	}

	return $items;
}

add_filter( 'evie_breadcrumb_root_items', 'evie_wc_breadcrumb_root_items' );

/**
 * Adds a breadcrumb navigation to the single product.
 */
function evie_wc_single_product_breadcrumb() {
	if ( is_singular( 'product' ) ) {
		?>
		<header class="page-header alignfull has-header-breadcrumb has-header-size-short has-header-gap-none">

			<div class="evie-container alignwide">

				<?php
				if ( class_exists( 'Evie_Breadcrumb_Generator', false ) ) {
					Evie_Breadcrumb_Generator::generate();
				}
				?>

				<nav class="navigation product-navigation">
					<?php

					previous_post_link( '%link', '<i class="evie-ico-arrow-left"></i>' );

					next_post_link( '%link', '<i class="evie-ico-arrow-right"></i>' );

					?>
				</nav>

			</div>

		</header><!-- .page-header -->
		<?php
	}
}

add_action( 'evie_before_content', 'evie_wc_single_product_breadcrumb' );

/**
 * Adds the main content wrapper to WooCommerce page.
 */
function evie_wc_before_main_content() {
	do_action( 'evie_before_content' );
}

/**
 * Replaces the content wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );

add_action( 'woocommerce_before_main_content', 'evie_wc_before_main_content' );

/**
 * Adds the main content wrapper to WooCommerce page.
 */
function evie_wc_after_main_content() {
	do_action( 'evie_after_content' );
}

/**
 * Replaces the content wrapper.
 */
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );
add_action( 'woocommerce_after_main_content', 'evie_wc_after_main_content' );

/**
 * Removes default sidebar position.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );

/**
 * Removes the breadcrumbs.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Displays a side cart.
 */
function evie_wc_side_cart() {

	if ( ! WC()->cart->is_empty() ) :
		?>

		<form class="wc-side-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

			<ul class="wc-side-cart-items">
				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = $cart_item['data'];
					$product_id = $cart_item['product_id'];

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
						$product_name      = $_product->get_name();
						$thumbnail         = $_product->get_image( 'woocommerce_gallery_thumbnail' );
						$product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
						?>
						<li class="wc-side-cart-item">
							<?php
							if ( empty( $product_permalink ) ) {
								echo '<span class="wc-side-cart-item-thumbnail">' . $thumbnail . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							} else {
								echo '<a href="' . esc_attr( esc_url( $product_permalink ) ) . '" class="wc-side-cart-item-thumbnail">' . $thumbnail . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
							<div class="wc-side-cart-item-details">
								<?php
								if ( empty( $product_permalink ) ) :
									?>
								<span class="wc-side-cart-item-name"><?php echo esc_html( $product_name ); ?></span>
								<?php else : ?>
								<a href="<?php echo esc_attr( esc_url( $product_permalink ) ); ?>" class="wc-side-cart-item-name">
									<?php echo esc_html( $product_name ); ?>
								</a>
								<?php endif; ?>
								<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<div class="wc-side-cart-quantity">
									<?php
									if ( $_product->is_sold_individually() ) {
										echo sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									} else {
										echo woocommerce_quantity_input( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $_product->get_name(),
											),
											$_product,
											false
										);
									}
									?>
									<?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							</div>
							<?php
							echo sprintf(
								'<a href="%s" class="remove remove_from_cart_button" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"></a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_attr( $product_id ),
								esc_attr( $cart_item_key ),
								esc_attr( $_product->get_sku() )
							);
							?>
						</li>
						<?php
					}
				}
				?>
			</ul>

			<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

		</form>

		<div class="wc-side-cart-subtotal">
			<?php woocommerce_widget_shopping_cart_subtotal(); ?>
		</div>

		<div class="wc-side-cart-buttons">
			<?php
			woocommerce_widget_shopping_cart_button_view_cart();
			woocommerce_widget_shopping_cart_proceed_to_checkout();
			?>
		</div>

	<?php else : ?>

		<div class="wc-side-cart-empty-message">
			<p><?php esc_html_e( 'No products in the cart.', 'evie' ); ?></p>
			<a class="button wc-backward" href="<?php echo esc_attr( wc_get_page_permalink( 'shop' ) ); ?>"><?php echo esc_html__( 'Return to shop', 'evie' ); ?></a>
		</div>

		<?php
	endif;
}

/**
 * Returns the number of items in the current cart.
 *
 * @return string HTML markup for the number of items in the current cart.
 */
function evie_wc_menu_cart_items() {
	$items_count = WC()->cart->get_cart_contents_count();
	$class       = ( $items_count > 0 ) ? '' : ' is-empty';
	return sprintf(
		'<span class="wc-cart-items%s">%s</span>',
		esc_attr( $class ),
		esc_html( number_format( $items_count, 0, '.', ',' ) )
	);
}

/**
 * Adds a menu cart fragment to the add to cart fragments.
 *
 * @param array $fragments Array of fragments.
 * @return array New fragments.
 */
function evie_wc_menu_cart_items_fragment( $fragments ) {
	$fragments['.wc-cart-items'] = evie_wc_menu_cart_items();

	ob_start();

	evie_wc_side_cart();

	$mini_cart = ob_get_clean();

	$fragments['div.widget_shopping_cart_content'] = '<div class="wc-cart-body widget_shopping_cart_content">' . $mini_cart . '</div>';

	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'evie_wc_menu_cart_items_fragment' );

/**
 * Returns HTML link for the product filter term.
 *
 * @param string  $term_html The term HTML link.
 * @param WP_Term $term      The term object.
 * @param string  $link      Term link.
 * @param int     $count     Number of products in the current term.
 * @return string New HTML link for the product filter term.
 */
function evie_wc_widget_layered_nav_term_html( $term_html, $term, $link, $count ) {
	if ( function_exists( 'evie_wc_get_attribute_option' ) ) {
		$type      = evie_wc_get_attribute_type( $term->taxonomy );
		$term_html = '<a href="' . esc_url( $link ) . '" class="attribute-link-type-' . esc_attr( $type ) . '" rel="nofollow">' . evie_wc_get_attribute_option( $term, $type ) . '</a>';
	}

	return $term_html;
}

add_filter( 'woocommerce_layered_nav_term_html', 'evie_wc_widget_layered_nav_term_html', 10, 4 );

/**
 * Returns the login URL.
 *
 * @return string The menu login URL.
 */
function evie_wc_menu_login_url() {
	return wc_get_page_permalink( 'myaccount' );
}

add_filter( 'flextension_lightbox_login_url', 'evie_wc_menu_login_url' );

/**
 * Adds a mini cart to the sidebar.
 */
function evie_wc_cart_sidebar() {
	?>
	<aside id="cart-sidebar" class="wc-cart-sidebar woocommerce evie-drawer">

		<div class="wc-cart-body widget_shopping_cart_content">

			<?php evie_wc_side_cart(); ?>

		</div>

		<div class="wc-cart-footer wc-side-cart-account">

			<?php if ( is_user_logged_in() ) : ?>

				<?php if ( function_exists( 'YITH_WCWL' ) ) : ?>

			<a href="<?php echo esc_attr( YITH_WCWL()->get_wishlist_url() ); ?>" class="wc-whishlist-button">
				<i class="evie-ico-like"></i><?php echo esc_html__( 'Whishlist', 'evie' ); ?>
			</a>

			<?php endif; ?>

			<a href="<?php echo esc_attr( wc_get_page_permalink( 'myaccount' ) ); ?>" class="wc-my-account-button">
				<i class="evie-ico-user"></i><?php echo esc_html__( 'Account', 'evie' ); ?>
			</a>

			<a href="<?php echo esc_attr( wc_logout_url() ); ?>" class="wc-logout-button">
				<i class="evie-ico-logout"></i><?php echo esc_html__( 'Log Out', 'evie' ); ?>
			</a>

			<?php else : ?>

				<?php if ( function_exists( 'YITH_WCWL' ) ) : ?>

			<a href="<?php echo esc_attr( YITH_WCWL()->get_wishlist_url() ); ?>" class="wc-whishlist-button">
				<i class="evie-ico-like"></i><?php echo esc_html__( 'Whishlist', 'evie' ); ?>
			</a>

			<?php endif; ?>

			<a href="<?php echo esc_attr( wc_get_page_permalink( 'myaccount' ) ); ?>" class="wc-login-button">
				<i class="evie-ico-login"></i><?php echo esc_html__( 'Log In', 'evie' ); ?>
			</a>

			<?php endif; ?>

		</div>

	</aside><!-- #cart-sidebar -->
	<?php
}

add_action( 'evie_after_main', 'evie_wc_cart_sidebar' );

/**
 * Filters the number of products per row.
 *
 * @return int The number of products per row.
 */
function evie_wc_loop_shop_columns() {
	return 3;
}

add_filter( 'loop_shop_columns', 'evie_wc_loop_shop_columns' );

/**
 * Adds a minus button before a quantity field.
 */
function evie_wc_before_quantity_input_field() {
	echo '<button class="wc-quantity-minus-button" aria-label="' . esc_attr( esc_html__( 'Decrease', 'evie' ) ) . '">-</button>';
}

add_action( 'woocommerce_before_quantity_input_field', 'evie_wc_before_quantity_input_field' );

/**
 * Adds a plus button after a quantity field.
 */
function evie_wc_after_quantity_input_field() {

	echo '<button class="wc-quantity-plus-button" aria-label="' . esc_attr( esc_html__( 'Increase', 'evie' ) ) . '">+</button>';
}

add_action( 'woocommerce_after_quantity_input_field', 'evie_wc_after_quantity_input_field' );

/**
 * Filters the list of categories arguments.
 *
 * @param array $args Array of categories arguments.
 * @return array An array of categories arguments. *
 */
function evie_wc_list_categories_args( $args = array() ) {
	if ( ! is_search() && is_woocommerce() ) {
		$args['taxonomy'] = 'product_cat';
	}

	return $args;
}

add_filter( 'evie_posts_filters_args', 'evie_wc_list_categories_args' );

/**
 * Sets the sortby options for the shop page.
 *
 * @param array $options An array list of the order by options.
 * @return array An array list of the order by options.
 */
function evie_wc_sortby_options( $options = array() ) {
	if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
		return $options;
	}

	$catalog_orderby_options = apply_filters(
		'evie_wc_catalog_orderby',
		array(
			'popularity' => array(
				'orderby' => 'popularity',
				'icon'    => 'flext-ico-view',
				'label'   => esc_html__( 'Popularity', 'evie' ),
			),
			'rating'     => array(
				'orderby' => 'rating',
				'icon'    => 'flext-ico-like',
				'label'   => esc_html__( 'Average rating', 'evie' ),
			),
			'date'       => array(
				'orderby' => 'date',
				'order'   => 'desc',
				'icon'    => 'evie-ico-date',
				'label'   => esc_html__( 'Latest', 'evie' ),
			),
			'price'      => array(
				'orderby' => 'price',
				'icon'    => 'evie-ico-arrow-up',
				'label'   => esc_html__( 'Price: low to high', 'evie' ),
			),
			'price-desc' => array(
				'orderby' => 'price-desc',
				'icon'    => 'evie-ico-arrow-down',
				'label'   => esc_html__( 'Price: high to low', 'evie' ),
			),
		)
	);

	if ( wc_get_loop_prop( 'is_search' ) ) {
		$catalog_orderby_options = array_merge(
			array(
				'relevance' => array(
					'orderby' => 'relevance',
					'icon'    => 'evie-ico-search',
					'label'   => esc_html__( 'Relevance', 'evie' ),
				),
			),
			$catalog_orderby_options
		);
	}

	if ( ! wc_review_ratings_enabled() ) {
		unset( $catalog_orderby_options['rating'] );
	}

	return $catalog_orderby_options;
}

add_filter( 'evie_posts_sortby_options', 'evie_wc_sortby_options' );

/**
 * Filters the current orderby query for the shop page.
 *
 * @param string $orderby The current orderby query.
 * @return string The current orderby query.
 */
function evie_wc_current_orderby( $orderby ) {
	if ( 'product' === get_post_type() ) {
		$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : get_option( 'woocommerce_default_catalog_orderby', '' );
		$orderby         = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	}

	return $orderby;
}

add_filter( 'evie_current_orderby', 'evie_wc_current_orderby' );

/**
 * Removes the result count.
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

/**
 * Removes the catalog ordering.
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

/**
 * Adds the shop loop wrapper start.
 */
function evie_wc_shop_wrapper_start() {
	$args = array(
		'layout'        => 'grid',
		'style'         => 'none',
		'hover_effect'  => 'none',
		'animation'     => '',
		'show_filter'   => true,
		'show_sortby'   => true,
		'show_title'    => true,
		'show_category' => false,
		'show_author'   => false,
		'show_date'     => false,
		'show_buttons'  => false,
		'pagination'    => true,
		'class'         => 'main-posts',
		'image_size'    => '',
		'attrs'         => array(),
		'query_vars'    => array(),
		'post_class'    => array(),
	);
	echo '<div class="' . esc_attr( evie_posts_class( $args ) ) . '">';
}

add_action( 'woocommerce_before_shop_loop', 'evie_wc_shop_wrapper_start', 1 );

/**
 * Adds a filter options to the shop page.
 *
 * @param array $options An array of options.
 */
function evie_wc_shop_filter_options( $options = array() ) {
	if ( is_shop() || is_product_category() || is_product_tag() ) {
		if ( ! is_active_sidebar( 'product-filters' ) && ! is_customize_preview() ) {
			return;
		}
		$link = ! empty( $options['all_link'] ) ? $options['all_link'] : wc_get_page_permalink( 'shop' );
		?>
		<div class="product-filter-widgets">
			<?php dynamic_sidebar( 'product-filters' ); ?>
		</div>
		<div class="filter-buttons">
			<a href="<?php echo esc_attr( $link ); ?>"><i class="evie-ico-cancel"></i><?php echo esc_html__( 'Clear all filters', 'evie' ); ?></a>
		</div>
		<?php
	}
}

/**
 * Adds a filters bar to the shop page.
 */
function evie_wc_shop_filters() {
	if ( ! is_search() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		remove_action( 'evie_posts_filter_options', 'evie_posts_filter_options' );
		add_action( 'evie_posts_filter_options', 'evie_wc_shop_filter_options' );
		evie_posts_filters(
			array(
				'show_filter' => true,
				'show_sortby' => true,
			)
		);
	}
}

add_action( 'woocommerce_before_shop_loop', 'evie_wc_shop_filters', 5 );

/**
 * Adds the shop loop wrapper end.
 */
function evie_wc_shop_wrapper_end() {
	echo '</div>';
}

add_action( 'woocommerce_after_shop_loop', 'evie_wc_shop_wrapper_end', 100 );

/**
 * Removes the category title with the products count.
 */
remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title' );

/**
 * Prints out the category title with the products count.
 *
 * @param object $category Category object.
 */
function evie_wc_shop_loop_category_title( $category ) {
	echo '<h2 class="woocommerce-loop-category__title">' . esc_html( $category->name ) . '</h2>';
	if ( $category->count > 0 ) {
		echo '<span class="wc-products-count">' . esc_html( $category->count ) . '</span>';
	}
}

add_action( 'woocommerce_shop_loop_subcategory_title', 'evie_wc_shop_loop_category_title' );

/**
 * Removes default shop page title.
 *
 * @return bool Whether the shop page title can be displayed.
 */
function evie_wc_hide_page_title() {
	return false;
}

add_filter( 'woocommerce_show_page_title', 'evie_wc_hide_page_title' );

/**
 * Removes archive description from default archive header.
 */
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description' );

/**
 * Displays an 'Out of stock' label if the product is out of stock.
 */
function evie_wc_product_stock_status() {
	global $product;

	echo '<div class="evie-product-status">';

	woocommerce_show_product_loop_sale_flash();

	$stock_status_options = wc_get_product_stock_status_options();

	if ( ! $product->is_in_stock() ) {
		echo '<span class="evie-outofstock">' . esc_html( $stock_status_options['outofstock'] ) . '</span>';
	} elseif ( $product->is_on_backorder() ) {
		echo '<span class="evie-onbackorder">' . esc_html( $stock_status_options['onbackorder'] ) . '</span>';
	}

	echo '</div>';
}

/**
 * Adds a Quick View button into the product link.
 */
function evie_wc_product_quick_view() {
	if ( function_exists( 'flextension_quick_view_button' ) && true === get_theme_mod( 'wc_product_quick_view', false ) ) {
		echo flextension_quick_view_button( get_the_ID(), 'wc-product-quick-view', 'legacy' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Returns the Quick View product image size.
 */
function evie_wc_product_quick_view_image_size() {
	return 'evie-fullwidth';
}

/**
 * Prints out product images in the Quick View lightbox.
 */
function evie_wc_product_quick_view_images() {
	add_filter( 'woocommerce_gallery_image_size', 'evie_wc_product_quick_view_image_size', 20 );
	woocommerce_show_product_images();
}

add_action( 'evie_wc_quick_view_product_images', 'evie_wc_product_stock_status' );
add_action( 'evie_wc_quick_view_product_images', 'evie_wc_product_quick_view_images', 20 );

add_action( 'evie_wc_quick_view_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'evie_wc_quick_view_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'evie_wc_quick_view_product_summary', 'woocommerce_template_single_price', 15 );
add_action( 'evie_wc_quick_view_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'evie_wc_quick_view_product_summary', 'evie_more_link', 25 );
add_action( 'evie_wc_quick_view_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

/**
 * Moves sale flash from product loop to the product stock status.
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
add_action( 'woocommerce_before_shop_loop_item_title', 'evie_wc_product_stock_status' );
add_action( 'woocommerce_before_shop_loop_item_title', 'evie_wc_product_quick_view' );

/**
 * Removes star rating from the product on Shop and Archive pages.
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

/**
 * Moves the product link close to be after the product thumbnail.
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 20 );

/**
 * Adds a product header start.
 */
function evie_wc_product_header_start() {
	echo '<div class="wc-product-content"><div class="wc-product-header">';
}

add_action( 'woocommerce_shop_loop_item_title', 'evie_wc_product_header_start', 5 );

add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 9 );

add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );

/**
 * Adds a product header end.
 */
function evie_wc_product_header_end() {
	echo '</div>';
}

add_action( 'woocommerce_after_shop_loop_item', 'evie_wc_product_header_end', 100 );

/**
 * Adds the starting tag of the button wrapper.
 */
function evie_wc_add_button_wrapper_start() {
	echo '</div><div class="wc-product-buttons">';
}

add_action( 'woocommerce_after_shop_loop_item', 'evie_wc_add_button_wrapper_start', 6 );

/**
 * Adds the closing tag of the button wrapper.
 */
function evie_wc_add_button_wrapper_end() {
	echo '</div">';
}

add_action( 'woocommerce_after_shop_loop_item', 'evie_wc_add_button_wrapper_end', 20 );

/**
 * Adds a circle SVG after the page number.
 *
 * @param array $args An array of pagination arguments.
 */
function evie_wc_pagination_args( $args = array() ) {
	$args['type']      = 'plain';
	$args['prev_text'] = '<i class="evie-ico-left"></i><span>' . esc_html__( 'Prev.', 'evie' ) . '</span>';
	$args['next_text'] = '<span>' . esc_html__( 'Next', 'evie' ) . '</span><i class="evie-ico-right"></i>';
	return $args;
}

add_filter( 'woocommerce_pagination_args', 'evie_wc_pagination_args' );

/**
 * Single Product
 */

/**
 * Moves sale flash from single product summary to the product stock status.
 */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );
add_action( 'woocommerce_before_single_product_summary', 'evie_wc_product_stock_status' );

/**
 * Adds the product gallery wrapper start.
 */
function evie_wc_product_gallery_wrapper_start() {
	echo '<div class="product-gallery-wrapper">';
}

add_action( 'woocommerce_before_single_product_summary', 'evie_wc_product_gallery_wrapper_start', 0 );

/**
 * Adds the product gallery wrapper end.
 */
function evie_wc_product_gallery_wrapper_end() {
	echo '</div><!-- .product-gallery-wrapper -->';
}

add_action( 'woocommerce_before_single_product_summary', 'evie_wc_product_gallery_wrapper_end', 50 );

/**
 * Disables default carousel navigation.
 *
 * @param array $options Array of the carousel options.
 * @return array New carousel options.
 */
function evie_wc_single_product_carousel_options( $options = array() ) {

	$options['controlNav'] = false;

	return $options;
}

add_filter( 'woocommerce_single_product_carousel_options', 'evie_wc_single_product_carousel_options' );

/**
 * Adds a main CSS class name to PhotoSwipe options.
 *
 * @param array $options Array of the PhotoSwipe options.
 * @return array New PhotoSwipe options.
 */
function evie_wc_single_product_photoswipe_options( $options = array() ) {
	$options['mainClass'] = 'flext-lightbox-gallery';
	return $options;
}

add_filter( 'woocommerce_single_product_photoswipe_options', 'evie_wc_single_product_photoswipe_options' );

/**
 * Moves the product excerpt to be after the 'Add to cart' button.
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 30 );

/**
 * Displays the buttons and actions for the product.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 */
function evie_wc_product_buttons( $post = 0 ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	echo '<div class="entry-buttons">';

	$button_class = 'evie-button';

	if ( function_exists( 'flextension_post_views_button' ) ) {
		flextension_post_views_button( $post, $button_class );
	}

	if ( function_exists( 'flextension_post_likes_button' ) ) {
		flextension_post_likes_button( $post, $button_class );
	}

	if ( function_exists( 'flextension_share_buttons' ) ) {
		flextension_share_buttons( $post, $button_class );
	}

	echo '</div>';
}

add_action( 'woocommerce_share', 'evie_wc_product_buttons' );

/**
 * Adds a wrapper starting tag to the product meta.
 */
function evie_wc_product_meta_start() {
	echo '<div class="wc-product-meta">';
}

add_action( 'woocommerce_product_meta_start', 'evie_wc_product_meta_start' );

/**
 * Adds a wrapper closing tag to the product meta.
 */
function evie_wc_product_meta_end() {
	echo '</div><button class="evie-button wc-product-meta-toggle" aria-label="' . esc_attr( esc_html__( 'Show product metadata', 'evie' ) ) . '"><i class="evie-ico-down"></i></button>';
}

add_action( 'woocommerce_product_meta_end', 'evie_wc_product_meta_end' );

/**
 * Removes the product description heading.
 */
function evie_wc_product_description_heading() {
	return '';
}

add_filter( 'woocommerce_product_description_heading', 'evie_wc_product_description_heading' );
add_filter( 'woocommerce_product_additional_information_heading', 'evie_wc_product_description_heading' );

/**
 * Filters comment pagination arguments.
 *
 * @param array $args An array of comment pagination arguments.
 * @return array New comment pagination arguments.
 */
function evie_wc_comment_pagination_args( $args = array() ) {
	$args['type']      = 'plain';
	$args['prev_text'] = '<i class="evie-ico-left"></i><span>' . esc_html__( 'Prev.', 'evie' ) . '</span>';
	$args['next_text'] = '<span>' . esc_html__( 'Next', 'evie' ) . '</span><i class="evie-ico-right"></i>';
	return $args;
}

add_filter( 'woocommerce_comment_pagination_args', 'evie_wc_comment_pagination_args' );

/**
 * Checkout Page
 */

/**
 * Adds a wrapper starting tag before the Order Review table.
 */
function evie_wc_checkout_before_order_review_heading() {
	echo '<div class="wc-order-review">';
}

add_action( 'woocommerce_checkout_before_order_review_heading', 'evie_wc_checkout_before_order_review_heading' );

/**
 * Adds a wrapper closing tag after the Order Review table.
 */
function evie_wc_checkout_after_order_review() {
	echo '</div>';
}

add_action( 'woocommerce_checkout_after_order_review', 'evie_wc_checkout_after_order_review' );

/**
 * My Account
 */

/**
 * Adds a wrapper starting tag before the login form.
 */
function evie_wc_before_customer_login_form() {
	?>
	<div class="wc-login-tabs">
	<?php
}

add_action( 'woocommerce_before_customer_login_form', 'evie_wc_before_customer_login_form' );

/**
 * Adds a wrapper closing tag after the login form.
 */
function evie_wc_after_customer_login_form() {
	echo '</div>';
}

add_action( 'woocommerce_after_customer_login_form', 'evie_wc_after_customer_login_form' );

/**
 * Adds a wrapper starting tag to the login form.
 */
function evie_wc_login_form_start() {
	echo '<div class="wc-account-form">';
}

add_action( 'woocommerce_login_form_start', 'evie_wc_login_form_start' );
add_action( 'woocommerce_register_form_start', 'evie_wc_login_form_start' );
add_action( 'woocommerce_edit_account_form_start', 'evie_wc_login_form_start' );
add_action( 'woocommerce_before_lost_password_form', 'evie_wc_login_form_start' );
add_action( 'woocommerce_before_reset_password_form', 'evie_wc_login_form_start' );

/**
 * Adds a wrapper closing tag to the login form.
 */
function evie_wc_login_form_end() {
	echo '</div>';
}

add_action( 'woocommerce_login_form_end', 'evie_wc_login_form_end' );
add_action( 'woocommerce_register_form_end', 'evie_wc_login_form_end' );
add_action( 'woocommerce_edit_account_form_end', 'evie_wc_login_form_end' );
add_action( 'woocommerce_after_lost_password_form', 'evie_wc_login_form_end' );
add_action( 'woocommerce_after_reset_password_form', 'evie_wc_login_form_end' );
