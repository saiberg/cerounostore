<?php
get_header();
$woo = class_exists( 'WooCommerce' );

$feat     = $woo ? wc_get_products( [ 'limit' => 1, 'featured' => true, 'status' => 'publish' ] ) : [];
$sale_ids = $woo ? wc_get_product_ids_on_sale() : [];
$deal     = $sale_ids ? wc_get_product( $sale_ids[0] ) : null;
$new      = $woo ? wc_get_products( [ 'limit' => 3, 'orderby' => 'date', 'order' => 'DESC', 'status' => 'publish' ] ) : [];
$top      = $woo ? wc_get_products( [ 'limit' => 6, 'orderby' => 'popularity', 'status' => 'publish' ] ) : [];
$all_cats = $woo ? get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => false, 'parent' => 0, 'exclude' => [ get_option( 'default_product_cat' ) ] ] ) : [];
$rails    = $woo ? array_slice( array_filter( (array) $all_cats, fn( $c ) => $c->count > 0 ), 0, 4 ) : [];
$n_prod   = $woo ? (int) wp_count_posts( 'product' )->publish : 0;
$n_cat    = is_array( $all_cats ) ? count( $all_cats ) : 0;

$off = 0;
if ( $deal && (float) $deal->get_regular_price() > 0 ) {
	$off = (int) round( 100 - ( (float) $deal->get_sale_price() / (float) $deal->get_regular_price() ) * 100 );
}
$end = ( new DateTime( 'sunday this week 23:59:59', wp_timezone() ) )->getTimestamp();
?>
<div class="cu-container cu-shell">
	<?php if ( $woo ) { cu_sidebar_cats(); } ?>
	<div class="cu-feed">

		<section class="cu-bento">
			<div class="cu-t cu-t--hero" data-spot>
				<span class="cu-glyph" aria-hidden="true">01</span>
				<span class="cu-pill"><i></i> Nueva tienda online</span>
				<h1>Lo mejor en <span class="cu-rot"><b class="on">tecnología</b><b>hogar</b><b>herramientas</b><b>orden</b></span> para tu día a día.</h1>
				<p>Productos útiles, bien elegidos y con despacho a todo Chile.</p>
				<?php if ( $woo ) : ?>
				<div class="cu-hero__cta">
					<a class="cu-btn" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Explorar catálogo</a>
					<?php if ( $deal ) : ?><a class="cu-btn cu-btn--ghost" href="#oferta">Oferta de la semana</a><?php endif; ?>
				</div>
				<?php endif; ?>
				<ul class="cu-stats">
					<li><b><?php echo (int) $n_prod; ?></b><span>productos</span></li>
					<li><b><?php echo (int) $n_cat; ?></b><span>categorías</span></li>
					<li><b>CL</b><span>despacho nacional</span></li>
				</ul>
			</div>

			<?php if ( $feat ) : $p = $feat[0]; ?>
			<a class="cu-t cu-t--prod" href="<?php echo esc_url( $p->get_permalink() ); ?>">
				<?php echo wp_kses_post( $p->get_image( 'large' ) ); ?>
				<span class="cu-t__cap"><small>Destacado</small><strong><?php echo esc_html( $p->get_name() ); ?></strong><em><?php echo wp_kses_post( $p->get_price_html() ); ?></em></span>
			</a>
			<?php endif; ?>

			<?php if ( $deal ) : ?>
			<a class="cu-t cu-t--sale" href="#oferta">
				<small>Oferta</small>
				<strong><?php echo $off ? esc_html( '-' . $off . '%' ) : 'Rebaja'; ?></strong>
				<span><?php echo esc_html( $deal->get_name() ); ?></span>
			</a>
			<?php endif; ?>

			<div class="cu-t cu-t--new">
				<small>Recién llegados</small>
				<div class="cu-t__thumbs">
					<?php foreach ( $new as $p ) : ?>
					<a href="<?php echo esc_url( $p->get_permalink() ); ?>" title="<?php echo esc_attr( $p->get_name() ); ?>"><?php echo wp_kses_post( $p->get_image( 'thumbnail' ) ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<?php if ( $all_cats && ! is_wp_error( $all_cats ) ) : ?>
		<div class="cu-marquee" aria-label="Categorías">
			<div class="cu-marquee__track">
				<?php for ( $k = 0; $k < 2; $k++ ) : foreach ( $all_cats as $c ) : ?>
				<a href="<?php echo esc_url( get_term_link( $c ) ); ?>" <?php echo $k ? 'tabindex="-1" aria-hidden="true"' : ''; ?>><span class="cu-ico"><?php cu_icon( $c->slug ); ?></span><?php echo esc_html( $c->name ); ?></a>
				<?php endforeach; endfor; ?>
			</div>
		</div>
		<?php endif; ?>

		<?php if ( $deal ) : ?>
		<section id="oferta" class="cu-deal rv">
			<a class="cu-deal__img" href="<?php echo esc_url( $deal->get_permalink() ); ?>">
				<?php echo wp_kses_post( $deal->get_image( 'large' ) ); ?>
				<?php if ( $off ) : ?><span class="cu-deal__chip">-<?php echo (int) $off; ?>%</span><?php endif; ?>
			</a>
			<div class="cu-deal__body">
				<span class="cu-kicker">Oferta de la semana</span>
				<h2><?php echo esc_html( $deal->get_name() ); ?></h2>
				<div class="cu-deal__price"><?php echo wp_kses_post( $deal->get_price_html() ); ?></div>
				<div class="cu-count" data-end="<?php echo (int) $end; ?>" aria-label="Tiempo restante">
					<div><b data-d>00</b><small>días</small></div>
					<div><b data-h>00</b><small>horas</small></div>
					<div><b data-m>00</b><small>min</small></div>
					<div><b data-s>00</b><small>seg</small></div>
				</div>
				<a class="cu-btn" href="<?php echo esc_url( $deal->get_permalink() ); ?>">Aprovechar oferta</a>
			</div>
		</section>
		<?php endif; ?>

		<?php foreach ( $rails as $idx => $cat ) :
			$items = wc_get_products( [ 'limit' => 8, 'category' => [ $cat->slug ], 'status' => 'publish' ] );
			if ( ! $items ) { continue; } ?>
		<section class="cu-rail rv">
			<div class="cu-rail__head">
				<span class="cu-num"><?php echo esc_html( str_pad( (string) ( $idx + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
				<h2><?php echo esc_html( $cat->name ); ?></h2>
				<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>">Ver todo &rarr;</a>
			</div>
			<div class="cu-rail__track">
				<?php foreach ( $items as $p ) { cu_card( $p ); } ?>
			</div>
		</section>
		<?php endforeach; ?>

		<?php if ( $top ) : ?>
		<section class="cu-rank rv">
			<div class="cu-rail__head"><span class="cu-num">★</span><h2>Selección cerouno</h2></div>
			<ol>
				<?php foreach ( $top as $i => $p ) : ?>
				<li>
					<a href="<?php echo esc_url( $p->get_permalink() ); ?>">
						<b><?php echo (int) ( $i + 1 ); ?></b>
						<?php echo wp_kses_post( $p->get_image( 'thumbnail' ) ); ?>
						<span><strong><?php echo esc_html( $p->get_name() ); ?></strong><small><?php echo esc_html( wp_strip_all_tags( wc_get_product_category_list( $p->get_id(), ', ' ) ) ); ?></small></span>
						<em><?php echo wp_kses_post( $p->get_price_html() ); ?></em>
					</a>
				</li>
				<?php endforeach; ?>
			</ol>
		</section>
		<?php endif; ?>

		<section class="cu-cta rv">
			<h2>¿No encuentras lo que buscas?</h2>
			<p>Cuéntanos qué necesitas y lo buscamos por ti.</p>
			<a class="cu-btn" href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Escríbenos</a>
		</section>

	</div>
</div>
<?php get_footer(); ?>
