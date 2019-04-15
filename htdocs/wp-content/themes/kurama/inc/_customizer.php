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

    //Logo Settings
    $wp_customize->get_section( 'title_tagline' )->title = __( 'Title, Tagline & Logo', 'kurama' );

    $wp_customize->add_setting( 'kurama_logo_resize' , array(
        'default'     => 100,
        'sanitize_callback' => 'kurama_sanitize_positive_number',
    ) );
    $wp_customize->add_control(
        'kurama_logo_resize',
        array(
            'label' => __('Resize & Adjust Logo','kurama'),
            'section' => 'title_tagline',
            'settings' => 'kurama_logo_resize',
            'priority' => 6,
            'type' => 'range',
            'active_callback' => 'kurama_logo_enabled',
            'input_attrs' => array(
                'min'   => 30,
                'max'   => 200,
                'step'  => 5,
            ),
        )
    );

    function kurama_logo_enabled($control) {
        $option = $control->manager->get_setting('custom_logo');
        return $option->value() == true;
    }



    //Replace Header Text Color with, separate colors for Title and Description
    $wp_customize->get_control('header_textcolor')->label = __('Site Title Color','kurama');
    $wp_customize->get_setting('header_textcolor')->default = '#d8a33e';

    $wp_customize->add_setting('kurama_header_desccolor', array(
        'default'     => '#eee',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
            $wp_customize,
            'kurama_header_desccolor', array(
            'label' => __('Site Tagline Color','kurama'),
            'section' => 'colors',
            'settings' => 'kurama_header_desccolor',
            'type' => 'color'
        ) )
    );

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
        array( 'sanitize_callback' => 'kurama_sanitize_featimg_layout' )
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

    //Settings for Header Image
    $wp_customize->add_setting( 'kurama_himg_style' , array(
        'default'     => true,
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
        'default'     => true,
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


    // CREATE THE FCA PANEL
    $wp_customize->add_panel( 'kurama_fca_panel', array(
        'priority'       => 40,
        'capability'     => 'edit_theme_options',
        'theme_supports' => '',
        'title'          => __('Featured Content Areas','kurama'),
        'description'    => '',
    ) );


    //FEATURED AREA 1
    $wp_customize->add_section(
        'kurama_fc_boxes',
        array(
            'title'     => __('Featured Area 1','kurama'),
            'priority'  => 10,
            'panel'     => 'kurama_fca_panel'
        )
    );

    $wp_customize->add_setting(
        'kurama_box_enable',
        array( 'sanitize_callback' => 'kurama_sanitize_checkbox' )
    );

    $wp_customize->add_control(
        'kurama_box_enable', array(
            'settings' => 'kurama_box_enable',
            'label'    => __( 'Enable Featured Area 1.', 'kurama' ),
            'section'  => 'kurama_fc_boxes',
            'type'     => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'kurama_box_cat',
        array( 'sanitize_callback' => 'kurama_sanitize_category' )
    );

    $wp_customize->add_control(
        new Kurama_WP_Customize_Category_Control(
            $wp_customize,
            'kurama_box_cat',
            array(
                'label'    => __('Category For Square Boxes.','kurama'),
                'settings' => 'kurama_box_cat',
                'section'  => 'kurama_fc_boxes'
            )
        )
    );


    // Layout and Design
    $wp_customize->add_panel( 'kurama_design_panel', array(
        'priority'       => 40,
        'capability'     => 'edit_theme_options',
        'theme_supports' => '',
        'title'          => __('Design & Layout','kurama'),
    ) );

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
        array( 'sanitize_callback' => 'kurama_sanitize_blog_layout' )
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
        array( 'sanitize_callback' => 'kurama_sanitize_checkbox' )
    );

    $wp_customize->add_control(
        'kurama_disable_sidebar', array(
            'settings' => 'kurama_disable_sidebar',
            'label'    => __( 'Disable Sidebar Everywhere.','kurama' ),
            'section'  => 'kurama_sidebar_options',
            'type'     => 'checkbox',
            'default'  => false
        )
    );

    $wp_customize->add_setting(
        'kurama_disable_sidebar_home',
        array( 'sanitize_callback' => 'kurama_sanitize_checkbox' )
    );

    $wp_customize->add_control(
        'kurama_disable_sidebar_home', array(
            'settings' => 'kurama_disable_sidebar_home',
            'label'    => __( 'Disable Sidebar on Home/Blog.','kurama' ),
            'section'  => 'kurama_sidebar_options',
            'type'     => 'checkbox',
            'active_callback' => 'kurama_show_sidebar_options',
            'default'  => false
        )
    );

    $wp_customize->add_setting(
        'kurama_disable_sidebar_front',
        array( 'sanitize_callback' => 'kurama_sanitize_checkbox' )
    );

    $wp_customize->add_control(
        'kurama_disable_sidebar_front', array(
            'settings' => 'kurama_disable_sidebar_front',
            'label'    => __( 'Disable Sidebar on Front Page.','kurama' ),
            'section'  => 'kurama_sidebar_options',
            'type'     => 'checkbox',
            'active_callback' => 'kurama_show_sidebar_options',
            'default'  => false
        )
    );


    $wp_customize->add_setting(
        'kurama_sidebar_width',
        array(
            'default' => 4,
            'sanitize_callback' => 'kurama_sanitize_positive_number' )
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
        'kurama_typo_options',
        array(
            'title'     => __('Google Web Fonts','kurama'),
            'priority'  => 41,
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

    $wp_customize->add_section(
        'kurama_sec_pro',
        array(
            'title'     => __('-> Upgrade to Kurama Plus','kurama'),
            'priority'  => 44,
        )
    );

    $wp_customize->add_setting(
        'kurama_upgrade_pro',
        array( 'sanitize_callback' => 'esc_textarea' )
    );

    $wp_customize->add_control(
        new WP_Customize_Upgrade_Control(
            $wp_customize,
            'kurama_upgrade_pro',
            array(
                'label' => __('Get More Features right now!','kurama'),
                'description' => __('<br /> We hope you are enjoying the free version of this theme. The Free version of Kurama itself is a very powerful theme. But for those who want more we have created a Kurama Premium Version. Some of the exciting Features of Kurama Plus include <br /><br />- Better Mobile Friendliness <br />- Unlimited Colors & Skins <br />- Many More Featured Areas <br />- Advanced Slider  <br />- 600+ Custom Fonts <br />- More Blog/Page Layouts <br />- Adsense Support  <br />- And Much More <br /><br /> To Purchase & Know more visit  <a href="https://inkhive.com/product/kurama-plus/">Kurama Plus</a>.','kurama'),
                'section' => 'kurama_sec_pro',
                'settings' => 'kurama_upgrade_pro',
            )
        )
    );

    // Social Icons
    $wp_customize->add_section('kurama_social_section', array(
        'title' => __('Social Icons','kurama'),
        'priority' => 44 ,
    ));

    $social_networks = array( //Redefinied in Sanitization Function.
        'none' => __('-','kurama'),
        'facebook' => __('Facebook','kurama'),
        'twitter' => __('Twitter','kurama'),
        'google-plus' => __('Google Plus','kurama'),
        'instagram' => __('Instagram','kurama'),
        'rss' => __('RSS Feeds','kurama'),
        'vine' => __('Vine','kurama'),
        'vimeo-square' => __('Vimeo','kurama'),
        'youtube' => __('Youtube','kurama'),
        'flickr' => __('Flickr','kurama'),
    );

    $social_count = count($social_networks);

    for ($x = 1 ; $x <= ($social_count - 3) ; $x++) :

        $wp_customize->add_setting(
            'kurama_social_'.$x, array(
            'sanitize_callback' => 'kurama_sanitize_social',
            'default' => 'none'
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
            'facebook',
            'twitter',
            'google-plus',
            'instagram',
            'rss',
            'vine',
            'vimeo-square',
            'youtube',
            'flickr'
        );
        if ( in_array($input, $social_networks) )
            return $input;
        else
            return '';
    }


    /* Sanitization Functions Common to Multiple Settings go Here, Specific Sanitization Functions are defined along with add_setting() */
    function kurama_sanitize_checkbox( $input ) {
        if ( $input == 1 ) {
            return 1;
        } else {
            return '';
        }
    }

    function kurama_sanitize_positive_number( $input ) {
        if ( ($input >= 0) && is_numeric($input) )
            return $input;
        else
            return '';
    }

    function kurama_sanitize_category( $input ) {
        if ( term_exists(get_cat_name( $input ), 'category') )
            return $input;
        else
            return '';
    }


}
add_action( 'customize_register', 'kurama_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function kurama_customize_preview_js() {
    wp_enqueue_script( 'kurama_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'kurama_customize_preview_js' );
