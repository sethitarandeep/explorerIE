<?php
/**
 * Displays footer site info
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 * 
 * <?php echo esc_url( __( '', '' ) ); ?>
 */

?>
<div class="site-info">
	<?php
	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link( '', '<span role="separator" aria-hidden="true"></span>' );
	}
	?>
	<span class="imprint" style="font-size:14px; align:center;">
		<?php printf( __( 'Ideated and Developed by %s', 'twentyseventeen' ), 'Team Explorer' ); ?>
	</span>
</div><!-- .site-info -->
