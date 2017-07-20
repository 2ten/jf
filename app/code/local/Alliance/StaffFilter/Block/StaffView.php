<?php


class Alliance_StaffFilter_Block_StaffView extends Mage_Core_Block_Template
{
	private $_currentStaff;
	
	
	protected function _prepareLayout()
	{
		// Page Title.
		$pageTitle = $this->_getPageTitle();
		
		// Set page Title.
		$headBlock = $this->getLayout()->getBlock('head');
		if($headBlock) {
            $title = $this->getCurrentStaff()->getMetaTitle();
			if($title) {
				$headBlock->setTitle($title);
			} else {
				$headBlock->setTitle($pageTitle);
			}
			
			$keyword = $this->getCurrentStaff()->getMetaKeyword();
			if($keyword) {
				$headBlock->setKeywords($keyword);
			}
			
			$description = $this->getCurrentStaff()->getMetaDescription();
            if ($description) {
                $headBlock->setDescription( ($description) );
            }
		}
		
		// show breadcrumbs.
		$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
		$breadcrumbs->addCrumb('home', array('label' => Mage::helper('cms')->__('Home'), 'title' => Mage::helper('cms')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
		$breadcrumbs->addCrumb('page', array('label' => $pageTitle, 'title' => __('Go to ').$pageTitle, 'link' => $this->_getStaffFilterLink()));
		
		
		return parent::_prepareLayout();
	}
	
	
	public function getCurrentStaff()
	{
		if(empty($this->_currentStaff)) {
			$key = Mage::app()->getRequest()->getParam('key');
			$product = Mage::getModel('catalog/product')->loadByAttribute('url_key', $key);
			
			$this->_currentStaff = $product;
		}
		
		return $this->_currentStaff;
	}
	
	public function getStaffMainImage($width, $height)
	{
		$currentStaff = $this->getCurrentStaff();
		return Mage::helper('catalog/image')->init($currentStaff, 'image')->keepAspectRatio(true)->keepFrame(false)->resize($width, $height);
	}
	
	public function getStaffImages($width, $height)
	{
		$currentStaff = $this->getCurrentStaff();
		$mediaGallery = Mage::getModel('catalog/product')->load($currentStaff->getId())->getMediaGalleryImages();
		$mainImage = $currentStaff->getImage();
		
		$images = array();
		foreach($mediaGallery as $oneImage) {
			if($mainImage == $oneImage->getFile()) continue;
			
			$thumbnail = $this->__(Mage::helper('catalog/image')->init($currentStaff, 'thumbnail', $oneImage->getFile())->resize($width, $height));
			$images[$oneImage->getId()] = array(
				'label' => $this->escapeHtml($oneImage->getLabelDefault()),
				'thumb' => $thumbnail,
				'big' => $oneImage->getUrl(),
			);
		}
		
		
		return $images;
	}
	
	public function getStaffSpecialities()
	{
		$attributes = $this->_getHelper()->getAttributeCollection();
		$staff = $this->getCurrentStaff();
		
		$specialities = array();
		foreach($attributes as $oneAttribute) {
			$code = $oneAttribute->getAttributeCode();
			if($staff->getData($code)) {
				$specialities[$code] = $staff->getAttributeText($code);
			}
		}
		
		return $specialities;
	}
	
	public function getStaffIcons()
	{
		// Get helper.
		$helper = $this->_getHelper();
		// Get current staff.
		$staff = $this->getCurrentStaff();
		
		// Get Attribute collections.
		$attributes = $helper->getAttributeCollection();
		$icons = array();
		foreach($attributes as $oneAttribute) {
			if($staff->getData($oneAttribute->getAttributeCode())) {
				$icons[] = $this->getSkinUrl('images/staff/'.$helper->cleanAttributeCode($oneAttribute->getAttributeCode()).'-sm.png');
			}
		}
		
		return $icons;
	}
	
	
	private function _getPageTitle()
	{
		// Load helper.
		$helper = Mage::helper('stafffilter');
		$location = $this->_getHelper()->getCurrentLocation();
		
		return __($helper->getSectionName()." - ".$location['description']." - Staff - ".$this->getCurrentStaff()->getName());
	}
	
	private function _getStaffFilterLink()
	{
		$urlParts = $this->_getHelper()->getUrlParts();
		
		$link = $urlParts['location'];
		return $link;
	}
	
	private function _getHelper()
	{
		return Mage::helper('stafffilter');
	}
}

