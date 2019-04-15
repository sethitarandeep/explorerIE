<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package kurama
 */

if ( ! is_active_sidebar( 'kurama-sidebar-1' ) ) {
	return;
}

if ( kurama_load_sidebar() ) : ?>
<div id="secondary" class="widget-area <?php do_action('kurama_secondary-width') ?>" role="complementary">
	<?php dynamic_sidebar( 'kurama-sidebar-1' ); ?>
</div><!-- #secondary -->
<?php endif; ?>
