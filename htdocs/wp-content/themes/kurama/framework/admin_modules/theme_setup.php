<?php
/**
 * Created by PhpStorm.
 * User: Gourav
 * Date: 2/3/2018
 * Time: 10:38 AM
 */
/**
 * kurama functions and definitions
 *
 * @package kurama
 */

if ( ! function_exists( 'kurama_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function kurama_setup() {

        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on kurama, use a find and replace
         * to change 'kurama' to the name of your theme in all the template files
         */
        load_theme_textdomain( 'kurama', get_template_directory() . '/languages' );

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        //Gutenberg fullscreen content
        add_theme_support('align-wide');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         *
         */
        add_theme_support( 'title-tag' );

        /**
         * Set the content width based on the theme's design and stylesheet.
         */
        global $content_width;
        if ( ! isset( $content_width ) ) {
            $content_width = 740; /* pixels */
        }


        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support( 'post-thumbnails' );

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'kurama' ),
            'bottom' => __( 'Bottom Menu', 'kurama' ),
        ) );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support( 'html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
        ) );

        add_theme_support( 'custom-logo');

        add_image_size('kurama-pop-thumb',542, 340, true );
        add_image_size('kurama-pop-thumb-half',592, 225, true );
        add_image_size('kurama-poster-thumb',542, 680, true );




        // Set up the WordPress core custom background feature.
        add_theme_support( 'custom-background', apply_filters( 'kurama_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );
    }
endif; // kurama_setup
add_action( 'after_setup_theme', 'kurama_setup' );