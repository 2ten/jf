<?php


class Alliance_StaffFilter_Block_StaffFilter extends Mage_Core_Block_Template
{
	protected function _prepareLayout()
	{
		// Page Title.
		$pageTitle = $this->_getPageTitle();
		
		// Set page Title.
		$head = $this->getLayout()->getBlock('head');
		if ($head) {
			$head->setTitle($pageTitle);
		}
		
		// show breadcrumbs.
		$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
		$breadcrumbs->addCrumb('home', array('label' => Mage::helper('cms')->__('Home'), 'title' => Mage::helper('cms')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
		$breadcrumbs->addCrumb('page', array('label' => $pageTitle, 'title' => $pageTitle));
		
		
		return parent::_prepareLayout();
	}
	
	public function getStaff()
	{
		$helper = $this->_getHelper();
		
		$category = $helper->getStaffCategory();
		$location = $helper->getCurrentLocation();
		$staffCollection = $category
			->getProductCollection()
			->addAttributeToSelect('*')
			->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
			->addAttributeToFilter('location', array(
				'like' => '%'.$location['value'].'%',
			))
		;
		
		return $staffCollection;
	}
	
	public function getAttributes()
	{
		// Load helper.
		$helper = $this->_getHelper();
		
		// Load Attribute.
		$attributeCollection = $helper->getAttributeCollection();
		
		$attributes = array();
		foreach($attributeCollection as $oneAttribute) {
			$code = $oneAttribute->getAttributeCode();
			
			// List all attributes less experience attribute.
			$attributes[$oneAttribute->getId()] = array(
				'code' => $code,
				'clean-code' => $helper->cleanAttributeCode($oneAttribute->getAttributeCode()),
				'label' => $oneAttribute->getFrontendLabel()
			);
		}
		
		return $attributes;
	}
	
	public function getExperienceCode()
	{
		return $this->_getHelper()->getExperienceKey();
	}
	
	public function getAttributePosition($attributeId)
	{
		$attributeModel = Mage::getResourceModel( 'eav/entity_attribute_option_collection');
		$attributeModel->addFieldToFilter('option_id', $attributeId);
		
		return $attributeModel->getFirstItem()->getSortOrder();
	}
	
	
	private function _getPageTitle()
	{
		// Load helper.
		$helper = Mage::helper('stafffilter');
		$location = $this->_getHelper()->getCurrentLocation();
		
		return __($helper->getSectionName()." - ".$location['description']." - Staff");
	}
	
	private function _getHelper()
	{
		return Mage::helper('stafffilter');
	}
}

