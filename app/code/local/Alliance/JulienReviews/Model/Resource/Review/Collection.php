<?php

class Alliance_JulienReviews_Model_Resource_Review_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('alliance_julienreviews/review');
    }
}