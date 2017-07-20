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
			filter();
		} else {
			currentOption.slideUp();
			filter();
		}
	});
	
	// Add action to attributes link.
	jQuery('.service-options a').click(function(e) {
		e.preventDefault();
		
		var link = jQuery(this);
		activateOption(link);
		filter();
	});
	
	// Add action to reset filter.
	jQuery('#reset-all-filter').click(function(e) {
		e.preventDefault();
		
		jQuery('.destination-attributes .toggle.clicked').click();
	});
	
	// Add action to person.
	jQuery('.person').click(function(e) {
		e.preventDefault();
		
		var selectedFilters = "";
		if(jQuery('.option-clicked').length > 0) {
			jQuery('.option-clicked').each(function() {
				var jElement = jQuery(this);
				
				if(attribute == undefined) var attribute = jElement.parents('.service-options').data('attribute');
				
				if(selectedFilters != "") selectedFilters += '&';
				selectedFilters += attribute+'[]='+jElement.data('option');
			});
			
			selectedFilters = '?'+selectedFilters
		} else if(jQuery('.destination-attributes .clicked').length > 0) {
			selectedFilters = '?'+jQuery('.destination-attributes .clicked').next().data('attribute')+'=0';
		}
		
		
		location.href = jQuery(this).data('url')+selectedFilters;
	});
	
	
	function activateOption(link) {
		link.toggleClass('option-clicked');
	}
	
	
	// *** Shuffle *** //
	var $grid = jQuery('#staff-list');
	$grid.shuffle({
		itemSelector: '.person'
	});
	setDefaultFilters();
	filter();
	
	
	function filter() {
		jQuery('.staff-list-empty').hide();
		
		var activeFilters = new Array();
		// Check for individual filters.
		if(jQuery('.option-clicked').length > 0) {
			jQuery('.option-clicked').each(function() {
				var jElement = jQuery(this);
				activeFilters.push(jElement.parents('.service-options').data('attribute')+'-'+jElement.data('option'));
			});
		} else {
			// Active all options of attribute.
			if(jQuery('.destination-attributes .clicked').length > 0) {
				jQuery('.destination-attributes .clicked').next().find('a').each(function() {
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
			
			if(jQuery('.destination-attributes .clicked').data('sort') != 'default') {
				$grid.shuffle('sort', {
					// reverse: true,
					by: function($el) {
						return $el.data('experience');
					}
				});
			} else {
				$grid.shuffle('sort');
			}
			
			if(!showAnyItem) jQuery('.staff-list-empty').fadeIn();
		} else {
			$grid.shuffle('shuffle', 'all');
			$grid.shuffle('sort');
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
					var attributeLink = jQuery('.destination-attributes .'+attribute+' .toggle');
					attributeLink.click();
				}
				var optionId = attributeOption[1];
				activateOption(attributeLink.next('.service-options').find('[data-option='+optionId+']'));
			}
		}
	}
	// *** Shuffle *** //
});

