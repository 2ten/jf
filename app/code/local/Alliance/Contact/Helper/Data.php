<?php
class Alliance_Contact_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getSenderEmailIdentity($location)
    {
        return Mage::getStoreConfig('contact_form/'.$location.'/sender_email_identity');
    }

    public function getEmailTemplate($location)
    {
        return Mage::getStoreConfig('contact_form/'.$location.'/email_template');
    }

    public function getEmailRecipient($location)
    {
        return Mage::getStoreConfig('contact_form/'.$location.'/contact_email');
    }

    public function getContactLabel($location)
    {
        return Mage::getStoreConfig('contact_form/'.$location.'/contact_label');
    }
}