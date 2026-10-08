<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="dtp-topbar"><div class="dtp-marquee"><?php for ( $i = 0; $i < 2; $i++ ) : ?><span>&#9733; Envíos a todo el país</span><span>&#9733; Compra segura</span><span>&#9733; Nuevos productos cada semana</span><span>&#9733; Tecnología, hogar y más</span><?php endfor; ?></div></div>
<header class="dtp-header">
	<div class="dtp-container dtp-header__inner">
		<a class="dtp-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php if ( has_custom_logo() ) { the_custom_logo(); } else { ?>
				<span class="dtp-logo__mark">D</span><span class="dtp-logo__text"><?php bloginfo( 'name' ); ?></span>
			<?php } ?>
		</a>
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<form class="dtp-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="search" name="s" placeholder="Buscar productos..." value="<?php echo esc_attr( get_search_query() ); ?>">
			<input type="hidden" name="post_type" value="product">
			<button type="submit" aria-label="Buscar">&#128269;</button>
		</form>
		<a class="dtp-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
			&#128722; <span class="dtp-cart-count"><?php echo (int) WC()->cart->get_cart_contents_count(); ?></span>
		</a>
		<?php endif; ?>
	</div>
	<nav class="dtp-nav">
		<div class="dtp-container">
			<?php wp_nav_menu( [ 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => false, 'menu_class' => 'dtp-menu' ] ); ?>
		</div>
	</nav>
</header>
<main class="dtp-main">
