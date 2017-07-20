<?php

class Alliance_StaffFilter_Helper_Data extends Mage_Core_Helper_Abstract
{
	const STAFF_CATEGORY_KEY = "staff";
	const ATTR_LOCATION = "location";
	const ATTR_EXPERIENCE = "experience_level";
	const ATTR_EXPERIENCE_ORDERED = "stylist_specialty,colorist_specialty";
	const ATTR_CATEGORYFILTER_PREFIX = "product_";
	const SECTION_NAME = "Destinations";
	const SECTION_URL_KEY = "locations";
	const OVERVIEW_URL_KEY = "overview";
	const PAGE_DESCRIPTIONS = "Overview,Services,Staff,Appointments"; // Separated by commas.
	const APPOINTMENTS_EXTERNAL_URL = "http://www.secure-booker.com/julienfarel/MakeAppointment/Search.aspx";
	const APPOINTMENTS_LOCAL_URL = "appointment";
	
	private $_locations;
	private $_urlParts;
	private $_pages;
	private $_attributes;
	
	
	public function getLocations()
	{
		if(empty($this->_locations)) {
			$attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', self::ATTR_LOCATION);
			$attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
			$locations = $attribute->getSource()->getAllOptions();
			
			$locationsProcessed = array();
			foreach($locations as $oneLocation) {
				if(empty($oneLocation['value'])) continue;
				
				$locationsProcessed[$oneLocation['value']] = array(
					'value' => $oneLocation['value'],
					'description' => trim(Mage::helper('core')->stripTags($oneLocation['label'])),
					'url-key' => $this->_getLocationUrlKey($oneLocation['label']),
				);
			}
			
			$this->_locations = $locationsProcessed;
		}
		
		return $this->_locations;
	}
	
	public function getCurrentLocation()
	{
		// Get Location Key.
		$urlParts = $this->getUrlParts();
		$locationKey = $urlParts['location'];
		
		// Get Location Description.
		$locations = $this->getLocations();
		foreach($locations as $oneLocation) {
			if($this->_getLocationUrlKey($locationKey) == $oneLocation['url-key']) return $oneLocation;
		}
		
		return false;
	}
	
	public function getUrlParts()
	{
		if(empty($this->_urlParts)) {
			$requestUri = Mage::app()->getRequest()->getRequestUri();
			
			if(preg_match('/locations\-([a-z\-0-9]+)\-([a-z]+)/', $requestUri, $matches)) {
				$this->_urlParts = array(
					'location' => $matches[1],
					'page' => $matches[2],
				);
			}
		}
		
		return $this->_urlParts;
	}
	
	public function getPages($currentPage = null)
	{	
		if(empty($this->_pages)) {
			$pagesArray = explode(',', self::PAGE_DESCRIPTIONS);
			foreach($pagesArray as $onePage) {
				$urlKey = $this->_getPageUrlKey($onePage);
				if(! $urlKey) continue;
				
				$this->_pages[] = array(
					'description' => $onePage,
					'url-key' => $urlKey,
					'external' => (strpos($urlKey, "http://") !== false),
					'selected' => (! is_null($currentPage) && $currentPage == Mage::getModel('catalog/product_url')->formatUrlKey($onePage)),
				);
			}
		}
		
		return $this->_pages;
	}
	
	public function getSectionName()
	{
		return self::SECTION_NAME;
	}
	
	public function getLocationKey()
	{
		return self::ATTR_LOCATION;
	}
	public function getExperienceKey()
	{
		return self::ATTR_EXPERIENCE;
	}
	public function getCategoryFilterPrefix()
	{
		return self::ATTR_CATEGORYFILTER_PREFIX;
	}
	
	public function getExcludedAttributes()
	{
		$excluded = array(
			$this->getLocationKey(),
			'fixed_video',
			'gallery_video',
			'highlight_review',
		);
		
		return $excluded;
	}
	
	public function getStaffCategory()
	{
		$category = Mage::getModel('catalog/category')->loadByAttribute('url_key', self::STAFF_CATEGORY_KEY);
		
		return $category;
	}
	
	public function getAttributeCollection()
	{
		if(empty($this->_attributes)) {
			$attributeCollection = Mage::getResourceModel('catalog/product_attribute_collection')
				->addVisibleFilter()
			;
			$attributeCollection
				->addFieldToFilter('is_user_defined', 1)
				->addFieldToFilter('is_visible_on_front', 1)
				->addFieldToFilter('attribute_code', array('nin' => $this->getExcludedAttributes()))
				->addFieldToFilter('attribute_code', array('nlike' => $this->getCategoryFilterPrefix()."%"))
				// ->addFieldToFilter('attribute_code', array('neq' => $this->getExperienceKey()))
			;
			$attributeCollection->getSelect()->order('position', "ASC");
			
			$this->_attributes = $attributeCollection;
		}
		
		return $this->_attributes;
	}
	
	public function cleanAttributeCode($code)
	{
		$code = str_replace('_service', '', $code);
		$code = str_replace('_specialty', '', $code);
		$code = str_replace('_level', '', $code);
		return $code;
	}
	
	public function getAtributeOrder($code)
	{
		if((strpos(self::ATTR_EXPERIENCE_ORDERED, $code) !== false)) return "experience";
		return "default";
	}
	
	
	private function _getPageUrlKey($page)
	{
		$urlParts = $this->getUrlParts();
		$locationKey = $urlParts['location'];
		
		if($page == "Staff" && strpos($locationKey, "cabo") !== false) return false;
		
		if($page == "Appointments") {
			if(strpos($locationKey, "restore") !== false) $url = self::APPOINTMENTS_EXTERNAL_URL;
			else $url = '/'.self::APPOINTMENTS_LOCAL_URL;
		} else $url = '/'.Mage::getModel('catalog/product_url')->formatUrlKey(self::SECTION_URL_KEY.'-'.$locationKey.'-'.$page);
		
		return $url;
	}
	
	private function _getLocationUrlKey($label)
	{
		if(preg_match('/\<(.*)\>/', $label, $matches)) {
			return $matches[1];
		} else {
			$label = str_replace('&', 'and', $label);
			$label = preg_replace('/\(.*\)/', '', $label);
			return '/'.Mage::getModel('catalog/product_url')->formatUrlKey(self::SECTION_URL_KEY.'-'.$label.'-'.self::OVERVIEW_URL_KEY);
		}
	}
}

