<?php

function register_spos_menu_page() {	
	add_submenu_page( 'options-general.php', 'Optimization', 'Optimization', 'manage_options', 'spos', 'spos_admin_page');
}
add_action( 'admin_menu', 'register_spos_menu_page' );

function spos_admin_page() {
	include plugin_dir_path( __FILE__ ) . '/pages/spos-admin.php';
}

function spos_settings_init() {

	register_setting( 'spos_settings_page', 'spos_settings' );

	/************************
	 * GENERAL SETTINGS
	 ************************/

	add_settings_section(
		'spos_settings_section', 
		__( 'General Settings', 'spos' ), 
		'spos_settings_section_callback', 
		'spos_settings_page'
	);

	add_settings_field( 
		'optimize_scripts', 
		__( 'Optimize Scripts', 'spos' ), 
		'spos_optimize_scripts_field', 
		'spos_settings_page', 
		'spos_settings_section' 
	);

	add_settings_field( 
		'optimize_styles', 
		__( 'Optimize Styles', 'spos' ), 
		'spos_optimize_styles_field', 
		'spos_settings_page', 
		'spos_settings_section' 
	);
	
	add_settings_field( 
		'enable_logged_in', 
		__( 'Enable for logged in users', 'spos' ), 
		'spos_enable_logged_in_field', 
		'spos_settings_page', 
		'spos_settings_section' 
	);

	add_settings_field( 
		'remove_script_type', 
		__( 'Remove <span>type=\'text/javascript\'</span> from script tags', 'spos' ), 
		'spos_remove_script_type_field', 
		'spos_settings_page', 
		'spos_settings_section' 
	);

	add_settings_field( 
		'remove_style_type', 
		__( 'Remove <span>type=\'text/css\'</span> from style tags', 'spos' ), 
		'spos_remove_style_type_field', 
		'spos_settings_page', 
		'spos_settings_section' 
	);
	
	add_settings_field( 
		'show_admin_bar', 
		__( 'Show clear button in admin bar', 'spos' ), 
		'spos_show_admin_bar_field', 
		'spos_settings_page', 
		'spos_settings_section' 
	);

	/************************
	 * REMOVE SETTINGS
	 ************************/

	add_settings_section(
		'spos_settings_section_2', 
		__( 'Remove', 'spos' ), 
		'spos_settings_section_2_callback', 
		'spos_settings_page'
	);
	
	add_settings_field( 
		'remove_scripts', 
		__( 'Remove scripts', 'spos' ), 
		'spos_remove_scripts_field', 
		'spos_settings_page', 
		'spos_settings_section_2' 
	);
	
	add_settings_field( 
		'remove_styles', 
		__( 'Remove styles', 'spos' ), 
		'spos_remove_styles_field', 
		'spos_settings_page', 
		'spos_settings_section_2' 
	);
	
	/************************
	 * IGNORE SETTINGS
	 ************************/

	add_settings_section(
		'spos_settings_section_3', 
		__( 'Ignore', 'spos' ), 
		'spos_settings_section_3_callback', 
		'spos_settings_page'
	);
	
	add_settings_field( 
		'ignore_scripts', 
		__( 'Ignore scripts', 'spos' ), 
		'spos_ignore_scripts_field', 
		'spos_settings_page', 
		'spos_settings_section_3' 
	);
	
	add_settings_field( 
		'ignore_styles', 
		__( 'Ignore styles', 'spos' ), 
		'spos_ignore_styles_field', 
		'spos_settings_page', 
		'spos_settings_section_3' 
	);

}
add_action( 'admin_init', 'spos_settings_init' );

function spos_settings_section_callback() {
	echo __( 'Choose from the settings below. Some options may not work with your particular configuration, so be sure to test before using on a production site.', 'spos' );
}

function spos_settings_section_2_callback() {
	echo __( 'If your theme has a ton of junk styles or scripts, you can remove them here. Be sure the scripts or styles you remove here are not in use. The files are not physically removed, but they will no longer load on your site.', 'spos' );
}

