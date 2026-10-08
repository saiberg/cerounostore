</main>
<footer class="dtp-footer">
	<div class="dtp-container dtp-footer__grid">
		<div>
			<h4><?php bloginfo( 'name' ); ?></h4>
			<p>Tecnología, hogar, plásticos y mucho más en un solo lugar.</p>
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
			<p>Cambios y devoluciones<br>Tiempos de envío<br>Contacto</p>
		</div>
	</div>
	<div class="dtp-footer__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
