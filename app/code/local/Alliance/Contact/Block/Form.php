<?php

class Alliance_Contact_Block_Form extends Mage_Core_Block_Template
{
    public $contacts;

    public function __construct()
    {
        $this->getContacts();
    }
    public function getContacts()
    {
        $contacts = array();
        for ($i = 1; $i <= 10; $i++) {
            $config_data = Mage::getStoreConfig('contact_form/contact_'.$i);
            if ($config_data['enabled'] == '1'
            && Zend_Validate::is(trim($config_data['contact_label']), 'NotEmpty')
            && Zend_Validate::is(trim($config_data['contact_email']), 'NotEmpty')
            && Zend_Validate::is(trim($config_data['contact_email']), 'EmailAddress')) {
                $contacts['contact_'.$i] = $config_data;
            }
        }
        $this->contacts = $contacts;
    }
}