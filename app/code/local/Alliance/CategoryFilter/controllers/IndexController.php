<?php


class Alliance_CategoryFilter_IndexController extends Mage_Core_Controller_Front_Action
{
	public function preDispatch()
	{
		parent::preDispatch();

		$helper = $this->_getHelper();
		$shopCategory = $helper->getMainCategory();
		
		// Filter if exist category.
		$category = Mage::app()->getRequest()->getParam('category');
		if(empty($category)) {
			$mainCategory = $shopCategory;
		} else {
			$mainCategory = $helper->getCategorybyKey($category);
			
			// Checking category heirarchy.
			if(! in_array($shopCategory->getId(), $mainCategory->getPathIds())) {
				$mainCategory->setId(false);
			}
		}
		
		if( ! $mainCategory->getId()) {
			$this->norouteAction();
		} else {
			Mage::register('mainCategory', $mainCategory);
		}
	}
	
	
	public function indexAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}
	
	
	private function _getHelper()
	{
		return Mage::helper('categoryfilter');
	}
}
