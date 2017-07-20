var filterstatus = "no-hero"; // mmc removing hero
var $grid;

jQuery(function() {
	
	// Add action to reset filter.
	 jQuery('#reset-all-filter').click(function( e ) {
		e.preventDefault();
		jQuery('.categoryfilter-attributes .toggle.clicked').click();
	});	

	// Add action to side bar menu (Attributes)
	jQuery('.categoryfilter-attributes .toggle').click(function(e) {
		e.preventDefault();
		
		var link = jQuery(this);
		var currentOption = link.next();
		
		var clicked = jQuery('.categoryfilter-attributes').find('.clicked');
		if(clicked != undefined) clicked.removeClass('clicked');
		
		// Remove all previous filters.
		jQuery('.option-clicked').removeClass('option-clicked');
		
		if(! currentOption.is(':visible')) {
			jQuery('.service-options').each(function() {
				var option = jQuery(this);
				if(option != currentOption && option.is(':visible')) {
					option.slideUp();
				}
			});
			
			currentOption.slideDown();
			link.addClass('clicked');
			filterstatus = "no-hero";
			filter(filterstatus);
		} else {
			currentOption.slideUp();
			filterstatus = "hero";
			filter(filterstatus);
		}
	});
	
	
	// Add action to attributes link.
	jQuery('.service-options a').click(function(e) {
		e.preventDefault();
		var link = jQuery(this);
		activateOption(link);
		filterstatus = "no-hero";
		filter(filterstatus);
	});
	
	
	function activateOption(link) {
		link.toggleClass('option-clicked');
	}
	
	/* mmc remove
	jQuery("#products-list .product").hover(
	function(){
		jQuery(".quick-view-container",this).css("display","block");
	}, function(){
		jQuery(".quick-view-container",this).css("display","none");
	});	
	*/
});


jQuery(window).load(function() {
	// *** Shuffle *** //
	$grid = jQuery('#products-list')
	$grid.shuffle({
		itemSelector: '.product'
	});
	//setDefaultFilters(); Not using this function
	filter(filterstatus);
	// *** Shuffle *** //
});



// *** Shuffle *** //
function filter(status) {
	
	restyleAfterfilter(status);

	jQuery('.products-list-empty').hide();
	
	var activeFilters = new Array();
	// Check for individual filters.
	if(jQuery('.option-clicked').length > 0) {
		jQuery('.option-clicked').each(function() {
			var jElement = jQuery(this);
			activeFilters.push(jElement.parents('.service-options').data('attribute')+'-'+jElement.data('option'));
		});
	} else {
		// Active all options of attribute.
		if(jQuery('.categoryfilter-attributes .clicked').length > 0) {
			jQuery('.categoryfilter-attributes .clicked').next().find('a').each(function() {
				var jElement = jQuery(this);
				activeFilters.push(jElement.parents('.service-options').data('attribute')+'-'+jElement.data('option'));
			});
		}
	}
	
	var showAnyItem = false;
	if(activeFilters != undefined && activeFilters.length > 0) {
		$grid.shuffle('shuffle', function($el) {
			var elementsGroup = $el.data().groups;
			if(elementsGroup != undefined && elementsGroup.length > 0) {
				var showItem = false;
				for(var i = 0; i < elementsGroup.length; i++) {
					if(isFilterActive(elementsGroup[i], activeFilters)) {
						showItem = true;
						break;
					}
				}
				
				if(showItem) showAnyItem = true;
				return showItem;
			}
		});
		
		if(jQuery('.categoryfilter-attributes .clicked').data('sort') != 'default') {
			$grid.shuffle('sort', {
				// reverse: true,
				by: function($el) {
					return $el.data('experience');
				}
			});
		} else {
			$grid.shuffle('sort');
		}
		
		if(!showAnyItem) jQuery('.products-list-empty').fadeIn();
	} else {
		$grid.shuffle('shuffle', 'all');
		$grid.shuffle('sort');
	}
}

function restyleAfterfilter(status){
	if(status=="hero"){
		jQuery('#products-list .product:nth-child(4)').addClass("hero");
	}else{
		jQuery('#products-list .product:nth-child(4)').removeClass("hero");
	}
	
	var detection = (window.location.pathname).split("/");
	if(detection.size()==3&&detection[1]=="shop"&&(detection[2].match(/[a-z]/i))){
		jQuery('#products-list .product:eq(3)').removeClass("hero");
	}else{
		//jQuery('#products-list .product:eq(3)').addClass("hero");
	}	
}

function isFilterActive(element, filterArray) {
	for(i = 0; i < filterArray.length; i++) {
		if(filterArray[i] == element) return true;
	}	
	return false;
}

function setDefaultFilters() {
	if(window.location.search != "") {
		var params = window.location.search.substr(1).split('&');
		for(var i = 0; i < params.length; i++) {
			var attributeOption = params[i].split('=');
			
			if(attribute == undefined) {
				var attribute = attributeOption[0].replace('[]', '');
				var attributeLink = jQuery('.categoryfilter-attributes .'+attribute+' .toggle');
				attributeLink.click();
			}
			var optionId = attributeOption[1];
			activateOption(attributeLink.next('.service-options').find('[data-option='+optionId+']'));
		}
	}
}
// *** Shuffle *** //

jQuery(document).ready(function(){
	setTimeout(function(){
		jQuery('.loading-hover').hide();
	},800);
})
