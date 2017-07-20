<?php

/**
 * Class Alliance_Bioactives_Model_Resource_Relation
 */
class Alliance_Bioactives_Model_Resource_Relation extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('alliance_bioactives/relation', 'id');
    }
}