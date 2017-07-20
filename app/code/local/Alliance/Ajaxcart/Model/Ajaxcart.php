<?php


class Alliance_Ajaxcart_Model_Ajaxcart extends Mage_Core_Model_Abstract
{
	private $_newItemsQty;
	
	public function _construct()
	{
		parent::_construct();
		$this->_init('ajaxcart/ajaxcart');
	}
	
	
	public function addProduct($product, $qty, $options, $extraParams = null)
	{
		$cart = $this->_getCart();
		
		// Check Maximum Qty Allowed in Shopping Cart.
		if(! $this->_checkMaxSaleQty($product, $qty)) {
			throw new Exception('The amount selected for the product exceeds the maximum allowed for purchase.');
			return false;
		}
		
		// Check for FreeSample.
		$helper = $this->_getHelper();
		if($helper->getFreesampleEnabled() && $helper->checkFreesample($product)) {
			// Check item in cart.
			if($helper->checkProductInCart($product->getId())) {
				throw new Exception('The Free Sample product already been added to cart.');
				return false;
			}
			
			// Check freesamples limit.
			if($helper->checkFreesampleLimit()) {
				throw new Exception('You can only select up to '.$helper->getFreesampleLimit().' free samples per order.');
				return false;
			}
			
			// Force freesample qty to 1.
			$qty = 1;
		}
		
		// Mage::log("helperaddProduct: productId: ".$product->getId()." - ".$qty." - name: ".$product->getName(), null, 'ajaxcart.log');
		$params = array('qty' => $qty, 'super_attribute' => $options);
		if(! empty($extraParams)) $params += $extraParams;
		$cart->addProduct($product, $params);
		$cart->save();
		if(! $cart->getQuote()->getHasError()) {
			return true;
		}
		
		return false;
	}
	
	public function updateProduct($product, $qty, $options)
	{
		$quote = $this->_getSession()->getQuote();
		$cart = $this->_getCart();
		
		// Get item to change.
		$item = $quote->getItemByProduct($product);
		if(! $item->getId()) {
			throw new Exception('The product does not exist in the cart.');
			return;
		}
		
		// Check Maximum Qty Allowed in Shopping Cart.
		if(! $this->_checkMaxSaleQty($product, $qty)) {
			throw new Exception('The amount selected for the product exceeds the maximum allowed for purchase.');
			return;
		}
		
		// Check for FreeSample.
		$helper = $this->_getHelper();
		if($helper->getFreesampleEnabled() && $helper->checkFreesample($product)) {
			// Force freesample qty to 1.
			$qty = 1;
		} else {
			$qty = ($item->getQty() + $qty);
		}
		
		// Update item.
		try {
			$cartData = array($item->getId() => array('qty' => $qty, 'super_attribute' => $options));
			$cartData = $cart->suggestItemsQty($cartData);
			
			$cart->updateItems($cartData);
			$cart->save();
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			return;
		}
	}
	
