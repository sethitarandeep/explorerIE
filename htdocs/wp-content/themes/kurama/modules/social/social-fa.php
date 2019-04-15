<?php
/*
** Template to Render Social Icons on Top Bar
*/
$social_style = get_theme_mod('kurama_social_icon_style_set' ,'none');

for ($i = 1; $i <= 7; $i++) :
	$social = get_theme_mod('kurama_social_'.$i);
	if ( ($social != 'none') && ($social != '') ) : ?>
	<span><a class="<?php echo esc_attr( $social_style ); ?>" href="<?php echo esc_url( get_theme_mod('kurama_social_url'.$i) ); ?>"><i class="fa fa-fw fa-<?php echo esc_attr($social); ?>"></i></a></span>
	<?php endif;
endfor; ?>