<?php
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', [ 'height' => 48, 'width' => 220, 'flex-width' => true ] );
	add_theme_support( 'html5', [ 'search-form', 'gallery', 'caption', 'style', 'script' ] );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	register_nav_menus( [ 'primary' => 'Menú principal' ] );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'cu-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap', [], null );
	$file = get_theme_file_path( 'assets/css/main.css' );
	wp_enqueue_style( 'cerounostore', get_theme_file_uri( 'assets/css/main.css' ), [ 'cu-fonts' ], filemtime( $file ) );

	$js = get_theme_file_path( 'assets/js/main.js' );
	wp_enqueue_script( 'cerounostore', get_theme_file_uri( 'assets/js/main.js' ), [ 'jquery' ], filemtime( $js ), true );
	wp_localize_script( 'cerounostore', 'cuData', [ 'cart' => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '' ] );
	if ( function_exists( 'WC' ) && is_front_page() ) {
		wp_enqueue_script( 'wc-add-to-cart' );
		wp_enqueue_script( 'wc-cart-fragments' );
	}
} );

add_action( 'wp_head', function () {
	if ( ! has_site_icon() ) {
		echo '<link rel="icon" type="image/svg+xml" href="' . esc_url( get_theme_file_uri( 'assets/img/mark.svg' ) ) . '">' . "\n";
	}
} );

add_filter( 'loop_shop_columns', fn() => 3 );
add_filter( 'loop_shop_per_page', fn() => 12 );
add_filter( 'woocommerce_output_related_products_args', fn( $a ) => array_merge( $a, [ 'posts_per_page' => 4, 'columns' => 4 ] ) );

add_filter( 'woocommerce_add_to_cart_fragments', function ( $f ) {
	$f['span.cu-cart-count'] = '<span class="cu-cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
	return $f;
} );

function cu_logo() {
	?>
	<a class="cu-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="cerounostore.cl">
		<?php if ( has_custom_logo() ) { the_custom_logo(); } else { ?>
			<img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/mark.svg' ) ); ?>" alt="" width="40" height="40">
			<span class="cu-word">cerouno<b>store</b><i>.cl</i></span>
		<?php } ?>
	</a>
	<?php
}

function cu_sidebar_cats() {
	$cats = get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => false, 'parent' => 0, 'exclude' => [ get_option( 'default_product_cat' ) ] ] );
	if ( ! $cats || is_wp_error( $cats ) ) {
		return;
	}
	$current = is_product_category() ? get_queried_object_id() : 0;
	?>
	<aside class="cu-side" aria-label="Categorías">
		<h3>Categorías</h3>
		<ul>
			<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="<?php echo is_shop() ? 'is-on' : ''; ?>">Todo el catálogo</a></li>
			<?php foreach ( $cats as $c ) : ?>
			<li><a href="<?php echo esc_url( get_term_link( $c ) ); ?>" class="<?php echo $current === $c->term_id ? 'is-on' : ''; ?>"><span class="cu-ico"><?php cu_icon( $c->slug ); ?></span><span class="cu-side__name"><?php echo esc_html( $c->name ); ?></span><small><?php echo (int) $c->count; ?></small></a></li>
			<?php endforeach; ?>
		</ul>
		<p class="cu-side__note">Despacho a todo Chile.<br>Cambios y devoluciones simples.</p>
	</aside>
	<?php
}

function cu_card( $p ) {
	$buy = $p->is_type( 'simple' ) && $p->is_purchasable() && $p->is_in_stock();
	?>
	<article class="cu-card">
		<a class="cu-card__img" href="<?php echo esc_url( $p->get_permalink() ); ?>">
			<?php echo wp_kses_post( $p->get_image( 'woocommerce_thumbnail' ) ); ?>
			<?php if ( $p->is_on_sale() ) : ?><span class="cu-card__sale">Oferta</span><?php endif; ?>
		</a>
		<div class="cu-card__body">
			<a class="cu-card__name" href="<?php echo esc_url( $p->get_permalink() ); ?>"><?php echo esc_html( $p->get_name() ); ?></a>
			<div class="cu-card__row">
				<span class="cu-card__price"><?php echo wp_kses_post( $p->get_price_html() ); ?></span>
				<?php if ( $buy ) : ?>
				<a class="cu-card__add add_to_cart_button ajax_add_to_cart" href="<?php echo esc_url( $p->add_to_cart_url() ); ?>" data-product_id="<?php echo (int) $p->get_id(); ?>" data-quantity="1" rel="nofollow" aria-label="Agregar al carrito">+</a>
				<?php endif; ?>
			</div>
		</div>
	</article>
	<?php
}

function cu_icon( $slug ) {
	$map = [
		'electr'  => '<rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 2v3M15 2v3M9 19v3M15 19v3M2 9h3M2 15h3M19 9h3M19 15h3"/>',
		'hogar'   => '<path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/>',
		'plast'   => '<path d="M21 8l-9-5-9 5 9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/>',
		'herram'  => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
		'juguet'  => '<rect x="2" y="7" width="20" height="10" rx="5"/><path d="M7 10v4M5 12h4"/><circle cx="16" cy="11" r=".8"/><circle cx="18" cy="13" r=".8"/>',
		'accesor' => '<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',
		'oficina' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
		'auto'    => '<path d="M5 17h14M3 13l2-6h14l2 6v4H3z"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/>',
	];
	$path = '<path d="M20 12l-8 8-9-9V3h8z"/><circle cx="7.5" cy="7.5" r="1"/>';
	foreach ( $map as $k => $p ) {
		if ( str_contains( $slug, $k ) ) {
			$path = $p;
			break;
		}
	}
	echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>'; // phpcs:ignore
}

add_action( 'woocommerce_single_product_summary', function () {
	global $product;
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		echo '<a class="cu-chip" href="' . esc_url( get_term_link( $terms[0] ) ) . '">' . esc_html( $terms[0]->name ) . '</a>';
	}
}, 4 );

add_action( 'woocommerce_single_product_summary', function () {
	global $product;
	if ( $product->is_type( 'simple' ) && $product->is_on_sale() ) {
		$reg  = (float) $product->get_regular_price();
		$sale = (float) $product->get_sale_price();
		if ( $reg > 0 && $sale > 0 ) {
			echo '<span class="cu-save">Ahorras ' . wp_kses_post( wc_price( $reg - $sale ) ) . ' (-' . (int) round( 100 - ( $sale / $reg ) * 100 ) . '%)</span>';
		}
	}
}, 11 );

add_action( 'woocommerce_single_product_summary', function () {
	?>
	<ul class="cu-perks">
		<li><b>Despacho a todo Chile</b><span>Seguimiento de tu pedido</span></li>
		<li><b>Compra protegida</b><span>Pago seguro</span></li>
		<li><b>Cambios y devoluciones</b><span>Proceso simple</span></li>
	</ul>
	<?php
}, 35 );