	public function removeItem($itemId)
	{
		$helper = $this->_getHelper();
		$cart = $this->_getCart();
		
		// Delete item.
		try {
			$cart->removeItem($itemId);
			$cart->save();
			$this->_getSession()->setCartWasUpdated(true);
			
			if(! $cart->getQuote()->getHasError()) {
				// Check if only samples.
				$freesampleCount = $helper->getFreesampleCount();
				$itemCartCount = $cart->getItemsCount();
				
				if($freesampleCount == $itemCartCount) {
					$_items = $helper->getRealItemsCollection();
					foreach($_items as $_item) {
						$cart->removeItem($_item->getId());
						$cart->save();
						$this->_getSession()->setCartWasUpdated(true);
					}
				}
				
				return true;
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			return false;
		}
	}

	public function updateCart($cartData)
	{
		if(empty($cartData) || ! is_array($cartData)) {
			throw new Exception('Cart data is empty.');
			return;
		}
		
		$filter = new Zend_Filter_LocalizedToNormalized(
			array('locale' => Mage::app()->getLocale()->getLocaleCode())
		);
		
		foreach($cartData as $index => $data) {
			if (isset($data['qty'])) {
				$cartData[$index]['qty'] = $filter->filter(trim($data['qty']));
				
				// Check Maximum Qty Allowed in Shopping Cart.
				$item = Mage::getModel("sales/quote_item")->load($index);
				$currentQty = $item->getQty();
				$qty = $cartData[$index]['qty'] - $currentQty;
				$productId = $item->getProductId();
				$product = $this->_getHelper()->initProduct($productId);
				if(! $this->_checkMaxSaleQty($product, $qty)) {
					throw new Exception('The amount selected for one product exceeds the maximum allowed for purchase. The cart was not updated.');
					return false;
				}
			}
		}
		
		$cart = $this->_getCart();
		if(! $cart->getCustomerSession()->getCustomer()->getId() && $cart->getQuote()->getCustomerId()) {
			$cart->getQuote()->setCustomerId(null);
		}
		
		try {
			$cartData = $cart->suggestItemsQty($cartData);
			$cart->updateItems($cartData)->save();
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			return false;
		}
	}
	
	public function emptyCart()
	{
		$cart = $this->_getCart();
		
		try {
			$cart->truncate()->save();
			$this->_getSession()->setCartWasUpdated(true);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			return;
		}
	}
	
	
	public function checkFreesamples()
	{
		// Load helper
		$helper = $this->_getHelper();
		
		if($helper->getFreesampleEnabled()) {
			// Load cart.
			$cart = $this->_getCart();
			$items = $cart->getQuote()->getItemsCollection();
			$this->_newItemsQty = $cart->getSummaryQty();
			if(count($items) > 1) {
				$freesamplesItems = array();
				$freesampleCount = 0;
				foreach ($items as $_item) {
					$itemID = $_item->getId();
					if(! empty($itemID)) {
						$product = $_item->getProduct();
						if($helper->checkFreesample($product)) {
							$freesampleCount++;
							
							// Quantity to 1.
							if($_item->getQty() > 1) {
								$this->_newItemsQty -= ($_item->getQty() - 1);
								$this->updateProduct($product, 1);
							}
							
							// Save item in array.
							$freesamplesItems[] = $_item;
						}
					}
				}
				
				// Delete extra free samples.
				$freesampleLimit = $helper->getFreesampleLimit();
				if($freesampleCount > $freesampleLimit) {
					for($i = ($freesampleCount - 1); $i >= $freesampleLimit; $i--) {
						$this->removeProduct($freesamplesItems[$i]->getProduct());
						$this->_newItemsQty--;
					}
				}
			}
		}
		
		return true;
	}
	
	public function getNewItemsQty()
	{
		// return $this->_newItemsQty;
		return $this->_getHelper()->getRealItemsQty();
	}
	
	public function getAjaxcartEnabled()
	{
		return $this->_getHelper()->getAjaxcartEnabled();
	}
	
	
	public function addToWishlist($product, $qt, $options = null)
	{
		// Get Wishlist.
		$wishlist = $this->_getWishlist();
		if(! $wishlist) {
			throw new Exception('No wishlist.');
			return;
		}
		
		// Check product.
		if (! $product->getId() || ! $product->isVisibleInCatalog()) {
			throw new Exception('Cannot specify product.');
			return;
		}
		
		// Insert item in wishlist.
		try {
			$requestParams = array(
				'qty' => $qt,
				'super_attribute' => $options,
			);
            $buyRequest = new Varien_Object($requestParams);
			
			$result = $wishlist->addNewItem($product, $buyRequest);
			if (is_string($result)) {
				throw new Exception('Error inserting wishlist item: '.$e->getMessage());
				return;
			}
			$wishlist->save();
			
			// Dispatch Event.
			Mage::dispatchEvent(
				'wishlist_add_product',
				array(
					'wishlist' => $wishlist,
					'product' => $product,
					'item' => $result
				)
			);
			
			return $wishlist;
		} catch (Exception $e) {
			throw new Exception('Error inserting wishlist item: '.$e->getMessage());
			return;
		}
	}
	
	public function removeToWishlist($itemId)
	{
		// Get Wishlist.
		$wishlist = $this->_getWishlist();
		if(! $wishlist) {
			throw new Exception('No wishlist.');
			return;
		}
		
		// Get Wishlist item.
		$item = Mage::getModel('wishlist/item')->load($itemId);
        if (! $item->getId()) {
			throw new Exception('No item in wishlist.');
			return;
        }
		
		// Delete item.
		try {
			$item->delete();
			$wishlist->save();
			
			return $wishlist;
		} catch (Exception $e) {
			throw new Exception('Error inserting wishlist item: '.$e->getMessage());
			return;
		}
	}
	
	public function addWishlistToCart($itemId)
	{
		// Get Wishlist.
		$wishlist = $this->_getWishlist();
		if(! $wishlist) {
			throw new Exception('No wishlist.');
			return;
		}
		
		// Get Wishlist item.
		$item = Mage::getModel('wishlist/item')->load($itemId);
        if (! $item->getId()) {
			throw new Exception('No item in wishlist.');
			return;
        }
		
		// Get Cart.
		$cart = $this->_getCart();
		
		try {
			$options = Mage::getModel('wishlist/item_option')->getCollection()->addItemFilter(array($itemId));
			$item->setOptions($options->getOptionsByItem($itemId));
			
			// $session = $this->_getCustomerSession();
			// if ($session->getBeforeWishlistRequest()) {
                // $requestParams = $session->getBeforeWishlistRequest();
                // $session->unsBeforeWishlistRequest();
            // }
            // $buyRequest = new Varien_Object($requestParams);
			
			// $item->mergeBuyRequest($buyRequest);
			// if($item->addToCart($cart, true)) {
			
			// $blockWishlistOption = $this->getLayout()->createBlock('wishlist/customer_wishlist_item_options');
			// $options = $blockWishlistOption->setItem($item)->getConfiguredOptions();
			// $options = Mage::helper('catalog/product_configuration')->getConfigurableOptions($item);
			$product = $item->getProduct();
			
			$options = array();
			if($product->getTypeId() == Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE) {
				$attributesOption = $product->getCustomOption('attributes');
				$options = unserialize($attributesOption->getValue());
			}
			
			if($this->addProduct($product, $item->getQty(), $options)) {
				$item->delete();
				$cart->save()->getQuote()->collectTotals();
			}

			$wishlist->save();
			Mage::helper('wishlist')->calculate();
			
			return $wishlist;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			return;
		}
	}
	
	public function moveToWishlist($cartItemId)
	{
		// Get Wishlist.
		$wishlist = $this->_getWishlist();
		if(! $wishlist) {
			throw new Exception('No wishlist.');
			return;
		}
		
		// Get Cart.
		$cart = $this->_getCart();
		$session = $this->_getSession();
		
		try {
			$item = $cart->getQuote()->getItemById($cartItemId);
			if(! $item) {
				throw new Exception("Requested cart item doesn't exist (#".$cartItemId.").");
				return;
			}
			
			$productId = $item->getProductId();
			$buyRequest = $item->getBuyRequest();
			
			// Add item to Wishlist.
			$wishlist->addNewItem($productId, $buyRequest);
			$wishlist->save();
			Mage::helper('wishlist')->calculate();
			
			// Remove item from Cart.
			$cart->getQuote()->removeItem($cartItemId);
			$cart->save();
			
			
			return $wishlist;
		} catch (Exception $e) {
			throw new Exception('Error moving cart in wishlist: '.$e->getMessage());
			return;
		}
	}
	
	
	protected function _checkMaxSaleQty($product, $qty)
	{
		// Get max quantity.
		$stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
		$maxSaleQty = $stock->getMaxSaleQty();
		
		// Get current quantity.
		$quote = $this->_getSession()->getQuote();
		$item = $quote->getItemByProduct($product);
		if($item) $currentQty = $item->getQty();
		else $currentQty = 0;
		
		
		$newQty = $currentQty + $qty;
		
		
		return ($maxSaleQty >= $newQty);
	}

	protected function _getHelper()
	{
		return Mage::helper('ajaxcart');
	}
	protected function _getCart()
	{
		return Mage::getSingleton('checkout/cart');
	}
	protected function _getSession()
	{
		return Mage::getSingleton('checkout/session');
	}
	protected function _getCustomerSession()
	{
		return Mage::getSingleton('customer/session');
	}
	
	protected function _getWishlist()
	{
		$wishlist = Mage::registry('wishlist');
		if($wishlist) {
			return $wishlist;
		}
		
		try {
			$customerId = $this->_getCustomerSession()->getCustomerId();
			
			$wishlist = Mage::getModel('wishlist/wishlist');
			$wishlist->loadByCustomer($customerId, true);
			
			if (! $wishlist->getId() || $wishlist->getCustomerId() != $customerId) {
				$wishlist = null;
				throw new Exception("Requested wishlist doesn't exist: ".$e->getMessage());
			}

			Mage::register('wishlist', $wishlist);
			
			return $wishlist;
		} catch (Exception $e) {
			throw new Exception('Error getting wishlist: '.$e->getMessage());
			return false;
		}

		return false;
	}
}