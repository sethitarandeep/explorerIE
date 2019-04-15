jQuery(document).ready( function() {
	jQuery('#searchicon').click(function() {
		jQuery('#jumbosearch').fadeIn();
		jQuery('#jumbosearch input').focus();
	});
	jQuery('#jumbosearch .closeicon').click(function() {
		jQuery('#jumbosearch').fadeOut();
	});
	jQuery('body').keydown(function(e){
	    
	    if(e.keyCode == 27){
	        jQuery('#jumbosearch').fadeOut();
	    }
	});
	


jQuery(document).ready( function() { 
	jQuery('#top-menu ul.menu').mobileMenu({
		switchWidth: 767
		});
	jQuery('#top-menu div.menu ul').mobileMenu({
		switchWidth: 767
		});	

	jQuery('#site-navigation .container ul.menu').mobileMenu({
		switchWidth: 767
		});
	jQuery('#site-navigation .container div.menu ul').mobileMenu({
		switchWidth: 767
		});	
});
	
});//endready

