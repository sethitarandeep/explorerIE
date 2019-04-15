<?php
/**
 * Created by PhpStorm.
 * User: Gourav
 * Date: 2/3/2018
 * Time: 10:38 AM
 */
/**
 * Enqueue Scripts for Admin
 */
function kurama_custom_wp_admin_style() {
	
    wp_enqueue_style( 'kurama-admin_css', get_template_directory_uri() . '/assets/css/admin.css' );
    
}
add_action( 'admin_enqueue_scripts', 'kurama_custom_wp_admin_style' );
