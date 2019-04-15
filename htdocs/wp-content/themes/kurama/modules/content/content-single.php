<?php
/**
 * @package kurama
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( (get_theme_mod('kurama_featimg','replace') == 'noreplace' ) ) : ?>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php endif; ?>
		
	</header><!-- .entry-header -->
	
	<?php if ( (get_theme_mod('kurama_featimg','replace') == 'noreplace' ) ) : ?>
		<div id="featured-image">
			<?php the_post_thumbnail('full'); ?>
		</div>
	<?php endif; ?>	
	
			
			
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'kurama' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	
	<div class="entry-meta">
           <?php kurama_posted_on(); ?>
		</div><!-- .entry-meta -->
	<footer class="entry-footer">
		<?php kurama_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
