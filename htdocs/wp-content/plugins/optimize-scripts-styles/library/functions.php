<?php

/* The following functions were created to speed up the site. W3 Total Cache minification
   does not work, so this is an alternative. The W3 empty cache function will empty this
   cache as well. It has since been upgraded to work with WP Super Cache.
 */

 /* INCLUDES */
$path = dirname(__FILE__);
require_once( $path . '/includes/minify/src/Minify.php' );
require_once( $path . '/includes/minify/src/CSS.php' );
require_once( $path . '/includes/minify/src/JS.php' );
require_once( $path . '/includes/minify/src/Exception.php' );
require_once( $path . '/includes/minify/converter/src/Converter.php' );

use MatthiasMullie\Minify;

/* SETTINGS */
global $spos_settings;
$remove_scripts = isset( $spos_settings['remove_scripts'] ) && $spos_settings['remove_scripts'] !== '' ? true : false;
$remove_styles = isset( $spos_settings['remove_styles'] ) && $spos_settings['remove_styles'] !== '' ? true : false;
$optimize_scripts = isset( $spos_settings['optimize_scripts'] ) && $spos_settings['optimize_scripts'] == 1 ? true : false;
$optimize_styles = isset( $spos_settings['optimize_styles'] ) && $spos_settings['optimize_styles'] == 1 ? true : false;
$remove_script_type = isset( $spos_settings['remove_script_type'] ) && $spos_settings['remove_script_type'] == 1 ? true : false;
$remove_style_type = isset( $spos_settings['remove_style_type'] ) && $spos_settings['remove_style_type'] == 1 ? true : false;

/* ACTIONS & FILTERS */
if ( $remove_scripts ) {
	add_action( 'wp_print_scripts', 'spos_dequeue_scripts', 98 );
	add_action( 'wp_footer', 'spos_dequeue_scripts', 18 ); // some additional scripts may be added after wp_print_scripts
}

if ( $remove_styles ) {
	add_action( 'wp_print_styles', 'spos_dequeue_styles', 98 );
}

if ( $optimize_scripts ) {
	add_action( 'wp_print_scripts', 'spos_minify_head_scripts', 99 ); // header scripts are all enqueued here
	add_action( 'wp_footer', 'spos_minify_footer_scripts', 19 ); // enqueued scripts are executed at 20
}

if ( $optimize_styles ) {
	add_action( 'wp_print_styles', 'spos_minify_styles', 99 );
}

if ( $remove_script_type ) {
	// remove type='text/javascript' in localized data
	if ( !$optimize_scripts ) {
		// only do this if scripts aren't being optimized already since data is pulled out via
		// the optimization function anyway
		add_action( 'wp_loaded', function() {
			// this adds lots of extra junk... not an ideal solution, but there are a lack of
			// hooks to accomplish this
			global $wp_scripts;
			$wp_scripts = new SPOS_Scripts;
		});
	}

	// remove the type='text/javascript' tags that are added by WordPress
	add_filter( 'script_loader_tag', 'spos_remove_tag_type', 10, 2 );	
}

if ( $remove_style_type ) {
	// remove the type='text/css' tags that are added by WordPress
	add_filter( 'style_loader_tag', 'spos_remove_tag_type', 10, 2 );	
}

/**************************
  REMOVE SCRIPT TYPE
**************************/

// extend WP_Scripts - only used if $optimize_scripts == false and $remove_script_type == true
class SPOS_Scripts extends WP_Scripts {

	// override print_extra_script from class.wp-scripts.php line 198
	public function print_extra_script( $handle, $echo = true ) {
		if ( !$output = $this->get_data( $handle, 'data' ) )
			return;

		if ( !$echo )
			return $output;

		echo "<script>\n"; // CDATA and type='text/javascript' is not needed for HTML 5 - REMOVED!
		echo "/* <![CDATA[ */\n";
		echo "$output\n";
		echo "/* ]]> */\n";
		echo "</script>\n";

		return true;
	}
}