function spos_settings_section_3_callback() {
	echo __( 'This is where you can exclude problem scripts or ignore custom styles that may be registered out of order. Scripts registered with a php extension are automatically ignored. You may need to experiment to get the best results for your site. Check the Cached files section below for the handles to use here.', 'spos' );
}

function spos_optimize_scripts_field() {
	global $spos_settings;
	?>
	<span class="switch">
		<input type="checkbox" id="optimize-scripts" value="1" name="spos_settings[optimize_scripts]" <?php echo isset( $spos_settings['optimize_scripts'] ) ? checked( $spos_settings['optimize_scripts'], 1, false ) : ''; ?> />
		<label for="optimize-scripts"></label>
		<span class="switch-knob"></span>
	</span>
	<span class="description">This option will combine and minify your JavaScript files.</span>
	<?php
}

function spos_optimize_styles_field() {
	global $spos_settings;
	?>
	<span class="switch">
		<input type="checkbox" id="optimize-styles" value="1" name="spos_settings[optimize_styles]" <?php echo isset( $spos_settings['optimize_styles'] ) ? checked( $spos_settings['optimize_styles'], 1, false ) : ''; ?> />
		<label for="optimize-styles"></label>
		<span class="switch-knob"></span>
	</span>
	<span class="description">This option will combine and minify your CSS files.</span>
	<?php
}

function spos_enable_logged_in_field() {
	global $spos_settings;
	?>
	<span class="switch">
		<input type="checkbox" id="enable-logged-in" value="1" name="spos_settings[enable_logged_in]" <?php echo isset( $spos_settings['enable_logged_in'] ) ? checked( $spos_settings['enable_logged_in'], 1, false ) : ''; ?> />
		<label for="enable-logged-in"></label>
		<span class="switch-knob"></span>
	</span>
	<span class="description">(not recommended)</span>
	<?php
}

function spos_remove_script_type_field() {
	global $spos_settings;
	?>
	<span class="switch">
		<input type="checkbox" id="remove-script-type" value="1" name="spos_settings[remove_script_type]" <?php echo isset( $spos_settings['remove_script_type'] ) ? checked( $spos_settings['remove_script_type'], 1, false ) : ''; ?> />
		<label for="remove-script-type"></label>
		<span class="switch-knob"></span>
	</span>
	<span class="description">If your theme is built in HTML5, select this to remove the type attribute from enqueued scripts.</span>
	<?php
}

function spos_remove_style_type_field() {
	global $spos_settings;
	?>
	<span class="switch">
		<input type="checkbox" id="remove-style-type" value="1" name="spos_settings[remove_style_type]" <?php echo isset( $spos_settings['remove_style_type'] ) ? checked( $spos_settings['remove_style_type'], 1, false ) : ''; ?> />
		<label for="remove-style-type"></label>
		<span class="switch-knob"></span>
	</span>
	<span class="description">If your theme is built in HTML5, select this to remove the type attribute from enqueued styles.</span>
	<?php
}

function spos_show_admin_bar_field() {
	global $spos_settings;
	?>
	<span class="switch">
		<input type="checkbox" id="show-admin-bar" value="1" name="spos_settings[show_admin_bar]" <?php echo isset( $spos_settings['show_admin_bar'] ) ? checked( $spos_settings['show_admin_bar'], 1, false ) : ''; ?> />
		<label for="show-admin-bar"></label>
		<span class="switch-knob"></span>
	</span>
	<?php
}

function spos_remove_scripts_field() {
	global $spos_settings;
	?>
	<input type="text" id="remove-scripts" name="spos_settings[remove_scripts]" value="<?php echo isset( $spos_settings['remove_scripts'] ) ? $spos_settings['remove_scripts'] : ''; ?>" class="spos-large" />
		<label class="spos-label" for="remove-scripts">Enter a comma separated list of script handles to remove.</label>
	</span>
	<?php
}

