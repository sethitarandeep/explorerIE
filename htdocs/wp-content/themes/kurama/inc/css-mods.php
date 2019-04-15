<?php
/* 
**   Custom Modifcations in CSS depending on user settings.
*/

function kurama_custom_css_mods() {
	
	$custom_css = "";
	
	//If Highlighting Nav active item is disabled
	if ( get_theme_mod('kurama_disable_active_nav') ) :
		$custom_css .= "#site-navigation ul .current_page_item > a, #site-navigation ul .current-menu-item > a, #site-navigation ul .current_page_ancestor > a { border:none; background: inherit; }"; 
	endif;
	
	if ( get_theme_mod('kurama_title_font') ) :
		$custom_css .= ".title-font, h1, h2, .section-title { font-family: ".esc_html(get_theme_mod('kurama_title_font','Oswald'))."; }";
	endif;
	
	if ( get_theme_mod('kurama_body_font') ) :
		$custom_css .= "body, #masthead h2 { font-family: ".esc_html(get_theme_mod('kurama_body_font','Open Sans'))."; }";
	endif;
	if ( get_theme_mod('kurama_site_titlecolor','#d8a33e') ) :
		$custom_css .= "#masthead h1.site-title a { color: ".esc_html( get_theme_mod('kurama_site_titlecolor', '#d8a33e') )."; }";
	endif;


	if ( get_theme_mod('kurama_header_desccolor','#d8a33e') ) :
		$custom_css .=  "#masthead h2.site-description { color: ".esc_html( get_theme_mod('kurama_header_desccolor','#d8a33e') )."; }";
	endif;
	
	if ( !display_header_text() ) :
		$custom_css .= "#masthead .site-branding #text-title-desc { display: none; }";
	endif;
		
	wp_add_inline_style('kurama-main-theme-style', $custom_css)	;

}

add_action('wp_enqueue_scripts', 'kurama_custom_css_mods');