function spos_remove_tag_type( $tag, $handle ) {
	return preg_replace( "/\stype=['\"]text\/(javascript|css)['\"]/", '', $tag );
}
   
/**************************
  REMOVE SCRIPTS
**************************/
function spos_dequeue_scripts() {
	global $spos_settings;
	$remove_setting = sanitize_text_field( $spos_settings['remove_scripts'] );
	if ( $remove_setting !== '' ) {
		// separate into an array
		$remove_arr = explode( ',', $remove_setting );
		foreach( $remove_arr as $remove_handle ) {
			// remove the script from the queue
			wp_dequeue_script( trim( $remove_handle ) );
		}
	}

}

/**************************
  REMOVE STYLES
**************************/

function spos_dequeue_styles() {
	global $spos_settings;
	$remove_setting = sanitize_text_field( $spos_settings['remove_styles'] );
	if ( $remove_setting !== '' ) {
		// separate into an array
		$remove_arr = explode( ',', $remove_setting );
		foreach( $remove_arr as $remove_handle ) {
			// remove the style from the queue
			wp_dequeue_style( trim( $remove_handle ) );
		}
	}
}

/**************************
  PROCESS HEADER SCRIPTS
**************************/

function spos_minify_head_scripts() {
	global $spos_settings;
	$remove_script_type = isset( $spos_settings['remove_script_type'] ) && $spos_settings['remove_script_type'] == 1 ? true : false;
	
	if ( !is_admin() ) {
		$start_time = microtime(true);
		global $wp_scripts;
		$buffer = array();
		$ignore = array( 'jquery' );
		$testing = false;
		$minify = true;		
		
		// grab ignored scripts from the plugin settings
		if ( isset( $spos_settings['ignore_scripts'] ) ) {
			$ignore_setting = sanitize_text_field( $spos_settings['ignore_scripts'] );
			if ( $ignore_setting !== '' ) {
				// separate into an array
				$ignore_arr = explode( ',', $ignore_setting );
				foreach( $ignore_arr as $ignore_handle ) {
					// add trimmed handle to the ignore array
					$ignore[] = trim( $ignore_handle );
				}
			}
		}
		
		// find scripts meant to be loaded in the header
		$header_scripts = array();
		$data = '';
		foreach ( $wp_scripts->queue as $script_name ) {
			// does the file end in .js?
			// this helps to eliminate problems with php files masquerading as javascript
			if ( substr( $wp_scripts->registered[$script_name]->src, -3 ) !== '.js' ) continue;

			$footer = isset( $wp_scripts->registered[$script_name]->extra['group'] ) && $wp_scripts->registered[$script_name]->extra['group'] == 1 ? true : false;
			if ( !$footer &&
				 !in_array( $script_name, $ignore ) && 
				 !in_array( $script_name, $wp_scripts->done ) &&
				 isset( $wp_scripts->registered[$script_name] ) && 
				 ( strpos( $wp_scripts->registered[$script_name]->src, get_bloginfo('url') ) !== false ||
				 strpos( $wp_scripts->registered[$script_name]->src, 'wp-includes' ) !== false )
			) {
				$header_scripts[] = $script_name;
				// retain localized data
				if ( isset( $wp_scripts->registered[$script_name]->extra['data'] ) ) {
					$data .= "\t" . $wp_scripts->registered[$script_name]->extra['data'] . "\r\n";
				} // end if
				
			} // end if
		} // end foreach
		
		// output any localized data
		$data = rtrim( $data, "\r\n" );
		if ( !empty( $data ) ) {
			$stype = $remove_script_type ? '' : ' type="text/javascript"';
			echo '<script' . $stype . '>' . "\r\n" . '/* <![CDATA[ */' . "\r\n" . $data . "\r\n" . '/* ]]> */' . "\r\n" . '</script>' . "\r\n";
		}			
		
		$md5 = md5( implode( ':', $header_scripts ) );
		$script_path = WP_CONTENT_DIR . '/cache/scripts/' . $md5;
		$flag_path = $script_path . '.txt';
		
		// check if the file exists and is less than 48 hours old
		if ( file_exists( $flag_path ) && filemtime( $flag_path ) > ( time() - ( 48 * 60 * 60 ) ) && !$testing ) {
			// the files have already been created. register the existing files
			
			// remove existing scripts since they are included in the stored files
			foreach ( $header_scripts as $script_name ) {
				$wp_scripts->done[] = $wp_scripts->registered[$script_name]->handle;
				wp_dequeue_script( $wp_scripts->registered[$script_name]->handle );
			}
			
			// register & enqueue the exising scripts
			$script_url = WP_CONTENT_URL . '/cache/scripts/' . $md5;
			if ( file_exists( $script_path . '.header.js' ) ) {
				wp_register_script( 'min-' . $md5, $script_url . '.header.js', '', filemtime( $script_path . '.header.js' ), false );				
				wp_enqueue_script( 'min-' . $md5 );
			}
					
		} else {
			// there are no existing files, so let's create them
			
			// create the directory if it doesn't exist
			if ( !file_exists( WP_CONTENT_DIR . '/cache/scripts' ) ) {
			  mkdir( WP_CONTENT_DIR . '/cache/scripts', 0755, true );
			}
			
			// store the scripts
			$buffer = '';
			
			// keep track of which scripts are in the file
			$included_scripts = array();		
			foreach( $header_scripts as $script_name ) {				
				
				// does the file exist?
				$path = realpath( '.' . str_replace( get_bloginfo('url'), '', preg_replace( '/\?.*/', '', $wp_scripts->registered[$script_name]->src ) ) );
				if ( $path ) {
					$obj = $wp_scripts->registered[$script_name];
					$deps = $obj->deps;
					$deps_loaded = true;
					foreach ( $deps as $dep ) {
						// is it already in the file?
						// is it loaded from this domain?
						if ( !in_array( $dep, $wp_scripts->done ) && ( strpos( $wp_scripts->registered[$dep]->src, get_bloginfo('url') ) !== false || strpos( $wp_scripts->registered[$dep]->src, 'wp-includes' ) !== false ) ) {
							// does the file exist?
							$dep_path = realpath( '.' . str_replace( get_bloginfo('url'), '', preg_replace( '/\?.*/', '', $wp_scripts->registered[$dep]->src ) ) );
							if ( $dep_path ) {
								// add this to the buffer
								$src = "\r\n\r\n" . '/* !!!!!!!!!!!!!!!!!!!! ' . $wp_scripts->registered[$dep]->handle . ' !!!!!!!!!!!!!!!!!!!!! */ ' . "\r\n" . file_get_contents( $dep_path );
								// add in semicolon if it's missing
								if ( substr( $src, -1, 1 ) !== ';' ) $src = $src . ';';

								// add it to the buffer
								$buffer .= $src;

								// keep track of what's in this file
								$included_scripts[] = $wp_scripts->registered[$dep]->handle;
								$wp_scripts->done[] = $wp_scripts->registered[$dep]->handle;
								// remove it from being individually enqueued
								wp_dequeue_script( $wp_scripts->registered[$dep]->handle );
							} else {
								// dependency doesn't exist
								$deps_loaded = false;
							}
						}
					}

					if ( $deps_loaded ) {

						$src = "\r\n\r\n" . '/* !!!!!!!!!!!!!!!!!!!! ' . $wp_scripts->registered[$script_name]->handle . ' !!!!!!!!!!!!!!!!!!!!! */ ' . "\r\n" . file_get_contents( $path );
						// add in semicolon if it's missing
						if ( substr( $src, -1, 1 ) !== ';' ) $src = $src . ';';

						// add it to the buffer
						$buffer .= $src;

						// keep track of what's in this file
						$included_scripts[] = $wp_scripts->registered[$script_name]->handle;
						$wp_scripts->done[] = $wp_scripts->registered[$script_name]->handle;
						// remove it from being individually enqueued
						wp_dequeue_script( $wp_scripts->registered[$script_name]->handle );

					} // end if
				} // end if 
			} // end foreach
			
			if ( !empty( $buffer ) ) {
				$footer = false;
				
				// scripts file
				$script_path = WP_CONTENT_DIR . '/cache/scripts/' . $md5 . '.header.js';
				$script_url = WP_CONTENT_URL . '/cache/scripts/' . $md5 . '.header.js';				

				if ( $minify ) {
					// Minify!						
					$minifier = new Minify\JS( $buffer );
					$buffer = $minifier->minify();
				}

				$script_file = fopen( $script_path, 'w' );
				fwrite( $script_file, $buffer );
				fclose( $script_file );

				wp_register_script( 'min-' . $md5, $script_url, '', filemtime( $script_path ), $footer );
				wp_enqueue_script( 'min-' . $md5 );
			
				// flag file
				$end_time = microtime(true);
				$execution_time = ( $end_time - $start_time );
				$gmt_offset = get_option('gmt_offset');
				$info = 'Cached ' . date('l jS \of F Y h:i:s A', time() + ( $gmt_offset * 60 * 60 ) ) . "\r\n";
					$info .= 'Initial ' . ucfirst( get_post_type() ) . ': ' . get_the_title() . "\r\n";
					$info .= 'Location: Header' . "\r\n";
					$info .= 'Includes: ' . implode( ', ', $included_scripts ) . "\r\n";
					$info .= 'Execution time: ' . $execution_time . ' seconds';

				//$flag_path = WP_CONTENT_DIR . '/cache/scripts/' . $md5 . '.txt';
				$flag_file = fopen( $flag_path, 'w' );
				fwrite( $flag_file, $info );
				fclose( $flag_file );

				if ( $testing ) {
					echo '<pre>' . $info . '</pre>';
				}
			
			}
		}
	}
}

