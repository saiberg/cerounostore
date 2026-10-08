<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="cu-header">
	<div class="cu-container cu-header__row">
		<?php cu_logo(); ?>
		<?php wp_nav_menu( [ 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => false, 'menu_class' => 'cu-menu' ] ); ?>
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<form class="cu-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="search" name="s" placeholder="Buscar en cerounostore..." value="<?php echo esc_attr( get_search_query() ); ?>" aria-label="Buscar">
			<input type="hidden" name="post_type" value="product">
		</form>
		<a class="cu-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="Carrito">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6L5 3H2"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>
			<span class="cu-cart-count"><?php echo (int) WC()->cart->get_cart_contents_count(); ?></span>
		</a>
		<?php endif; ?>
	</div>
</header>
<main class="cu-main">
