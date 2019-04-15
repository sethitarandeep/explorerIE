<header id="masthead" class="site-header col-md-3 col-sm-5 col-xs-12" role="banner">
    <div class="site-branding">
        <?php if ( function_exists( 'the_custom_logo' ) ) : ?>
            <div class="site-logo">
                <?php the_custom_logo();?>
            </div>
        <?php endif; ?>
        <div id="text-title-desc">
            <h1 class="site-title title-font"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
        </div>
    </div>

    <div id="social-icons">
        <?php get_template_part('modules/social/social', 'fa'); ?>
    </div>

</header><!-- #masthead -->
