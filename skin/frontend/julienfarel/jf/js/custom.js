//promo code position
jQuery(document).ready(function(){
	if(jQuery("#discount-coupon-form").length > 0){
		fixCouponForm();
	}
});
function fixCouponForm() {
	var position = jQuery('button[name="update_cart_action"]').position();
	
	jQuery('#discount-coupon-form').css('top', (position.top+20)+'px');
	setTimeout(fixCouponForm, 10);
}


// price style.

jQuery(document).ready( function() {
	fixPriceStyleRecursive();
});

function fixPriceStyleRecursive() {
	fixPriceStyle();
	setTimeout(fixPriceStyleRecursive, 10);
}
	
	function fixPriceStyle() {
	  jQuery("span.price").each( function() {
	    var element = jQuery(this); 
	    if(element.children().length==0){   
	      var fontsize = (element.css("font-size")).replace("px","");
	      var newfontsize = fontsize*0.6;
	      var newfontsizestyle = newfontsize + "px";
	      var price = element.html();
	      var pricesplit = price.split(".");
	      if(pricesplit[1]==undefined&&!pricesplit[0].match(/[a-z]/i)){
	        if(pricesplit[0].toString().match(/\W+/)){
	          var newprice = pricesplit[0];
	        }else{
	          var newprice = pricesplit[0] + "." + '<span style="vertical-align:super; font-size:'+ newfontsizestyle +'">00</span>';
	        }
	      }else if(pricesplit[1]==undefined&&pricesplit[0].match(/[a-z]/i)){
	        var newprice = pricesplit[0];
	      }else{
	        if(pricesplit[1].match(/[a-z]/i)){
	          var newprice = pricesplit[0]+"."+pricesplit[1];
	        }else{
	          var newprice = pricesplit[0] + "." + '<span style="vertical-align:super; font-size:'+ newfontsizestyle +'">' + pricesplit[1] + "</span>";
	        }
	      }
	      element.html(newprice);
	    }
	  });
	}


//biactive zoom
	jQuery(document).ready(function(){
	  jQuery(".bioactive-icon").click( function() {
	    jQuery(this).children('img').css({
	      "width":"100%",
	      "padding":"0"
	    }); 
	    jQuery(this).children("strong").css({
	      "color":"#ef4923"
	    });
	    jQuery(this).siblings().find(".key-bioactive-image").css({
	      "width":"80%",
	      "padding":"10%"     
	    });
	    jQuery(this).siblings().find("strong").css({
	      "color":"#4f4f4f",      
	    });   
	  });
	});

// rotating message in nav bar
jQuery(document).ready(function(){
	 jQuery('#msg2').hide();
	 setTimeout("displaynext(1)", 6000); 
});
	
function displaynext(i){
	 if(i == "1") {
	    jQuery('#msg1').fadeOut(500); 
	    jQuery('#msg2').fadeIn(2000);   
	    setTimeout('displaynext(2)', 6000);               
	} else {
	    jQuery('#msg2').fadeOut(500); 
	    jQuery('#msg1').fadeIn(2000); 
	    setTimeout('displaynext(1)', 6000);         
	 }
}
//rotating message in nav bar end	

jQuery(document).ready(function(){	
	  jQuery("#estimatelink").click(function(e){	  
	    if(jQuery("#estimate").css("display")=="none"){
	      jQuery("#estimate").show();
	    }else{
	      jQuery("#estimate").hide();
	    } 
	  });	
});
jQuery(document).ready(function(){	
	  jQuery("#estimatelink-head").click(function(e){	  
	    if(jQuery("#estimate").css("display")=="none"){
	      jQuery("#estimate").show();
	    }else{
	      jQuery("#estimate").hide();
	    } 
	  });	
});

