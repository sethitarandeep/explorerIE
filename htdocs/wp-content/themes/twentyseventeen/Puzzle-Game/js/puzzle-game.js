jQuery(document).ready(function() {


	var box = jQuery(".box"),
		orginal = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
		temp = orginal,
		x = [],
		sec = 0,
		date1,date2,
		moves = 0,
		mm = 0,
		ss = 0,
		upIMG,
		images = ["https://preview.ibb.co/kMdsfm/kfp.png","https://preview.ibb.co/kWOEt6/minion.png","https://preview.ibb.co/e0Rv0m/ab.jpg","https://holdmyhand.ga/wp-content/themes/twentyseventeen/assets/images/wonder-woman-552109_1920.jpg"]
		img = 0;




	jQuery('.me').css({"background-image" : 'url('+images[0]+')'});

	jQuery(".start").click(function() {
		jQuery(".start").delay(100).slideUp(500);
		jQuery(".full").hide();
		jQuery(".pre_img").addClass("prevent_click");
		startTimer();
		date1 = new Date();
		Start();
		return 0;
	});

	function Start() {
		randomTile();
		changeBG(img);
		var count = 0,
			a,
			b,
			A,
			B;
		jQuery(".me").click(function() {
			count++;
			if (count == 1) {
				a = jQuery(this).attr("data-bid");
				jQuery('.me_'+a).css({"opacity": ".65"});
			} else {
				b = jQuery(this).attr("data-bid");	
				jQuery('.me_'+a).css({"opacity": "1"});
				if (a == b) {
				} else {
					jQuery(".me_" + a)
						.addClass("me_" + b)
						.removeClass("me_" + a);
					jQuery(this)
						.addClass("me_" + a)
						.removeClass("me_" + b);
					jQuery(".me_" + a).attr("data-bid", a);
					jQuery(".me_" + b).attr("data-bid", b);
				}
				moves++;
date2 = new Date();
				timeDifferece();

	jQuery('#contMoves').html(moves);
				swapping(a, b);
				checkCorrect(a);
				checkCorrect(b);
				a = b = count = A = B = 0;
		
			}
			if (arraysEqual(x)) { 
timeDifferece();
				showScore();
				return 0;
			}
		});
		return 0;
	}

	function randomTile() {
		var i;
		for (i = orginal.length-1; i >= 0; i--) {
			var flag = getRandom(0, i);
			x[i] = temp[flag];
			temp[flag] = temp[i];
			temp[i] = x[i];
		}
		for (i = 0; i < orginal.length; i++) {
			box.append(
				'<div  class="me me_' + x[i] + ' tile" data-bid="' + x[i] + '"></div>'
			);
			if ((i + 1) % 6 == 0) box.append("<br>");
		}
		i = 17;
		return 0;
	}

	function arraysEqual(arr) {
		var i;
		for (i = orginal.length - 1; i >= 0; i--) {
			if (arr[i] != i) return false;
		}
		return true;
	}

	function checkCorrect(N1) {
		var pos = x.indexOf(parseInt(N1, 10));
		if (pos != N1) {
			return;
		}
		jQuery(".me_" + N1).addClass("correct , prevent_click ");
		return;
	}

	function swapping(N1, N2) {
		var first = x.indexOf(parseInt(N1, 10)),
			second = x.indexOf(parseInt(N2, 10));
		x[first] = parseInt(N2, 10);
		x[second] = parseInt(N1, 10);
		return 0;
	}
	
	function getRandom(min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
		}
	
	function timeDifferece(){
		var diff = date2 - date1;
		var msec = diff;
		var hh = Math.floor(msec / 1000 / 60 / 60);
		msec -= hh * 1000 * 60 * 60;
	 	mm = Math.floor(msec / 1000 / 60); // Gives Minute
		msec -= mm * 1000 * 60;
		ss = Math.floor(msec / 1000);		// Gives Second
		msec -= ss * 1000;
		return 0;
	}


	function changeBG(img){	
		if(img != 4){
		jQuery('.me').css({
			"background-image" : "url("+images[img]+")"
		});
		return
		}
		else
			jQuery('.me').css({"background-image" : "url("+upIMG+")"});
	}

	jQuery('.pre_img li').hover(function(){
			img = jQuery(this).attr("data-bid");
			changeBG(img);

		});
let modal = document.getElementById("scorePopup")
 let modal2 = document.getElementById("instructionsPopup")
	
	function showScore(){
		jQuery('#min').html(mm);
		jQuery('#sec').html(ss);
		jQuery('#moves').html(moves);
modal.classList.add("show");
		return 0;
	}

	/*function showinstructionspopup () {
		modal2.classList.add("show");
	}*/
	
/*function closeSuccessModal (){
	modal.classList.remove("show");
}
function closeInstructionsModal (){
	modal2.classList.remove("show");
}*/

	jQuery('.instructionsButton').click(function(){
modal2.classList.add("show");
	});
	
	jQuery('#closeScore').click(function(){
modal.classList.remove("show");
	});
	jQuery('#closeInstructions').click(function(){
modal2.classList.remove("show");
	});
	

	jQuery('.reset').click(function(){
		jQuery(".tile").remove();
		jQuery("br").remove();
		jQuery(".full").show();
		jQuery(".start").show();
jQuery('#contMin').html(0);
		jQuery('#contSec').html(0);
		jQuery('#contMoves').html(0);
jQuery('#min').html(0);
modal.classList.remove("show");
		jQuery('#sec').html(0);
		jQuery('#moves').html(0);
second = 0;
    minute = 0; 
    hour = 0;
    clearInterval(interval);
		jQuery(".pre_img").removeClass("prevent_click");
		
		temp = orginal;
		x = [];
		moves =  ss = mm = 0;
		return 0;
	});

	jQuery("#upfile1").click(function () {
 	   jQuery("#file1").trigger('click');
	});

	jQuery("#file1").change(function(){
        readURL(this);
    });
var minute = 0;
var second = 0;
function startTimer(){
    interval = setInterval(function(){
        jQuery('#contSec').html(second);
        jQuery('#contMin').html(minute);
        second++;
        if(second == 60){
            minute++;
            second=0;
        }
        if(minute == 60){
            hour++;
            minute = 0;
        }
    },1000);
}
     function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
               upIMG =  e.target.result;
               img = 3;
               changeBG(3);
            }
            reader.readAsDataURL(input.files[0]);
        }

    }
});
