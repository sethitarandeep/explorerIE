<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package kurama
 */

get_header(); ?>

	<div id="primary" class="content-areas <?php do_action('kurama_primary-width') ?>">
		<main id="main" class="site-main" role="main">
		<div class="section-title home-title">
			<span><div><?php esc_html_e('Recent Posts','kurama'); ?></div></span>
		</div>	
		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 */
					do_action('kurama_blog_layout'); 
					
				?>

			<?php endwhile; ?>

			<?php kurama_pagination(); ?>

		<?php else : ?>

			<?php get_template_part( 'modules/content/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
