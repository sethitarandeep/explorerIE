<?php
/**
 * Created by PhpStorm.
 * User: Gourav
 * Date: 2/3/2018
 * Time: 10:52 AM
 */

function kurama_customize_register_layouts( $wp_customize ) {
// Layout and Design
$wp_customize->add_panel( 'kurama_design_panel', array(
    'priority'       => 3,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __('Design & Layout','kurama'),
) );
    $wp_customize->get_section('background_image')->panel = 'kurama_design_panel';


    $wp_customize->add_section(
    'kurama_design_options',
    array(
        'title'     => __('Blog Layout','kurama'),
        'priority'  => 0,
        'panel'     => 'kurama_design_panel'
    )
);


$wp_customize->add_setting(
    'kurama_blog_layout',
    array( 
    'default'	=> 'kurama',
    'sanitize_callback' => 'kurama_sanitize_blog_layout' )
);

function kurama_sanitize_blog_layout( $input ) {
    if ( in_array($input, array('grid','grid_2_column','grid_3_column','kurama') ) )
        return $input;
    else
        return '';
}

$wp_customize->add_control(
    'kurama_blog_layout',array(
        'label' => __('Select Layout','kurama'),
        'settings' => 'kurama_blog_layout',
        'section'  => 'kurama_design_options',
        'type' => 'select',
        'choices' => array(
            'grid' => __('Basic Blog Layout','kurama'),
            'kurama' => __('Kurama Default Layout','kurama'),
            'grid_2_column' => __('Grid - 2 Column','kurama'),
            'grid_3_column' => __('Grid - 3 Column','kurama'),
        )
    )
);

$wp_customize->add_section(
    'kurama_sidebar_options',
    array(
        'title'     => __('Sidebar Layout','kurama'),
        'priority'  => 0,
        'panel'     => 'kurama_design_panel'
    )
);

$wp_customize->add_setting(
    'kurama_disable_sidebar',
    array( 
    'default'	=> '',
    'sanitize_callback' => 'kurama_sanitize_checkbox' )
);

$wp_customize->add_control(
    'kurama_disable_sidebar', array(
        'settings' => 'kurama_disable_sidebar',
        'label'    => __( 'Disable Sidebar Everywhere.','kurama' ),
        'section'  => 'kurama_sidebar_options',
        'type'     => 'checkbox',
    )
);

$wp_customize->add_setting(
    'kurama_disable_sidebar_home',
    array( 
    'default'	=> '',
    'sanitize_callback' => 'kurama_sanitize_checkbox' )
);

$wp_customize->add_control(
    'kurama_disable_sidebar_home', array(
        'settings' => 'kurama_disable_sidebar_home',
        'label'    => __( 'Disable Sidebar on Home/Blog.','kurama' ),
        'section'  => 'kurama_sidebar_options',
        'type'     => 'checkbox',
        'active_callback' => 'kurama_show_sidebar_options'
    )
);

$wp_customize->add_setting(
    'kurama_disable_sidebar_front',
    array( 
    'default'	=> '',
    'sanitize_callback' => 'kurama_sanitize_checkbox' )
);

$wp_customize->add_control(
    'kurama_disable_sidebar_front', array(
        'settings' => 'kurama_disable_sidebar_front',
        'label'    => __( 'Disable Sidebar on Front Page.','kurama' ),
        'section'  => 'kurama_sidebar_options',
        'type'     => 'checkbox',
        'active_callback' => 'kurama_show_sidebar_options'
    )
);


$wp_customize->add_setting(
    'kurama_sidebar_width',
    array(
        'default' => 4,
        'sanitize_callback' => 'kurama_sanitize_positive_number',
        'transport'	=> 'postMessage'
         )
);

$wp_customize->add_control(
    'kurama_sidebar_width', array(
        'settings' => 'kurama_sidebar_width',
        'label'    => __( 'Sidebar Width','kurama' ),
        'description' => __('Min: 25%, Default: 33%, Max: 40%','kurama'),
        'section'  => 'kurama_sidebar_options',
        'type'     => 'range',
        'active_callback' => 'kurama_show_sidebar_options',
        'input_attrs' => array(
            'min'   => 3,
            'max'   => 5,
            'step'  => 1,
            'class' => 'sidebar-width-range',
            'style' => 'color: #0a0',
        ),
    )
);

/* Active Callback Function */
function kurama_show_sidebar_options($control) {

    $option = $control->manager->get_setting('kurama_disable_sidebar');
    return $option->value() == false ;

}

function kurama_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

$wp_customize-> add_section(
    'kurama_custom_footer',
    array(
        'title'			=> __('Custom Footer Text','kurama'),
        'description'	=> __('Enter your Own Copyright Text.','kurama'),
        'priority'		=> 11,
        'panel'			=> 'kurama_design_panel'
    )
);

$wp_customize->add_setting(
    'kurama_footer_text',
    array(
        'default'		=> '',
        'sanitize_callback'	=> 'sanitize_text_field'
    )
);

$wp_customize->add_control(
    'kurama_footer_text',
    array(
        'section' => 'kurama_custom_footer',
        'settings' => 'kurama_footer_text',
        'type' => 'text'
    )
);

$wp_customize->add_section(
	    'kurama_pro_layout',
	    array(
		    'title'	=> __('More Layout Options in Kurama Plus', 'kurama'),
		    'panel'	=> 'kurama_design_panel',
		    'priority'	=> 100
	    )
    );
    
    $wp_customize->add_control(
	    'kurama_pro_control_layout',
	    array(
		    'label'		=> __('You shouldn\'t be here', 'kurama'),
		    'settings'	=> array(),
		    'section'	=> 'kurama_pro_layout'
	    )
    );
}
add_action( 'customize_register', 'kurama_customize_register_layouts' );
