<?php get_header(); ?>
<div class="cu-container cu-content">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'cu-article' ); ?>>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</article>
	<?php endwhile; else : ?>
		<p>No se encontró contenido.</p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
