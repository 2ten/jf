<?php

class Alliance_JulienReviews_Model_Resource_Review extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('alliance_julienreviews/review', 'id');
    }
}