jQuery(function($) {
	$(document).ready(function(){
		
		// Keep arrow background on when hovering over child (destinations dropdown)
		$('.choices').hover(function(){$('.dropdown .header').addClass('hoverClass')}, function(){$('.dropdown .header').removeClass('hoverClass')});
		 		
		// Show subscription form on click (internal pages)
		$('.toggleSubscribe').on('click', function() {
		    $('.toggleSubscribe').animate({width: "0"}, { duration: 400, queue: false });
		    $('.subscribe-box').animate({width: "460"}, { duration: 400, queue: false });
		});
	
	    // Show breadcrumbs on click  
	    var startWidth = $('.breadcrumbs').outerWidth() - 28;
	    $('.breadcrumbs').css('left', -startWidth);
	   
		$('.breadcrumb-tab').click(function() {
			var it = $(this).data('it') || 1;
			switch ( it ) {
				case 1 :
					$(this).parent().animate({left:0}, {queue:false, duration: 500});
					$('.breadcrumb-tab').css({'background-position': '0 center'});
					break;
				case 2 :
					$(this).parent().animate({left:-startWidth}, {queue: false, duration: 500});
					$('.breadcrumb-tab').css({'background-position': '-28px center'});
					break;
			}
			it++;
			if(it > 2) it = 1;
			$(this).data('it', it);
		});
		
		// Show extra footer on home page 

		/* mmc 2ten removing
		$('.cms-home .more-btn').click(function() {			
			if($(this).hasClass('open')){
				$('.cms-home .footer-extra').animate({height: "0"}, { duration: 400, queue: false });
				$('.cms-home .footer-container').animate({bottom: "0"}, { duration: 400, queue: false });
				$('.cms-home .more-btn').css('backgroundPosition', '0 0');
				$(this).removeClass('open');				
			} else {
				$('.cms-home .footer-extra').animate({height: "55"}, { duration: 400, queue: false });
				$('.cms-home .footer-container').animate({bottom: "55"}, { duration: 400, queue: false });
				$('.cms-home .more-btn').css('backgroundPosition', '0 -48px');
				$(this).addClass('open');
			}
		}); 
		
		
		// Show subscribe form on home page
		$('.cms-home .subscribe-home').click(function() {			
			if($(this).hasClass('open')){
				$('.cms-home .subscribe-box').animate({height: "0"}, { duration: 400, queue: false });	
				$(this).removeClass('open');		
			} else {
				$('.cms-home .subscribe-box').animate({height: "55"}, { duration: 400, queue: false });
				$(this).addClass('open');
			}
		});    

		*/
		
		
		// Initialize lightbox
		$('.popup-gallery').magnificPopup({
			delegate: 'a',
			type: 'image',
			tLoading: 'Loading image #%curr%...',
			mainClass: 'mfp-img-mobile',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			},
			image: {
				tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
			}
		});
		
		$('a.youtube').YouTubePopup({
			useYouTubeTitle: false,
			hideTitleBar:true,
			clickOutsideClose:true
		});
		
			
		// Initialize responsive nav - REMOVED UNTIL SITE IS MADE RESPONSIVE
		$('.flexnav').flexNav({
			'animationSpeed' : 500,
			'hoverIntent': true,
			'hoverIntentTimeout': 300,
			'transitionOpacity': true 			
		});

		// Initialize background slider
		$('#maximage_destinations').maximage(); 
		
		// PASv0.1		
		var pSlider = jQuery('#maximage');
		var pImages = pSlider.find('img');
		var pSlideF = jQuery("<div id='pSlideF' class='pSlide' style='background-image: url("+pImages.eq(0).attr('src')+")'></div>");
		var pSlideB = jQuery("<div id='pSlideB' class='pSlide' style='background-image: url("+pImages.eq(0).attr('src')+")'></div>");
		pSlider.append(pSlideF).append(pSlideB);
		
		// Settings. Could be done in css as well.
		pImages.hide();
		pSlider.css({
			width: '100%',
			height: '100%',
			position: 'fixed'
		});
		var pTimeout = 5000; //ms
		var pI = 1; // iterator
		var pIterate = function(){
			// get first div, get second div
			pSlideB.css("background-image", 'url('+pImages.eq(pI).attr("src")+')');
			pSlideF.animate({opacity: 0.3},400, function() { 
				jQuery(this).css("background-image", 'url('+pImages.eq(pI++).attr("src")+')');
				pI = pI >= pImages.length ? 0 : pI;
				
			}).animate({opacity: 1},400);
		}
		var pAction = setInterval(pIterate, pTimeout);
		jQuery('.action-block').hover(function(){
			// Pause
			var x_get_class = $(this).attr('id');
			var z_elem = x_get_class.split("_");
			var x_elem = parseInt(z_elem[1], 10);
			pSlideB.css("background-image", 'url('+pImages.eq(x_elem-1).attr("src")+')');
			pSlideF.css("background-image", 'url('+pImages.eq(x_elem-1).attr("src")+')');
			clearInterval(pAction);
			}, function(){
			// Play	
			pAction = setInterval(pIterate, pTimeout);
			});	
		// PASv0.1 ends.
		// Helper function to Fill and Center the HTML5 Video
//		jQuery('.html5video').maximage('maxcover');
	

	
	
		// Adding in support for $.browser, which has been deprecated
		var matched, browser;

		jQuery.uaMatch = function( ua ) {
		    ua = ua.toLowerCase();
		
		    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
		        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
		        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
		        /(msie) ([\w.]+)/.exec( ua ) ||
		        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
		        [];
		
		    return {
		        browser: match[ 1 ] || "",
		        version: match[ 2 ] || "0"
		    };
		};
		
		matched = jQuery.uaMatch( navigator.userAgent );
		browser = {};
		
		if ( matched.browser ) {
		    browser[ matched.browser ] = true;
		    browser.version = matched.version;
		}
		
		// Chrome is Webkit, but Webkit is also Safari.
		if ( browser.chrome ) {
		    browser.webkit = true;
		} else if ( browser.webkit ) {
		    browser.safari = true;
		}
		
		jQuery.browser = browser;
	
	}); // document ready end
	
}); // jquery end


// jf blog position
jQuery(document).ready(function() {
	jQuery('#blog-product-container').hide();
	var productgrid = jQuery('#blog-product-container').html();
	jQuery(productgrid).appendTo('.post .post-content .entry-content');
	//alert(productgrid);
});