<?php


class Alliance_CategoryFilter_Block_CategoryFilter extends Mage_Core_Block_Template
{
	private $_productBlock;
	
	protected function _prepareLayout()
	{
		
		return parent::_prepareLayout();
	}
	
	public function getProducts()
	{
		$mainCategory = Mage::registry('mainCategory');
		
		$productCollection = $mainCategory
			->getProductCollection()
			->addAttributeToSelect('*')
			->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
			->addAttributeToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
		;
		
		
		return $productCollection;
	}
	
	public function getCategory(){
	
		$mainCategory = Mage::registry('mainCategory');
		
		return $mainCategory;
	
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
	
	public function getPriceHtml($product)
	{
		return $this->_getProductBlock()->getPriceHtml($product, true);
	}
	
	public function getAddToCartUrl($product, $additional = array())
	{
		
		return $this->_getProductBlock()->getAddToCartUrl($product, $additional);
	}
	
	
	private function _getProductBlock()
	{
		if(empty($this->_productBlock)) {
			$this->_productBlock = $this->getLayout()->getBlockSingleton('catalog/product_list');
		}
		
		return $this->_productBlock;
	}
	
	private function _getHelper()
	{
		return Mage::helper('categoryfilter');
	}
}