function spos_remove_styles_field() {
	global $spos_settings;
	?>
	<input type="text" id="remove-styles" name="spos_settings[remove_styles]" value="<?php echo isset( $spos_settings['remove_styles'] ) ? $spos_settings['remove_styles'] : ''; ?>" class="spos-large" />
		<label class="spos-label" for="remove-styles">Enter a comma separated list of style handles to remove.</label>
	</span>
	<?php
}

function spos_ignore_scripts_field() {
	global $spos_settings;
	?>
	<input type="text" id="ignore-scripts" name="spos_settings[ignore_scripts]" value="<?php echo isset( $spos_settings['ignore_scripts'] ) ? $spos_settings['ignore_scripts'] : ''; ?>" class="spos-large" />
		<label class="spos-label" for="ignore-scripts">Enter a comma separated list of script handles to ignore. jQuery is ignored by default.</label>
	</span>
	<?php
}

function spos_ignore_styles_field() {
	global $spos_settings;
	?>
	<input type="text" id="ignore-styles" name="spos_settings[ignore_styles]" value="<?php echo isset( $spos_settings['ignore_styles'] ) ? $spos_settings['ignore_styles'] : ''; ?>" class="spos-large" />
		<label class="spos-label" for="ignore-styles">Enter a comma separated list of style handles to ignore.</label>
	</span>
	<?php
}

function spos_purge_scripts_styles() {
	$purge = false;
	if ( isset( $_GET['spos_action'] ) ) {
		// clearing cache from the admin bar
		$purge = trim( strip_tags( $_GET['spos_action'] ) );
	} else if ( isset( $_GET['w3tc_note'] ) ) {
		// this is specific to the W3 Total Cache plugin, allowing you to empty the cache using it's functions
		$purge = trim( strip_tags( $_GET['w3tc_note'] ) );
	} else if ( isset( $_POST['spos_action'] ) ) {
		// button from admin page
		$valid_nonce = isset($_REQUEST['_wpnonce']) ? wp_verify_nonce($_REQUEST['_wpnonce'], 'spos_clear_cache') : false;
		if ( !$valid_nonce ) {
			return;
		}
		$purge = trim( strip_tags( $_POST['spos_action'] ) );
	}

	if ( current_user_can('manage_options') && ( $purge == 'flush_all' || $purge == 'flush_pgcache' ) ) {
		// clear optimized scripts
		spos_clear_cache();	
		
		add_action( 'admin_notices', 'spos_admin_notice_success' );
	}
}
add_action('admin_head', 'spos_purge_scripts_styles' );

function spos_admin_notice_success() {
    ?>
    <div class="updated">
        <p>Cached files cleared!</p>
    </div>
    <?php
}

function spos_admin_bar_menu( $wp_admin_bar ) {
	global $spos_settings;
	if ( isset( $spos_settings['show_admin_bar'] ) && $spos_settings['show_admin_bar'] == 1 ) {
		// create a link with the current query string variables intact
		$protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
		$host = $_SERVER['HTTP_HOST'];
		$uri = $_SERVER['REQUEST_URI'];
		
		$fragments = explode( '?', $uri );
		$baseuri = array_shift( $fragments );
		
		// grab existing query string variables if they exist and add spos_action
		if ( !empty( $fragments ) ) {
			parse_str( $fragments[0], $qvars );
			$qvars['spos_action'] = 'flush_all';
			$qvars = '?' . http_build_query( $qvars );
		} else {
			$qvars = '?spos_action=flush_all';
		}		
		
		$href = $protocol . $host . $baseuri . $qvars;
		$args = array(
			'id' => 'spos_admin_bar',
			'title' => 'Clear Optimized Scripts',
			'href' => $href,
			'meta' => array( 
				'class' => 'spos-toolbar-page',
				'title' => 'Clear Optimized Scripts'
			)
		);
		$wp_admin_bar->add_node( $args );
	}
}
add_action( 'admin_bar_menu', 'spos_admin_bar_menu', 999 );	
	
?>