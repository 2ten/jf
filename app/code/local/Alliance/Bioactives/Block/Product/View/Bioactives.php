<?php

/**
 * Class Alliance_Bioactives_Block_Product_View_Bioactives
 */
class Alliance_Bioactives_Block_Product_View_Bioactives extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * Returns a collection of all enabled key bioactives associated with the currently viewed product
     *
     * @return object Alliance_Bioactives_Model_Resource_Bioactive_Collection
     */
    public function getKeyBioactives()
    {
        $bioactive_ids = explode(',', $this->getProduct()->getData('key_bioactives'));
        $bioactive_collection = Mage::getModel('alliance_bioactives/bioactive')->getCollection();
        $bioactive_collection->addFieldToFilter('id', array(
            'in' => $bioactive_ids,
        ));
        return $bioactive_collection;
    }
}