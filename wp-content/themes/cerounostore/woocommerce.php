<?php get_header(); ?>
<?php if ( function_exists( 'is_product' ) && ( is_product() || is_cart() || is_checkout() || is_account_page() ) ) : ?>
<div class="cu-container cu-content cu-woo">
	<?php woocommerce_content(); ?>
</div>
<?php else : ?>
<div class="cu-container cu-shell cu-woo">
	<?php cu_sidebar_cats(); ?>
	<div class="cu-feed">
		<?php woocommerce_content(); ?>
	</div>
</div>
<?php endif; ?>
<?php get_footer(); ?>
