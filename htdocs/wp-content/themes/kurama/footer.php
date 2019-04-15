<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package kurama
 */
?>

		</div><!-- #content -->
	
	</div><!--.mega-container-->

<?php get_template_part('modules/navigation/secondary-menu'); ?>


<?php get_sidebar('footer'); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info container">
			<?php printf( __( 'Theme Designed by %1$s.', 'kurama' ), '<a target="blank" href="'.esc_url("http://inkhive.com/").'" rel="designer">InkHive.com</a>' ); ?>
			<span class="sep"></span>
			<?php echo ( get_theme_mod('kurama_footer_text') == '' ) ? ('&copy; '.date('Y').' '.get_bloginfo('name').__('. All Rights Reserved. ','kurama')) : esc_html( get_theme_mod('kurama_footer_text') ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
	
</div><!-- #page -->


<?php wp_footer(); ?>

</body>
</html>