/**************************
  PROCESS FOOTER SCRIPTS
**************************/

function spos_minify_footer_scripts() {
	global $spos_settings;
	$remove_script_type = isset( $spos_settings['remove_script_type'] ) && $spos_settings['remove_script_type'] == 1 ? true : false;
	
	if ( !is_admin() ) {
		$start_time = microtime(true);
		global $wp_scripts;
		$buffer = array();
		$ignore = array( 'jquery' );
		$testing = false;
		$minify = true;
		
		// grab ignored scripts from the plugin settings
		if ( isset( $spos_settings['ignore_scripts'] ) ) {
			$ignore_setting = sanitize_text_field( $spos_settings['ignore_scripts'] );
			if ( $ignore_setting !== '' ) {
				// separate into an array
				$ignore_arr = explode( ',', $ignore_setting );
				foreach( $ignore_arr as $ignore_handle ) {
					// add trimmed handle to the ignore array
					$ignore[] = trim( $ignore_handle );
				}
			}		
		}
		
		// retain localized data for each script and place it in the footer
		$data = '';
		foreach ( $wp_scripts->queue as $script_name ) {
			if ( isset( $wp_scripts->registered[$script_name]->extra['data'] ) && !in_array( $script_name, $ignore ) ) {
				$data .= "\t" . $wp_scripts->registered[$script_name]->extra['data'] . "\r\n";
			}
		}
		$data = rtrim( $data, "\r\n" );
		if ( !empty( $data ) ) {
			$stype = $remove_script_type ? '' : ' type="text/javascript"';
			echo '<script' . $stype . '>' . "\r\n" . '/* <![CDATA[ */' . "\r\n" . $data . "\r\n" . '/* ]]> */' . "\r\n" . '</script>' . "\r\n";
		}
		
		$footer_scripts = array();
		foreach ( $wp_scripts->queue as $script_name ) {
			// does the file end in .js?
			// this helps to eliminate problems with php files masquerading as javascript
			if ( substr( $wp_scripts->registered[$script_name]->src, -3 ) !== '.js' ) continue;
			
			if ( !in_array( $script_name, $ignore ) && 
				 !in_array( $script_name, $wp_scripts->done ) &&
				 isset( $wp_scripts->registered[$script_name] ) && 
				 ( strpos( $wp_scripts->registered[$script_name]->src, get_bloginfo('url') ) !== false ||
				 strpos( $wp_scripts->registered[$script_name]->src, 'wp-includes' ) !== false )
			) {
				$footer_scripts[] = $script_name;
			}
		}
		
		$md5 = md5( implode( ':', $footer_scripts ) ); // some elements in the queue may already be done
		$script_path = WP_CONTENT_DIR . '/cache/scripts/' . $md5;
		$flag_path = $script_path . '.txt';
		
		// check if the file exists and is less than 48 hours old
		if ( file_exists( $flag_path ) && filemtime( $flag_path ) > ( time() - ( 48 * 60 * 60 ) ) && !$testing ) {
			// the files have already been created. register the existing files
			
			// remove existing scripts since they are included in the stored files
			foreach ( $footer_scripts as $script_name ) {
				if ( !in_array( $script_name, $ignore ) && strpos( $wp_scripts->registered[$script_name]->src, get_bloginfo('url') ) !== false ) {
					wp_dequeue_script( $wp_scripts->registered[$script_name]->handle );
					$wp_scripts->done[] = $wp_scripts->registered[$script_name]->handle;
				}
			}
			
			// register & enqueue the exising scripts
			$script_url = WP_CONTENT_URL . '/cache/scripts/' . $md5;
			if ( file_exists( $script_path . '.footer.js' ) ) {
				wp_register_script( 'min-', $script_url . '.footer.js', '', filemtime( $script_path . '.footer.js' ), true );				
				wp_enqueue_script( 'min-' );
			}
					
		} else {
			// there are no existing files, so let's create them
			
			// create the directory if it doesn't exist
			if ( !file_exists( WP_CONTENT_DIR . '/cache/scripts' ) ) {
			  mkdir( WP_CONTENT_DIR . '/cache/scripts', 0755, true );
			}
			
			// create new files
			$buffer = '';
			
			// keep track of which scripts are in the file
			$included_scripts = array();		
			foreach( $footer_scripts as $script_name ) {
				// ignore specific scripts
				// make sure info on the script exists
				// make sure it's loading from the local site
				if ( !in_array( $script_name, $ignore ) && 
					 !in_array( $script_name, $wp_scripts->done ) &&
					 isset( $wp_scripts->registered[$script_name] ) && 
					 ( 
					   strpos( $wp_scripts->registered[$script_name]->src, get_bloginfo('url') ) !== false ||
					   strpos( $wp_scripts->registered[$script_name]->src, 'wp-includes' ) !== false
					 )
				 ) {					
					// does the file exist?
					$path = realpath( '.' . str_replace( get_bloginfo('url'), '', preg_replace( '/\?.*/', '', $wp_scripts->registered[$script_name]->src ) ) );
					if ( $path ) {
						$obj = $wp_scripts->registered[$script_name];
						$deps = $obj->deps;
						$deps_loaded = true;
						foreach ( $deps as $dep ) {
							// is it already in the file?
							// is it loaded from this domain?
							if ( !in_array( $dep, $wp_scripts->done ) && ( strpos( $wp_scripts->registered[$dep]->src, get_bloginfo('url') ) !== false || strpos( $wp_scripts->registered[$dep]->src, 'wp-includes' ) !== false ) ) {
								// does the file exist?
								$dep_path = realpath( '.' . str_replace( get_bloginfo('url'), '', preg_replace( '/\?.*/', '', $wp_scripts->registered[$dep]->src ) ) );
								if ( $dep_path ) {
									// add this to the buffer
									$src = "\r\n\r\n" . '/* !!!!!!!!!!!!!!!!!!!! ' . $wp_scripts->registered[$dep]->handle . ' !!!!!!!!!!!!!!!!!!!!! */ ' . "\r\n" . file_get_contents( $dep_path );
									// add in semicolon if it's missing
									if ( substr( $src, -1, 1 ) !== ';' ) $src = $src . ';';

									$buffer .= $src;

									$included_scripts[] = $wp_scripts->registered[$dep]->handle;
									$wp_scripts->done[] = $wp_scripts->registered[$dep]->handle;
									wp_dequeue_script( $wp_scripts->registered[$dep]->handle );
								} else {
									// dependency doesn't exist
									$deps_loaded = false;
								}
							}
						}

						if ( $deps_loaded ) {

							$src = "\r\n\r\n" . '/* !!!!!!!!!!!!!!!!!!!! ' . $wp_scripts->registered[$script_name]->handle . ' !!!!!!!!!!!!!!!!!!!!! */ ' . "\r\n" . file_get_contents( $path );
							// add in semicolon if it's missing
							if ( substr( $src, -1, 1 ) !== ';' ) $src = $src . ';';

							$buffer .= $src;

							$included_scripts[] = $wp_scripts->registered[$script_name]->handle;
							$wp_scripts->done[] = $wp_scripts->registered[$script_name]->handle;
							wp_dequeue_script( $wp_scripts->registered[$script_name]->handle );

						} // end if
					} // end if 
				} // end if
			} // end foreach
			
			if ( !empty( $buffer ) ) {
				$footer = true;
				
				// scripts file
				$script_path = WP_CONTENT_DIR . '/cache/scripts/' . $md5 . '.footer.js';
				$script_url = WP_CONTENT_URL . '/cache/scripts/' . $md5 . '.footer.js';				

				if ( $minify ) {
					// Minify!						
					$minifier = new Minify\JS( $buffer );
					$buffer = $minifier->minify();
				}

				$script_file = fopen( $script_path, 'w' );
				fwrite( $script_file, $buffer );
				fclose( $script_file );

				wp_register_script( 'min-', $script_url, '', filemtime( $script_path ), $footer );
				wp_enqueue_script( 'min-' );
				
				// flag file
				$end_time = microtime(true);
				$execution_time = ( $end_time - $start_time );
				$gmt_offset = get_option('gmt_offset');
				$info = 'Cached ' . date('l jS \of F Y h:i:s A', time() + ( $gmt_offset * 60 * 60 ) ) . "\r\n";
					$info .= 'Initial ' . ucfirst( get_post_type() ) . ': ' . get_the_title() . "\r\n";
					$info .= 'Location: Footer' . "\r\n";
					$info .= 'Includes: ' . implode( ', ', $included_scripts ) . "\r\n";
					$info .= 'Execution time: ' . $execution_time . ' seconds';

				//$flag_path = WP_CONTENT_DIR . '/cache/scripts/' . $md5;
				$flag_file = fopen( $flag_path, 'w' );
				fwrite( $flag_file, $info );
				fclose( $flag_file );

				if ( $testing ) {
					echo '<pre>' . $info . '</pre>';
				}
			}
			
			
		}
	}
}

