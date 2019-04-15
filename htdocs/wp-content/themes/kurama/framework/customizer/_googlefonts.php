<?php
/**
 * Created by PhpStorm.
 * User: Gourav
 * Date: 2/3/2018
 * Time: 11:33 AM
 */

function kurama_customize_register_fonts( $wp_customize ) {
$wp_customize->add_section(
    'kurama_typo_options',
    array(
        'title'     => __('Google Web Fonts','kurama'),
        'priority'  => 28,
        'panel'     => 'kurama_design_panel'
    )
);

$font_array = array('Lato','Khula','Open Sans','Droid Sans','Droid Serif','Roboto Condensed','Bree Serif','Oswald','Slabo','Lora');
$fonts = array_combine($font_array, $font_array);

$wp_customize->add_setting(
    'kurama_title_font',
    array(
        'default'=> 'Oswald',
        'sanitize_callback' => 'kurama_sanitize_gfont'
    )
);

function kurama_sanitize_gfont( $input ) {
    if ( in_array($input, array('Lato','Khula','Open Sans','Droid Sans','Droid Serif','Roboto Condensed','Bree Serif','Oswald','Slabo','Lora') ) )
        return $input;
    else
        return '';
}

$wp_customize->add_control(
    'kurama_title_font',array(
        'label' => __('Title, Headings, Menu, etc.','kurama'),
        'settings' => 'kurama_title_font',
        'section'  => 'kurama_typo_options',
        'type' => 'select',
        'choices' => $fonts,
    )
);

$wp_customize->add_setting(
    'kurama_body_font',
    array(	'default'=> 'Open Sans',
        'sanitize_callback' => 'kurama_sanitize_gfont' )
);

$wp_customize->add_control(
    'kurama_body_font',array(
        'label' => __('Rest of the Body','kurama'),
        'settings' => 'kurama_body_font',
        'section'  => 'kurama_typo_options',
        'type' => 'select',
        'choices' => $fonts
    )
);

}
add_action( 'customize_register', 'kurama_customize_register_fonts' );