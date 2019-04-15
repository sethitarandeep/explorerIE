<?php
/**
 * Created by PhpStorm.
 * User: Gourav
 * Date: 2/3/2018
 * Time: 10:39 AM
 */
/**
 * Enqueue scripts and styles.
 */
function kurama_scripts() {
    wp_enqueue_style( 'kurama-style', get_stylesheet_uri() );

    wp_enqueue_style('kurama-title-font', '//fonts.googleapis.com/css?family='.str_replace(" ", "+", esc_html(get_theme_mod('kurama_title_font', 'Oswald')) ).':100,300,400,700' );

    wp_enqueue_style('kurama-body-font', '//fonts.googleapis.com/css?family='.str_replace(" ", "+", esc_html(get_theme_mod('kurama_body_font', 'Open Sans')) ).':100,300,400,700' );

    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css' );

    wp_enqueue_style( 'nivo-slider', get_template_directory_uri() . '/assets/css/nivo-slider.css' );

    wp_enqueue_style( 'nivo-slider-skin', get_template_directory_uri() . '/assets/css/nivo-default/default.css' );

    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css' );

    wp_enqueue_style( 'hover', get_template_directory_uri() . '/assets/css/hover.min.css' );

//    wp_enqueue_style( 'kurama-main-theme-style', get_template_directory_uri() . '/assets/css/main.css',array(),54555 );
    wp_enqueue_style( 'kurama-main-theme-style', get_template_directory_uri() . '/assets/theme-styles/css/'.get_theme_mod('kurama_skin', 'default').'.css' );


    wp_enqueue_script( 'kurama-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

    wp_enqueue_script( 'kurama-externaljs', get_template_directory_uri() . '/js/external.js', array('jquery'), '20120206', true );

    wp_enqueue_script( 'kurama-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    wp_enqueue_script( 'kurama-custom-js', get_template_directory_uri() . '/js/custom.js' );
}
add_action( 'wp_enqueue_scripts', 'kurama_scripts' );