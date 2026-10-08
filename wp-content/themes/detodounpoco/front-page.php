<?php
get_header();
$cats = class_exists( 'WooCommerce' ) ? get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => false, 'exclude' => [ get_option( 'default_product_cat' ) ] ] ) : [];
$icons = [ 'electr' => '&#128268;', 'hogar' => '&#127859;', 'pl' => '&#129529;', 'herram' => '&#128295;', 'juguet' => '&#129528;', 'accesor' => '&#127890;', 'oficina' => '&#128221;', 'auto' => '&#128663;' ];
?>
<section class="dtp-hero">
	<div class="dtp-container dtp-hero__inner">
		<div>
			<span class="dtp-badge">El bazar de siempre, ahora online</span>
			<h1>De todo un poco, <mark>y todo a buen precio</mark></h1>
			<p>Gadgets, hogar, herramientas, plásticos y accesorios. Un solo lugar para todo lo que andabas buscando.</p>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<a class="dtp-btn" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Ver tienda</a>
				<a class="dtp-btn dtp-btn--ghost" href="#ofertas">Ofertas</a>
			<?php endif; ?>
		</div>
		<div class="dtp-hero__art" aria-hidden="true">
			<?php $hero = wp_get_attachment_image_url( (int) get_option( 'dtp_hero_image' ), 'large' );
			if ( $hero ) : ?><img src="<?php echo esc_url( $hero ); ?>" alt=""><?php else : ?>&#128722;<?php endif; ?>
			<span class="dtp-sticker">¡Nuevo<br>cada<br>semana!</span>
		</div>
	</div>
</section>

<section class="dtp-benefits dtp-container">
	<div><strong>&#128666; Envío rápido</strong><span>A todo el país</span></div>
	<div><strong>&#128274; Pago seguro</strong><span>Tus datos protegidos</span></div>
	<div><strong>&#8617; Devoluciones</strong><span>Sin complicaciones</span></div>
	<div><strong>&#128172; Soporte</strong><span>Te ayudamos siempre</span></div>
</section>

<?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
<section class="dtp-section dtp-container">
	<h2 class="dtp-title"><small>Pasillo 01</small>Compra por categoría</h2>
	<div class="dtp-cats">
		<?php foreach ( $cats as $cat ) :
			$icon = '&#128230;';
			$slug = $cat->slug;
			foreach ( $icons as $k => $i ) { if ( str_contains( $slug, $k ) ) { $icon = $i; break; } }
		?>
		<a class="dtp-cat" href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
			<span class="dtp-cat__icon"><?php
				$thumb = (int) get_term_meta( $cat->term_id, 'thumbnail_id', true );
				if ( $thumb ) { echo wp_get_attachment_image( $thumb, 'medium', false, [ 'class' => 'dtp-cat__img', 'loading' => 'lazy' ] ); } else { echo $icon; } // phpcs:ignore
			?></span>
			<span><?php echo esc_html( $cat->name ); ?></span>
		</a>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<?php if ( class_exists( 'WooCommerce' ) ) : ?>
<section class="dtp-section dtp-container">
	<h2 class="dtp-title"><small>Pasillo 02</small>Los favoritos</h2>
	<?php echo do_shortcode( '[products limit="4" columns="4" visibility="featured"]' ); ?>
</section>

<section id="ofertas" class="dtp-section dtp-container">
	<h2 class="dtp-title"><small>Pasillo 03</small>Ofertas</h2>
	<?php echo do_shortcode( '[products limit="4" columns="4" on_sale="true"]' ); ?>
</section>

<section class="dtp-section dtp-container">
	<h2 class="dtp-title"><small>Pasillo 04</small>Recién llegados</h2>
	<?php echo do_shortcode( '[products limit="8" columns="4" orderby="date" order="DESC"]' ); ?>
</section>
<?php endif; ?>
<?php get_footer(); ?>
