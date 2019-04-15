<?php
/**
 * Created by PhpStorm.
 * User: Gourav
 * Date: 2/3/2018
 * Time: 11:35 AM
 */
function kurama_customize_register_skins( $wp_customize )
{
//Replace Header Text Color with, separate colors for Title and Description
    $wp_customize->get_control('header_textcolor')->label = __('Site Title Color', 'kurama');
    $wp_customize->remove_section('colors');
    //Select the Default Theme Skin
    $wp_customize->add_section(
        'kurama_skin_options',
        array(
            'title'     => __('Theme Skin & Colors','kurama'),
            'priority'  => 39,
            'panel'     => 'kurama_design_panel'
        )
    );
    //colors
    $wp_customize->add_setting('kurama_site_titlecolor', array(
        'default'     => '#d8a33e',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
            $wp_customize,
            'kurama_site_titlecolor', array(
            'label' => __('Site Title Color','kurama'),
            'section' => 'kurama_skin_options',
            'settings' => 'kurama_site_titlecolor',
            'type' => 'color'
        ) )
    );

    $wp_customize->add_setting('kurama_header_desccolor', array(
        'default'     => '#d8a33e',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
            $wp_customize,
            'kurama_header_desccolor', array(
            'label' => __('Site Tagline Color','kurama'),
            'section' => 'kurama_skin_options',
            'settings' => 'kurama_header_desccolor',
            'type' => 'color'
        ) )
    );
    //skins

    $wp_customize->add_setting('kurama_skin',
        array(
            'default'=> 'default',
            'sanitize_callback' => 'kurama_sanitize_skin'
        )
    );

    $skins = array( 'default' => __('Default (Maroon)','kurama'),
        'raisinblack' =>  __('Raisin Black','kurama'),
        'charcoal' => __('Charcoal','kurama'),
        'green'   => __('Green','kurama'));


    $wp_customize->add_control(
        'kurama_skin',array(
            'settings' => 'kurama_skin',
            'section'  => 'kurama_skin_options',
            'type' => 'select',
            'choices' => $skins,
        )
    );

    function kurama_sanitize_skin( $input ) {
        if ( in_array($input, array('default','raisinblack','charcoal','grayscale','green') ) )
            return $input;
        else
            return '';
    }
}
add_action( 'customize_register', 'kurama_customize_register_skins' );