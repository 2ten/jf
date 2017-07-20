<?php

class Alliance_JulienReviews_Model_Review extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('alliance_julienreviews/review');
    }

    public function fetchCustomerName()
    {
        if ($model = Mage::getModel('customer/customer')->load($this->getCustomerId())) {
            return $model->getFirstname();
        }
        else {
            return NULL;
        }
    }
}