<?php

/**
 * Class Alliance_Bioactives_Model_Resource_Bioactive_Collection
 */
class Alliance_Bioactives_Model_Resource_Bioactive_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('alliance_bioactives/bioactive');
    }
}