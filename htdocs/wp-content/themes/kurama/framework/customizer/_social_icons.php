<?php
/**
 * Created by PhpStorm.
 */
function kurama_customize_register_social( $wp_customize ) {
// Social Icons
$wp_customize->add_section('kurama_social_section', array(
    'title' => __('Social Icons','kurama'),
    'priority' => 44 ,
    'panel'   => 'kurama_header_panel'
));


    //social icons style
    $social_style = array(
        'none'  => __('Default', 'kurama'),
        'style1'   => __('Style 1', 'kurama'),
        'style2'   => __('Style 2', 'kurama'),
        'hvr-shutter-out-horizontal'   => __('Style 3', 'kurama'),
    );
    $wp_customize->add_setting(
        'kurama_social_icon_style_set', array(
        'sanitize_callback' => 'kurama_sanitize_social_style',
        'default' => 'none'
    ));


    function kurama_sanitize_social_style( $input ) {
        if ( in_array($input, array('none','style1','style2','hvr-shutter-out-horizontal') ) )
            return $input;
        else
            return '';
    }




    $wp_customize->add_control( 'kurama_social_icon_style_set', array(
        'settings' => 'kurama_social_icon_style_set',
        'label' => __('Social Icon Style ','kurama'),
        'description' => __('You can choose your icon style','kurama'),
        'section' => 'kurama_social_section',
        'type' => 'select',
        'choices' => $social_style,
    ));


    $social_networks = array( //Redefinied in Sanitization Function.
    'none' => __('-','kurama'),
    'facebook-f' => __('Facebook','kurama'),
    'twitter' => __('Twitter','kurama'),
    'google-plus' => __('Google Plus','kurama'),
    'instagram' => __('Instagram','kurama'),
    'rss' => __('RSS Feeds','kurama'),
    'vimeo-square' => __('Vimeo','kurama'),
    'youtube' => __('Youtube','kurama'),
    'flickr' => __('Flickr','kurama'),
);

$social_count = count($social_networks);

for ($x = 1 ; $x <= ($social_count - 2) ; $x++) :

    $wp_customize->add_setting(
        'kurama_social_'.$x, array(
        'sanitize_callback' => 'kurama_sanitize_social',
        'default' => 'none',
        'transport'	=> 'postMessage'
    ));

    $wp_customize->add_control( 'kurama_social_'.$x, array(
        'settings' => 'kurama_social_'.$x,
        'label' => __('Icon ','kurama').$x,
        'section' => 'kurama_social_section',
        'type' => 'select',
        'choices' => $social_networks,
    ));

    $wp_customize->add_setting(
        'kurama_social_url'.$x, array(
        'sanitize_callback' => 'esc_url_raw'
    ));

    $wp_customize->add_control( 'kurama_social_url'.$x, array(
        'settings' => 'kurama_social_url'.$x,
        'description' => __('Icon ','kurama').$x.__(' Url','kurama'),
        'section' => 'kurama_social_section',
        'type' => 'url',
        'choices' => $social_networks,
    ));

endfor;

function kurama_sanitize_social( $input ) {
    $social_networks = array(
        'none' ,
        'facebook-f',
        'twitter',
        'google-plus-g',
        'instagram',
        'rss',
        'vimeo-v',
        'youtube',
        'flickr'
    );
    if ( in_array($input, $social_networks) )
        return $input;
    else
        return '';
}
}
add_action( 'customize_register', 'kurama_customize_register_social' );