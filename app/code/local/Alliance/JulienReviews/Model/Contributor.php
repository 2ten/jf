<?php

class Alliance_JulienReviews_Model_Contributor extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('alliance_julienreviews/contributor');
    }

    public function loadByCustomerId($customer_id)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter('customer_id', $customer_id);
        if ($this->load($collection->getFirstItem()->getId())) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
}