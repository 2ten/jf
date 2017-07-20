<?php


class Alliance_StaffFilter_Block_DestinationsAttributes extends Mage_Core_Block_Template
{
	
	
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('stafffilter/destinations-attributes.phtml');
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
	
	
	private function _getHelper()
	{
		return Mage::helper('stafffilter');
	}
}