/********************
  PROCESS STYLES
********************/

function spos_minify_styles() {
	global $spos_settings;
	$remove_style_type = isset( $spos_settings['remove_style_type'] ) && $spos_settings['remove_style_type'] == 1 ? true : false;
	
	if ( !is_admin() ) {
		$start_time = microtime(true);
		global $wp_styles;
		$buffer = array();
		$ignore = array();
		$testing = false;
		$minify = true;
		
		// grab ignored styles from the plugin settings
		if ( isset( $spos_settings['ignore_styles'] ) ) {
			$ignore_setting = sanitize_text_field( $spos_settings['ignore_styles'] );
			if ( $ignore_setting !== '' ) {
				// separate into an array
				$ignore_arr = explode( ',', $ignore_setting );
				foreach( $ignore_arr as $ignore_handle ) {
					// add trimmed handle to the ignore array
					$ignore[] = trim( $ignore_handle );
				}
			}		
		}
		
		if ( count( array_diff( $wp_styles->queue, $ignore ) ) == 0 ) {
			// ignoring everything
			return;
		}

		$md5 = md5( implode( ':', $wp_styles->queue ) );
		$style_path = WP_CONTENT_DIR . '/cache/styles/' . $md5;
		$flag_path = $style_path . '.txt';
		
		// check if the file exists and is less than 48 hours old
		if ( file_exists( $flag_path ) && filemtime( $flag_path ) > ( time() - ( 48 * 60 * 60 ) ) && !$testing ) {
			// register the existing files
			foreach ( $wp_styles->queue as $k => $style_name ) {
				if ( in_array( $style_name, $ignore ) ) continue;
				
				// does the file end in .css?
				// this helps to eliminate problems with php files masquerading as css
				if ( substr( $wp_styles->registered[$style_name]->src, -4 ) !== '.css' ) continue;

				if ( strpos( $wp_styles->registered[$style_name]->src, get_bloginfo('url') ) !== false ) {
					if ( !in_array( $wp_styles->registered[$style_name]->args, $buffer ) ) {
						$buffer[] = $wp_styles->registered[$style_name]->args;
					}

					wp_deregister_style( $wp_styles->registered[$style_name]->handle );
				}
			}
			
			foreach( $buffer as $media ) {
				$slug = preg_replace('/[^A-Za-z0-9\-]/', '', $media);
				
				$style_url = WP_CONTENT_URL . '/cache/styles/' . $md5 . '.' . $slug . '.css';
				
				wp_register_style( 'min-' . $slug, $style_url, '', filemtime( $style_path . '.' . $slug . '.css' ), $media );				
				wp_enqueue_style( 'min-' . $slug );
				
				// put the optimized styles first
				$s = array_pop( $wp_styles->queue );
				array_unshift( $wp_styles->queue, $s );
			}
					
		} else {
			// create the directory if it doesn't exist
			if ( !file_exists( WP_CONTENT_DIR . '/cache/styles' ) ) {
			  mkdir( WP_CONTENT_DIR . '/cache/styles', 0755, true );
			}
			
			// keep track of which styles are in the file
			$included_styles = array();
			// separate out conditional styles to add in afterwards
			$conditional_styles = array();
			
			// create new files
			foreach( $wp_styles->queue as $k => $style_name ) {				
				// skip ignored styles
				if ( in_array( $style_name, $ignore ) ) continue;
				
				// does the file end in .css?
				// this helps to eliminate problems with php files masquerading as css
				if ( substr( $wp_styles->registered[$style_name]->src, -4 ) !== '.css' ) {
					continue;
				}
				
				if ( isset( $wp_styles->registered[$style_name] ) && strpos( $wp_styles->registered[$style_name]->src, get_bloginfo('url') ) !== false ) {
					$obj = $wp_styles->registered[$style_name];
					
					// keep track of conditionals to add in afterwards
					if ( array_key_exists( 'conditional', $obj->extra ) ) {
						$conditional_styles[] = $obj->handle;
						//wp_deregister_style( $obj->handle );
						continue;
					}
					
					// does the file exist?
					$path = realpath( '.' . str_replace( get_bloginfo('url'), '', preg_replace( '/\?.*/', '', $wp_styles->registered[$style_name]->src ) ) );
					if ( $path ) {						
						$deps = $obj->deps;
						$deps_loaded = true;
						foreach ( $deps as $dep ) {
							if ( !in_array( $dep, $wp_styles->queue ) ) {
								$deps_loaded = false;
								break;
							}
						}
						if ( $deps_loaded ) {
							
							$src = file_get_contents( $path );
							// does the file have @imports?
							preg_match_all( '/@import url\((.*?)\);/', $src, $matches );

							if ( !empty( $matches[0] ) ) {
								$imports = '';
								foreach( $matches[0] as $at_import ) {
									// remove the import
									$src = str_replace( $at_import, '', $src );
								}
								foreach ( $matches[1] as $import_path ) {
									$full_path = str_replace( '\\', '/', dirname( $path ) ) . '/' . str_replace( array( '\'', '"' ), '', $import_path );
									$real_import_path = realpath( $full_path );
									if ( $real_import_path ) {
										$import_src = file_get_contents( $real_import_path );
										$imports .= '/* @import url(' . $import_path . ') */' . "\r\n" . $import_src . "\r\n";	
									}
								}
								$src = $imports . $src;
							}
							
							// update url's to point to the right place
							$patharr = explode( '/', $wp_styles->registered[$style_name]->src );
							$pathlen = count( $patharr );
							$pattern = '/url\((.*?)\)/';
							$src = preg_replace_callback(
								$pattern, 
								function( $matches ) {
									if ( strpos( $matches[0], 'http' ) === false ) {
										return 'url(INSERT_URL/' . str_replace( array( '\'', '"', 'url(', ')' ), '', $matches[0] ) . ')';
									} else {
										return $matches[0];
									}
								},
								$src
							);
							
							$src = str_replace( 'INSERT_URL', implode( '/', array_slice( $patharr, 0, $pathlen - 1 ) ), $src );
							
							if ( empty( $src ) ) {
							} else {
								$media_type = isset( $wp_styles->registered[$style_name]->args ) ? $wp_styles->registered[$style_name]->args : 'all';
								$src = "\r\n\r\n" . '/* !!!!!!!!!!!!!!!!!!!! ' . $wp_styles->registered[$style_name]->handle . ' !!!!!!!!!!!!!!!!!!!!! */ ' . "\r\n" . str_replace( '@charset "utf-8";', '', $src );
								if ( !isset( $buffer[$media_type] ) ) { 		
									$buffer[$media_type] = $src;
								} else {
									$buffer[$media_type] .= $src;
								}
								$included_styles[] = $wp_styles->registered[$style_name]->handle;
								//wp_deregister_style( $wp_styles->registered[$style_name]->handle );
								wp_dequeue_style( $wp_styles->registered[$style_name]->handle );
							}
							
						} // end if
					} // end if
				} // end if
			} // end foreach
			
			if ( !empty( $included_styles ) ) { // check to make sure there are valid styles

				$end_time = microtime(true);
				$execution_time = ( $end_time - $start_time );
				$gmt_offset = get_option('gmt_offset');
				$info = 'Cached ' . date('l jS \of F Y h:i:s A', time() + ( $gmt_offset * 60 * 60 ) ). "\r\n";
					$info .= 'Initial ' . ucfirst( get_post_type() ) . ': ' . get_the_title() . "\r\n";
					$info .= 'Includes: ' . implode( ', ', $included_styles ) . "\r\n";
					$info .= 'Execution time: ' . $execution_time . ' seconds';

				//$flag_path = WP_CONTENT_DIR . '/cache/styles/' . $md5;
				$flag_file = fopen( $flag_path, 'w' );
				fwrite( $flag_file, $info );
				fclose( $flag_file );

				foreach ( $buffer as $media => $src ) {
					$slug = preg_replace('/[^A-Za-z0-9\-]/', '', $media);

					$style_path = WP_CONTENT_DIR . '/cache/styles/' . $md5 . '.' . $slug . '.css';
					$style_url = WP_CONTENT_URL . '/cache/styles/' . $md5 . '.' . $slug . '.css';

					if ( $minify ) {
						// Minify!						
						$minifier = new Minify\CSS( $src );
						$src = $minifier->minify();
					}

					$style_file = fopen( $style_path, 'w' );
					$charset = '@charset "utf-8";';
					fwrite( $style_file, $charset . "\r\n" . $src );
					fclose( $style_file );
					wp_register_style( 'min-styles-' . $slug, $style_url, '', filemtime( $style_path ), $media );
					wp_enqueue_style( 'min-styles-' . $slug );
					
					// put the optimized styles first
					$s = array_pop( $wp_styles->queue );
					array_unshift( $wp_styles->queue, $s );
				}

				// re-sort the queue to have conditionals at the end
				foreach ( $conditional_styles as $conditional_handle ) {
					$key = array_search( $conditional_handle, $wp_styles->queue );
					if ( $key !== false ) {
						unset( $wp_styles->queue[$key] );
						$wp_styles->queue[] = $conditional_handle;
					}
				}
			}
		}
	}
}

	
?>