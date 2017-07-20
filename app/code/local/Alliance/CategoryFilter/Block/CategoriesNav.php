<?php


class Alliance_CategoryFilter_Block_CategoriesNav extends Mage_Core_Block_Template
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('categoryfilter/categories-nav.phtml');
	}
	
	public function getCategories()
	{
		
		return $this->_getHelper()->getCategoriesNav();
	}
	
	public function moduleUrl()
	{
		// return Mage::getUrl('categoryfilter');
		return '/shop/';
	}
	
	
	private function _getHelper()
	{
		return Mage::helper('categoryfilter');
	}
}

