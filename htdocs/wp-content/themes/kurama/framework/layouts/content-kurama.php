<?php
/**
 * @package Kurama
 */
 ?>


<article id="post-<?php the_ID(); ?>" <?php post_class('kurama col-md-6'); ?>>

			<?php if (has_post_thumbnail()) : ?>	
				<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"><?php the_post_thumbnail('kurama-pop-thumb-half',array(  'alt' => trim(strip_tags( $post->post_title )))); ?></a>
			<?php else: ?>
				<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"><img alt="<?php the_title()?>" src="<?php echo get_template_directory_uri()."/assets/images/placeholder3.jpg"; ?>"></a>
			<?php endif; ?>
            
            
            <div class="out-thumb">
            	<div class="post-meta col-md-3 col-lg-2">
            		<div class="postdate">
		            	<span class="month"><?php the_time('j M, Y'); ?></span>
		            </div>
		            <div class="author">
		            	<i class="fa fa-user"></i> <?php the_author_posts_link(); ?>
		            </div>
            	</div>
				<header class="entry-header col-md-9 col-lg-10">
					<h3 class="entry-title title-font"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
					<span class="readmore"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php esc_html_e('Read More','kurama'); ?></a></span>

				</header><!-- .entry-header -->
			</div><!--.out-thumb-->
            
			
</article><!-- #post-## -->