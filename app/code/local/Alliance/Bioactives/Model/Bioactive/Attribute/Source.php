<?php

/**
 * Class Alliance_Bioactives_Model_Bioactive_Attribute_Source
 *
 * This is the source model for the "key_bioactives" product attribute installed
 * in sql/alliance_bioactives_setup/install-1.0.0.php
 */
class Alliance_Bioactives_Model_Bioactive_Attribute_Source extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Returns all <option>s for <select> element of Key Bioactives product attribute
     *
     * Will only return bioactives with Status: Enabled
     *
     * @return array
     */
    public function getAllOptions()
    {
        $key_bioactives_collection = Mage::getModel('alliance_bioactives/bioactive')->getCollection();
        $key_bioactives_collection->addFieldToFilter('status', array(
            'eq' => 'Enabled',
        ));

        $options = array();

        foreach ($key_bioactives_collection as $key_bioactive) {
            $new_option = array(
                'value' => $key_bioactive->getId(),
                'label' => $key_bioactive->getTitle(),
            );
            array_push($options, $new_option);
        }

        return $options;
    }
}
