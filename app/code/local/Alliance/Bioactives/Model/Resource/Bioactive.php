<?php

/**
 * Class Alliance_Bioactives_Model_Resource_Bioactive
 */
class Alliance_Bioactives_Model_Resource_Bioactive extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('alliance_bioactives/bioactive', 'id');
    }
}