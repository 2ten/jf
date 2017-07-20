<?php

class Alliance_Appointment_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getTechniciansByLocation( $location )
    {
        $technicians_collection = Mage::getModel( 'catalog/product' )
            ->getCollection()
            ->addAttributeToSelect( 'name' )
            ->addFieldToFilter(
                array(
                    array( 'attribute' => 'location', 'eq' => $location ),
                )
            );

        $technicians_array = array();

        foreach ( $technicians_collection as $technician )
        {
            $technicians_array[$technician->getId()] = $technician->getName();
        }

        return $technicians_array;
    }

    public function getEmailTemplate()
    {
        return Mage::getStoreConfig('appointment/email_settings/email_template');
    }

    public function getEmailSender()
    {
        return Mage::getStoreConfig('appointment/email_settings/sender_email_identity');
    }

    public function getEmailRecipient($id = 0) {
        switch ($id) {
            case 0:
                break;
            case 85:
                $recipient = Mage::getStoreConfig('appointment/email_settings/restore_nyc_recipient');
                break;
            case 86:
                $recipient = Mage::getStoreConfig('appointment/email_settings/fifth_avenue_recipient');
                break;
            case 87:
                $recipient = Mage::getStoreConfig('appointment/email_settings/jf_gymnastique_recipient');
                break;
            case 26:
                $recipient = Mage::getStoreConfig('appointment/email_settings/miami_recipient');
                break;
            case 25:
                $recipient = Mage::getStoreConfig('appointment/email_settings/cabo_recipient');
                break;
            default:
                break;
        }

        return $recipient;
    }
}