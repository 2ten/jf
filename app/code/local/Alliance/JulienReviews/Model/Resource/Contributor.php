<?php

class Alliance_JulienReviews_Model_Resource_Contributor extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('alliance_julienreviews/contributor', 'id');
    }
}