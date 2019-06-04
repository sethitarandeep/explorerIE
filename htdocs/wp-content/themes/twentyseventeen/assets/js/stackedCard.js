jQuery(document).ready(function() {
    var content = jQuery('.diveIncontent');
    var currentItem = content.filter('.diveActive');
    var steps = jQuery('.card').filter('.steps');
    var inactive1 = jQuery('.inactive-1');
    var inactive2 = jQuery('.inactive-2');
	var stepCounter = 20;

    jQuery('.customBtn').click(function() {
        var nextItem = currentItem.next();
        var lastItem = content.last();
        var contentFirst = content.first();
		
      
        currentItem.removeClass('diveActive');

        if (currentItem.is(lastItem)) {
            currentItem = contentFirst.addClass('diveActive');
            currentItem.css({'right': '10%', 'opacity': '1'});
			stepCounter = 20;
            jQuery('.step').animate({width: stepCounter+'%'});
			console.log('increasewd if'+ stepCounter);
            inactive1.animate({height: '8px', marginLeft:'20px', marginRight:'20px'}, 100);
            inactive2.animate({height: '8px', marginLeft:'10px', marginRight:'10px'}, 100);
          
        } else if (currentItem.is(contentFirst)) {
            currentItem.animate({opacity: 0}, 1000);
            currentItem = nextItem.addClass('diveActive');
			stepCounter = stepCounter+16;
            jQuery('.step').animate({width: stepCounter+'%'});
			console.log('increasewd else if'+ stepCounter);
            inactive2.animate({height: '0', marginLeft:'0px', marginRight:'0px'}, 100);
          
        } else {
			//stepCounter = stepCounter+16;
            currentItem = nextItem.addClass('diveActive');
			stepCounter = stepCounter + 16;
			console.log('increasewd else'+ stepCounter);
			jQuery('.step').animate({width: stepCounter+'%'});
			if(nextItem == lastItem){
            	inactive1.animate({height: '0', marginLeft:'0px', marginRight:'0px'}, 100);
			}
        } 
    });
  
});