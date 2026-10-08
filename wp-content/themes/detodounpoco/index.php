<?php get_header(); ?>
<div class="dtp-container dtp-content">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'dtp-article' ); ?>>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</article>
	<?php endwhile; else : ?>
		<p>No se encontró contenido.</p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
