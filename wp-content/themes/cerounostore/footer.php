</main>
<footer class="cu-footer">
	<div class="cu-container cu-footer__grid">
		<div>
			<?php cu_logo(); ?>
			<p>Tecnología, hogar y artículos útiles, con despacho a todo Chile.</p>
		</div>
		<div>
			<h4>Tienda</h4>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<ul>
				<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Todos los productos</a></li>
				<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Mi cuenta</a></li>
				<li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>">Carrito</a></li>
			</ul>
			<?php endif; ?>
		</div>
		<div>
			<h4>Ayuda</h4>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Contacto</a></li>
				<li>Cambios y devoluciones</li>
				<li>Tiempos de despacho</li>
			</ul>
		</div>
	</div>
	<div class="cu-footer__giant" aria-hidden="true">cerounostore</div>
	<div class="cu-footer__copy"><div class="cu-container">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> cerounostore.cl &middot; Todos los derechos reservados</div></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
