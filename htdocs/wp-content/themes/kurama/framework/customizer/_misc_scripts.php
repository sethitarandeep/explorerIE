<?php
/**
 * Created by PhpStorm.
 * User: Gourav
 * Date: 2/3/2018
 * Time: 11:01 AM
 */
function kurama_customize_register_misc( $wp_customize )
{

    $wp_customize->add_section(
        'kurama_sec_pro',
        array(
            'title' => __('Upgrade to Kurama Plus', 'kurama'),
            'priority' => 1,
        )
    );

    $wp_customize->add_setting(
        'kurama_upgrade_pro',
        array('sanitize_callback' => 'esc_textarea')
    );

    $wp_customize->add_control(
        new WP_Customize_Upgrade_Control(
            $wp_customize,
            'kurama_upgrade_pro',
            array(
                'label' => __('Get More Features right now!', 'kurama'),
                'description' => __('<br /> We hope you are enjoying the free version of this theme. The Free version of Kurama itself is a very powerful theme. But for those who want more we have created a Kurama Premium Version. Some of the exciting Features of Kurama Plus include <br /><br />- Better Mobile Friendliness <br />- Unlimited Colors & Skins <br />- Many More Featured Areas <br />- Advanced Slider  <br />- 600+ Custom Fonts <br />- More Blog/Page Layouts <br />- Adsense Support  <br />- And Much More <br /><br /> To Purchase & Know more visit  <a href="https://inkhive.com/product/kurama-plus/">Kurama Plus</a>.', 'kurama'),
                'section' => 'kurama_sec_pro',
                'settings' => 'kurama_upgrade_pro',
            )
        )
    );
}
add_action( 'customize_register', 'kurama_customize_register_misc' );