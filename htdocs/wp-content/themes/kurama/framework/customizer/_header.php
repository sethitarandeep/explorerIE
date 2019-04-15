<?php
/**
 * Created by PhpStorm.
 * User: Gourav
 * Date: 2/3/2018
 * Time: 10:47 AM
 */
function kurama_customize_register_header( $wp_customize ) {
    $wp_customize->get_section( 'header_image' )->panel =  'kurama_header_panel';

    $wp_customize->add_panel('kurama_header_panel', array(
        'priority' => 2,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => __('Header Settings', 'kurama'),
    ));
    //Logo Settings
    $wp_customize->get_section( 'title_tagline' )->title = __( 'Title, Tagline & Logo', 'kurama' );
    $wp_customize->get_section( 'title_tagline' )->panel =  'kurama_header_panel';


//    $wp_customize->add_setting( 'kurama_logo_resize' , array(
//        'default'     => 100,
//        'sanitize_callback' => 'kurama_sanitize_positive_number',
//    ) );
//    $wp_customize->add_control(
//        'kurama_logo_resize',
//        array(
//            'label' => __('Resize & Adjust Logo','kurama'),
//            'section' => 'title_tagline',
//            'settings' => 'kurama_logo_resize',
//            'priority' => 6,
//            'type' => 'range',
//            'active_callback' => 'kurama_logo_enabled',
//            'input_attrs' => array(
//                'min'   => 30,
//                'max'   => 200,
//                'step'  => 5,
//            ),
//        )
//    );
//
//    function kurama_logo_enabled($control) {
//        $option = $control->manager->get_setting('custom_logo');
//        return $option->value() == true;
//    }
    //Settings for Header Image
    $wp_customize->add_setting( 'kurama_himg_style' , array(
        'default'     => 'cover',
        'sanitize_callback' => 'kurama_sanitize_himg_style'
    ) );

    /* Sanitization Function */
    function kurama_sanitize_himg_style( $input ) {
        if (in_array( $input, array('contain','cover') ) )
            return $input;
        else
            return '';
    }

    $wp_customize->add_control(
        'kurama_himg_style', array(
        'label' => __('Header Image Arrangement','kurama'),
        'section' => 'header_image',
        'settings' => 'kurama_himg_style',
        'type' => 'select',
        'choices' => array(
            'contain' => __('Contain','kurama'),
            'cover' => __('Cover Completely','kurama'),
        )
    ) );

    $wp_customize->add_setting( 'kurama_himg_align' , array(
        'default'     => 'center',
        'sanitize_callback' => 'kurama_sanitize_himg_align'
    ) );

    /* Sanitization Function */
    function kurama_sanitize_himg_align( $input ) {
        if (in_array( $input, array('center','left','right') ) )
            return $input;
        else
            return '';
    }

    $wp_customize->add_control(
        'kurama_himg_align', array(
        'label' => __('Header Image Alignment','kurama'),
        'section' => 'header_image',
        'settings' => 'kurama_himg_align',
        'type' => 'select',
        'choices' => array(
            'center' => __('Center','kurama'),
            'left' => __('Left','kurama'),
            'right' => __('Right','kurama'),
        )

    ) );

    $wp_customize->add_setting( 'kurama_himg_repeat' , array(
        'default'     => true,
        'sanitize_callback' => 'kurama_sanitize_checkbox'
    ) );

    $wp_customize->add_control(
        'kurama_himg_repeat', array(
        'label' => __('Repeat Header Image','kurama'),
        'section' => 'header_image',
        'settings' => 'kurama_himg_repeat',
        'type' => 'checkbox',
    ) );
    
    $wp_customize->add_section(
	    'kurama_pro_header',
	    array(
		    'title'	=> __('More Header Options in Kurama Plus', 'kurama'),
		    'panel'	=> 'kurama_header_panel',
		    'priority'	=> 100
	    )
    );
    
    $wp_customize->add_control(
	    'kurama_pro_control_header',
	    array(
		    'label'		=> __('You shouldn\'t be here', 'kurama'),
		    'settings'	=> array(),
		    'section'	=> 'kurama_pro_header'
	    )
    );

}
add_action( 'customize_register', 'kurama_customize_register_header' );