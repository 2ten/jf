<?php

class Alliance_CategoryFilter_Helper_Data extends Mage_Core_Helper_Abstract
{
	const ATTR_CATEGORYFILTER_PREFIX = "product_";
	const SHOP_OUR_PRODUCTS_KEY = "shop";
	
	private $_attributes;
	private $_categories;
	
	
	public function getAttributeCollection()
	{
		if(empty($this->_attributes)) {
			$attributeCollection = Mage::getResourceModel('catalog/product_attribute_collection')
				->addVisibleFilter()
			;
			$attributeCollection
				->addFieldToFilter('is_user_defined', 1)
				->addFieldToFilter('is_visible_on_front', 1)
				->addFieldToFilter('attribute_code', array('like' => $this->getCategoryFilterPrefix()."%"))
			;
			$attributeCollection->getSelect()->order('position', "ASC");
			
			$this->_attributes = $attributeCollection;
		}
		
		return $this->_attributes;
	}
	
	public function getCategoriesNav()
	{
		if(empty($this->_categories)) {
			$mainCategory = $this->getMainCategory();
			if(empty($mainCategory)) return false;
			
			$topCategories = Mage::getModel('catalog/category')
				->getCollection()
				->addAttributeToFilter('parent_id', $mainCategory->getId())
				->addFieldToFilter('is_active', 1)
				->addAttributeToSelect('name')
			;
			$categories = array();
			foreach($topCategories as $_topCategory) {
				$topId = $_topCategory->getId();
				
				// Get child categories.
				$childCategories = Mage::getModel('catalog/category')
					->getCollection()
					->addAttributeToFilter('parent_id', $topId)
					->addFieldToFilter('is_active', 1)
					->addAttributeToSelect('name')
					->addAttributeToSelect('url_key')
				;
				$childs = array();
				foreach($childCategories as $_child) {
					$childs[$_child->getUrlKey()] = $_child->getName();
				}
				
				$categories[$topId] = array(
					'title' => $_topCategory->getName(),
					'childs' => $childs,
				);
			}
			
			$this->_categories = $categories;
		}
		
		return $this->_categories;
	}
	
	
	public function cleanAttributeCode($code)
	{
		return $code;
	}
	
	public function getAtributeOrder($code)
	{
		return "default";
	}
	
	public function getMainCategory()
	{
		$key = $this->getMainCategoryKey();
		$category = $this->getCategorybyKey($key);
		
		return $category;
	}
	
	public function getCategorybyKey($key)
	{
		$category = Mage::getModel('catalog/category')
			->getCollection()
			->addAttributeToFilter('url_key', $key)
			->getFirstItem()
		;
		
		return $category;
	}
	
	
	public function getCategoryFilterPrefix()
	{
		return self::ATTR_CATEGORYFILTER_PREFIX;
	}
	public function getMainCategoryKey()
	{
		return self::SHOP_OUR_PRODUCTS_KEY;
	}
	public function getStaffCategoryKey()
	{
		return self::STAFF_CATEGORY_KEY;
	}
}

