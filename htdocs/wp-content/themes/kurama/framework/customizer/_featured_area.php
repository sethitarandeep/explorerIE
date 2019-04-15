<?php
/**
 * Created by PhpStorm.
 * User: Gourav
 * Date: 2/3/2018
 * Time: 11:02 AM
 */

function kurama_customize_featured_posts( $wp_customize )
{
// CREATE THE FCA PANEL
    $wp_customize->add_panel('kurama_fca_panel', array(
        'priority' => 40,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => __('Featured Content Areas', 'kurama'),
        'description' => '',
    ));


//FEATURED AREA 1
    $wp_customize->add_section(
        'kurama_fc_boxes',
        array(
            'title' => __('Featured Area 1', 'kurama'),
            'priority' => 10,
            'panel' => 'kurama_fca_panel'
        )
    );

    $wp_customize->add_setting(
        'kurama_box_enable',
        array('sanitize_callback' => 'kurama_sanitize_checkbox')
    );

    $wp_customize->add_control(
        'kurama_box_enable', array(
            'settings' => 'kurama_box_enable',
            'label' => __('Enable Featured Area 1.', 'kurama'),
            'section' => 'kurama_fc_boxes',
            'type' => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'kurama_box_cat',
        array('sanitize_callback' => 'kurama_sanitize_category')
    );

    $wp_customize->add_control(
        new Kurama_WP_Customize_Category_Control(
            $wp_customize,
            'kurama_box_cat',
            array(
                'label' => __('Category For Square Boxes.', 'kurama'),
                'settings' => 'kurama_box_cat',
                'section' => 'kurama_fc_boxes'
            )
        )
    );
    
    $wp_customize->add_section(
	    'kurama_pro_fa',
	    array(
		    'title'	=> __('More Featured Areas in Kurama Plus', 'kurama'),
		    'panel'	=> 'kurama_fca_panel',
		    'priority'	=> 15
	    )
    );
    
    $wp_customize->add_control(
	    'kurama_pro_control_fa',
	    array(
		    'label'		=> __('You shouldn\'t be here', 'kurama'),
		    'settings'	=> array(),
		    'section'	=> 'kurama_pro_fa'
	    )
    );

}
add_action( 'customize_register', 'kurama_customize_featured_posts' );