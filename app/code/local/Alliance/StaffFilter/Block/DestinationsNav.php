<?php


class Alliance_StaffFilter_Block_DestinationsNav extends Mage_Core_Block_Template
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('stafffilter/destinations-nav.phtml');
	}
	
	public function getNavLocations()
	{
		return $this->_getHelper()->getLocations();
	}
	
	public function getSelectedLocation()
	{
		$location = $this->_getHelper()->getCurrentLocation();
		return $location['description'];
	}
	
	public function getNavPages()
	{
		// Load helper.
		$helper = $this->_getHelper();
		
		// Get Page Key.
		$urlParts = $helper->getUrlParts();
		$pageKey = $urlParts['page'];
		
		return $helper->getPages($pageKey);
	}
	
	
	private function _getHelper()
	{
		return Mage::helper('stafffilter');
	}
}

