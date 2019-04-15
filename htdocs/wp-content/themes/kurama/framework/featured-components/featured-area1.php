<div id="featured-area-1">
<div class="container-fluid">
<?php if ( get_theme_mod('kurama_box_enable') && is_front_page() ) : ?>
	<div class="popular-articles col-md-12">	
		
		<?php /* Start the Loop */ $count=0; ?>
				<?php
		    		$args = array( 'posts_per_page' => 5, 'category' => get_theme_mod('kurama_box_cat') );
					$lastposts = get_posts( $args );
					foreach ( $lastposts as $post ) :
					  setup_postdata( $post ); ?>
				
				    <div class="col-md-3 col-sm-6 col-xs-6 imgcontainer">
				    	<div class="popimage">
				        <?php if (has_post_thumbnail()) : ?>	
								<a href="<?php the_permalink() ?>"><?php the_post_thumbnail('kurama-pop-thumb'); ?></a>
						<?php else : ?>
								<a href="<?php the_permalink() ?>"><img src="<?php echo get_template_directory_uri()."/assets/images/placeholder2.jpg"; ?>"></a>
						<?php endif; ?>
							<div class="titledesc">
				            <h3><a href="<?php the_permalink() ?>"><?php echo the_title(); ?></a></h3>
				            <span class="readmore title-font"><a href="<?php the_permalink() ?>"><?php esc_html_e('Read More','kurama') ?></a></span>
				        </div>
				    	</div>	
				        <div class="postdate">
			            	<span class="day"><?php the_time('j'); ?></span>
			            	<span class="month"><?php the_time('M'); ?></span>
			            </div>
				    </div>
				    
				<?php $count++;
				if ($count == 4) break;
				 endforeach; ?>
				 <?php wp_reset_postdata(); ?>
	</div>

<?php endif; ?>
</div><!--.container-->
</div>