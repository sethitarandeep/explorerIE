<?php
/**
 * kurama Theme Customizer
 *
 * @package kurama
 */
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function kurama_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport	= 'postMessage';
   

    $wp_customize->add_section(
        'kurama_design_options',
        array(
            'title'     => __('Blog Layout','kurama'),
            'priority'  => 0,
            'panel'     => 'kurama_design_panel'
        )
    );


    //Featured Image Setting
    $wp_customize->add_section( 'kurama_posts_page' , array(
        'title'      => __( 'Posts Title & Featured Image Settings', 'kurama' ),
        'priority'   => 31,
    ) );


    $wp_customize->add_setting(
        'kurama_featimg',
        array( 
        'default'			=> 'replace',
        'sanitize_callback' => 'kurama_sanitize_featimg_layout' )
    );

    function kurama_sanitize_featimg_layout( $input ) {
        if ( in_array($input, array('replace','noreplace') ) )
            return $input;
        else
            return '';
    }

    $wp_customize->add_control(
        'kurama_featimg',array(
            'label' => __('Header Image on Posts Page.','kurama'),
            'description' => __('If You set Featured Image as the header, then your title will display overlapping the featured Image. Please do not use this layout if you do not plan on using Featured images for your website.','kurama'),
            'settings' => 'kurama_featimg',
            'section'  => 'kurama_posts_page',
            'type' => 'select',
            'choices' => array(
                'replace' => __('Featured Image as Header','kurama'),
                'noreplace' => __('Original Header Image','kurama'),
            )
        )
    );



    //Settings for Nav Area
    $wp_customize->add_setting( 'kurama_disable_active_nav' , array(
        'default'     => true,
        'sanitize_callback' => 'kurama_sanitize_checkbox',
    ) );

    $wp_customize->add_control(
        'kurama_disable_active_nav', array(
        'label' => __('Disable Highlighting of Current Active Item on the Menu.','kurama'),
        'section' => 'nav',
        'settings' => 'kurama_disable_active_nav',
        'type' => 'checkbox'
    ) );

}
add_action( 'customize_register', 'kurama_customize_register' );

//Load All Individual Settings Based on Sections/Panels.
require_once get_template_directory().'/framework/customizer/_header.php';
require_once get_template_directory().'/framework/customizer/_featured_area.php';
require_once get_template_directory().'/framework/customizer/_layout.php';
require_once get_template_directory().'/framework/customizer/skins&colors.php';
require_once get_template_directory().'/framework/customizer/_googlefonts.php';
require_once get_template_directory().'/framework/customizer/_sanitizations.php';
require_once get_template_directory().'/framework/customizer/_social_icons.php';
require_once get_template_directory().'/framework/customizer/_misc_scripts.php';


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function kurama_customize_preview_js() {
    wp_enqueue_script( 'kurama_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'kurama_customize_preview_js' );