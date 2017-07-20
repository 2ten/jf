<?php


class Alliance_Appointment_Block_Form extends Mage_Core_Block_Template
{
    public $techs_array;
    public $helper;

    public function __construct()
    {
        $this->getTechniciansWithLocationKey();
        $this->helper = Mage::helper('appointment');
    }

    public function getLocations()
    {
        $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'location');
        $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
        $locations = $attribute->getSource()->getAllOptions();
        return $locations;
    }
    public function getTechniciansWithLocationKey()
    {
        $technicians_array = array();
        $locations = $this->getLocations();
        foreach ($locations as $location) {
            $technicians_array[$location['value']] = array();
            $technicians_collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToSelect('name')
                ->addFieldToFilter(
                    array(
                        array('attribute'=>'location', 'eq'=>$location['value']),
                    )
                );

            foreach ($technicians_collection as $technician) {
                $technicians_array[$location['value']][$technician->getId()] = $technician->getName();
            }
        }
        $this->techs_array = $technicians_array;
        return $technicians_array;
    }

    public function emailExistsForLocation($id = 0)
    {
        $recipient = $this->helper->getEmailRecipient($id);
        if (Zend_Validate::is(trim($recipient), 'EmailAddress'))
        {
            return true;
        }
        return false;
    }
}