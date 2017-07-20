jQuery(function() {
	// Add action to side bar menu (Attributes)
	jQuery('.destination-attributes .toggle').click(function(e) {
		e.preventDefault();
		
		var link = jQuery(this);
		var currentOption = link.next();
		
		var clicked = jQuery('.destination-attributes').find('.clicked');
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
		} else {
			currentOption.slideUp();
		}
	});
	
	// Add action to attributes link.
	jQuery('.service-options a').click(function(e) {
		e.preventDefault();
		
		activateOption(jQuery(this));
		
		var selectedFilters = "";
		if(jQuery('.option-clicked').length > 0) {
			jQuery('.option-clicked').each(function() {
				var jElement = jQuery(this);
				
				if(attribute == undefined) var attribute = jElement.parents('.service-options').data('attribute');
				
				if(selectedFilters != "") selectedFilters += '&'
				selectedFilters += attribute+'[]='+jElement.data('option');
			});
			
			selectedFilters = '?'+selectedFilters
		}
		
		// Get path.
		var pathname = location.href.substr(0, location.href.length-2);
		pathname = pathname.substr(0, pathname.lastIndexOf('/'));
		
		// Redirect.
		location.href = pathname+selectedFilters;
	});

	
	// Preload selection.
	if(window.location.search != "") {
		var params = window.location.search.substr(1).split('&');
		for(var i = 0; i < params.length; i++) {
			var attributeOption = params[i].split('=');
			
			if(attribute == undefined) {
				var attribute = attributeOption[0].replace('[]', '');
				var attributeLink = jQuery('.destination-attributes .'+attribute+' .toggle');
				attributeLink.click();
			}
			var optionId = attributeOption[1];
			activateOption(attributeLink.next('.service-options').find('[data-option='+optionId+']'));
		}
	}
	
	// Fancy.
	jQuery('.fancybox').fancybox();
	
	
	function activateOption(link) {
		link.toggleClass('option-clicked');
	}
});
