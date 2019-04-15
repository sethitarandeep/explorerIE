/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	
	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '#text-title-desc' ).css({
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute'
				});
				// Add class for different logo styles if title and description are hidden.
				$( 'body' ).addClass( 'title-tagline-hidden' );
			} else {
				
				$( '#text-title-desc' ).css({
					clip: 'auto',
					position: 'relative'
				});
				$( '.site-branding a' ).css({
					color: to
				});
				// Add class for different logo styles if title and description are visible.
				$( 'body' ).removeClass( 'title-tagline-hidden' );
			}
		});
	});
	
	// Social Icons
	var	count 	=	[1,2,3,4,5,6,7];
	$.each( count, function( index ) {
		wp.customize( 'kurama_social_' + index, function( value ) {
	        value.bind( function( to ) {
	            var ClassNew	=	'fa fa-fw fa-' + to;
	            jQuery('#social-icons' ).find( 'i:eq(' + (index-1) + ')' ).attr( 'class', ClassNew );
	        });
	    });
	});
	
	 //Sidebar
    wp.customize( 'kurama_sidebar_width', function( value ) {
        value.bind( function( to ) {
            var SidebarWidth    =   (to * 100) / 12;
            $('#secondary').css('width', SidebarWidth + '%' );
            $('#primary, #primary-mono').css('width', 100 - SidebarWidth + '%' );
        } );
    } );
	
} )( jQuery );
