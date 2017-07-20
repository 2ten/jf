<?php


class Alliance_CategoryFilter_Block_AttributesNav extends Mage_Core_Block_Template
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('categoryfilter/attributes-nav.phtml');
	}
	
	
	public function getAttributes()
	{
		// Get Helper.
		$helper = $this->_getHelper();
		
		// Get Attribute collections.
		$attributeCollection = $helper->getAttributeCollection();
		
		$attributes = array();
		foreach($attributeCollection as $oneAttribute) {
			// Get Attribute options.
			$options = array();
			$attribute = Mage::getModel('catalog/resource_eav_attribute')->load($oneAttribute->getId());
			$allOptions = $attribute->getSource()->getAllOptions();
			foreach($allOptions as $oneOption) {
				$options[$oneOption['value']] = $oneOption['label'];
			}
			
			
			$code = $oneAttribute->getAttributeCode();
			$attributes[$oneAttribute->getId()] = array(
				'code' => $helper->cleanAttributeCode($code),
				'label' => $oneAttribute->getFrontendLabel(),
				'sort' => $helper->getAtributeOrder($code),
				'options' => $options
			);
		}
		
		return $attributes;
	}

	public function getCategory(){
	
		$helper = $this->_getHelper();
		
		// Filter if exist category.
		$category = Mage::app()->getRequest()->getParam('category');
		if(empty($category)) {
			$mainCategory = $helper->getMainCategory();
		} else {
			$mainCategory = $helper->getCategorybyKey($category);
		}	
		
		return $mainCategory;
	
	}	
	
	private function _getHelper()
	{
		return Mage::helper('categoryfilter');
	}
}

