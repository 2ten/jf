<?php

class Alliance_Ajaxcart_Block_FreeSamples extends Mage_Core_Block_Template
{
	public function getProductCollection()
	{
		$freeSampleCategoryId = Mage::helper('ajaxcart')->getFreesampleCategoryId();
		
		$category = Mage::getModel('catalog/category')->load($freeSampleCategoryId);
		$productCollection = $category
			->getProductCollection()
			->addAttributeToSelect('*')
			->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
			// ->addAttributeToSort('name', 'ASC')
		;
		
		// Filter out of stock items.
		$productCollection->getSelect()->joinLeft(
			array('stock' => 'cataloginventory_stock_item'),
			"e.entity_id = stock.product_id",
			array('stock.is_in_stock')
		)->where('stock.is_in_stock = 1');
		
		
		return $productCollection;
	}
	
	public function getSummaryCount()
	{
		if ($this->getData('summary_qty')) {
			return $this->getData('summary_qty');
		}
		return Mage::getSingleton('checkout/cart')->getSummaryQty();
	}
	
	public function checkProductInCart($productId)
	{
		$quote = Mage::getSingleton('checkout/session')->getQuote();
		return $quote->hasProductId($productId);
	}
	
	public function freeSampleEnabled()
	{
		return Mage::helper('ajaxcart')->getFreesampleEnabled();
	}
}

