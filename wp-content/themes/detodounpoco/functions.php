<?php
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', [ 'height' => 60, 'width' => 200, 'flex-width' => true ] );
	add_theme_support( 'html5', [ 'search-form', 'gallery', 'caption', 'style', 'script' ] );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	register_nav_menus( [ 'primary' => 'Menú principal' ] );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'detodounpoco-fonts', 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700;800&family=DM+Sans:wght@400;500;700&display=swap', [], null );
	$file = get_theme_file_path( 'assets/css/main.css' );
	wp_enqueue_style( 'detodounpoco', get_theme_file_uri( 'assets/css/main.css' ), [ 'detodounpoco-fonts' ], filemtime( $file ) );
} );

add_filter( 'loop_shop_columns', fn() => 4 );
add_filter( 'woocommerce_output_related_products_args', fn( $a ) => array_merge( $a, [ 'posts_per_page' => 4, 'columns' => 4 ] ) );

// Contador del carrito actualizado por AJAX.
add_filter( 'woocommerce_add_to_cart_fragments', function ( $f ) {
	$f['span.dtp-cart-count'] = '<span class="dtp-cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
	return $f;
} );
