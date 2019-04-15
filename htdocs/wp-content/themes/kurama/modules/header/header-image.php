<div id="header-image" class="col-md-9 col-sm-7 col-xs-12">
    <?php if ( is_single() && has_post_thumbnail() && (get_theme_mod('kurama_featimg','replace') == 'replace' ) ) : ?>
        <h1 class="entry-title"><?php the_title(); ?></h1>
    <?php endif; ?>
</div>
