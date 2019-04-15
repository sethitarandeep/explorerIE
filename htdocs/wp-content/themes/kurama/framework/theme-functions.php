<?php
/*
 * @package kurama, Copyright InkHive.com
 * This file contains Custom Theme Related Functions.
 */
//Import Admin Modules
require get_template_directory() . '/framework/admin_modules/register_styles.php';
require get_template_directory() . '/framework/admin_modules/register_widgets.php';
require get_template_directory() . '/framework/admin_modules/theme_setup.php';
require get_template_directory() . '/framework/admin_modules/admin_styles.php';
require get_template_directory() . '/framework/admin_modules/compatibility_function.php';
/*
** Walkers for Navigation menus
*/ 


/*
 * Pagination Function. Implements core paginate_links function.
 */
function kurama_pagination() {
	the_posts_pagination( array( 'mid_size' => 2 ) );
}

/*
** Customizer Controls 
*/
if (class_exists('WP_Customize_Control')) {
	class Kurama_WP_Customize_Category_Control extends WP_Customize_Control {
        /**
         * Render the control's content.
         */
        public function render_content() {
            $dropdown = wp_dropdown_categories(
                array(
                    'name'              => '_customize-dropdown-categories-' . $this->id,
                    'echo'              => 0,
                    'show_option_none'  => __( '&mdash; Select &mdash;', 'kurama' ),
                    'option_none_value' => '0',
                    'selected'          => $this->value(),
                )
            );
 
            $dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );
 
            printf(
                '<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
                $this->label,
                $dropdown
            );
        }
    }
}  

if (class_exists('WP_Customize_Control')) {
	class WP_Customize_Upgrade_Control extends WP_Customize_Control {
        /**
         * Render the control's content.
         */
        public function render_content() {
             printf(
                '<label class="customize-control-upgrade"><span class="customize-control-title">%s</span> %s</label>',
                $this->label,
                $this->description
            );
        }
    }
}
  
/*
** Function to check if Sidebar is enabled on Current Page 
*/

function kurama_load_sidebar() {
	$load_sidebar = true;
	if ( get_theme_mod('kurama_disable_sidebar') ) :
		$load_sidebar = false;
	elseif( get_theme_mod('kurama_disable_sidebar_home') && is_home() )	:
		$load_sidebar = false;
	elseif( get_theme_mod('kurama_disable_sidebar_front') && is_front_page() ) :
		$load_sidebar = false;
	endif;
	
	return  $load_sidebar;
}

/*
**	Determining Sidebar and Primary Width
*/
function kurama_primary_class() {
	$sw = esc_html( get_theme_mod('kurama_sidebar_width',4) );
	$class = "col-md-".(12-$sw);
	
	if ( !kurama_load_sidebar() ) 
		$class = "col-md-12";
	
	echo $class;
}
add_action('kurama_primary-width', 'kurama_primary_class');

function kurama_secondary_class() {
	$sw = esc_html( get_theme_mod('kurama_sidebar_width',4) );
	$class = "col-md-".$sw;
	
	echo $class;
}
add_action('kurama_secondary-width', 'kurama_secondary_class');


/*
**	Helper Function to Convert Colors
*/
function kurama_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);
   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return implode(",", $rgb); // returns the rgb values separated by commas
   //return $rgb; // returns an array with the rgb values
}
function kurama_fade($color, $val) {
	return "rgba(".kurama_hex2rgb($color).",". $val.")";
}


/*
** Function to Get Theme Layout 
*/
function kurama_get_blog_layout(){
	$ldir = 'framework/layouts/content';
	if (get_theme_mod('kurama_blog_layout') ) :
		get_template_part( $ldir , get_theme_mod('kurama_blog_layout') );
	else :
		get_template_part( $ldir ,'grid');	
	endif;	
}
add_action('kurama_blog_layout', 'kurama_get_blog_layout');

function kurama_blog_excerpt_length( $length ) {
	return 30;
}
add_filter('excerpt_length', 'kurama_blog_excerpt_length');

function kurama_blog_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'kurama_blog_excerpt_more');


/**
 *	Setting up a PHP constant for use in WP Forms Lite Plugin
**/


if ( ! defined( 'WPFORMS_SHAREASALE_ID' ) ) {
	define( 'WPFORMS_SHAREASALE_ID', '1802605' );
}


/*
** Load Custom Widgets
*/

require get_template_directory() . '/framework/widgets/recent-posts.php';


