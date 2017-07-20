<?php

/**
 * Class Alliance_Bioactives_Block_Adminhtml_Bioactive_Edit_Form
 */
class Alliance_Bioactives_Block_Adminhtml_Bioactive_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('alliance_bioactives_bioactive_form');
        $this->setTitle($this->__('Bioactive Details'));
    }

    /**
     * Prepares all fields for the Key Bioactives edit/new forms
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('alliance_bioactives');

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data',
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('alliance_bioactives')->__('Bioactive Information'),
            'class'     => 'fieldset-wide',
        ));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name'  => 'id',
            ));
        }

        $fieldset->addField('date', 'hidden', array(
            'label'     => Mage::helper('alliance_bioactives')->__('Date Created'),
        ));

        $fieldset->addField('image', 'image', array(
            'label'     => Mage::helper('alliance_bioactives')->__('Upload Bioactive Image'),
            'required'  => true,
            'name'      => 'image',
        ));

        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => Mage::helper('alliance_bioactives')->__('Bioactive Title'),
            'title'     => Mage::helper('alliance_bioactives')->__('Bioactive Title'),
            'required'  => true,
        ));

        $fieldset->addField('description', 'textarea', array(
            'name'      => 'description',
            'label'     => Mage::helper('alliance_bioactives')->__('Bioactive Description'),
            'title'     => Mage::helper('alliance_bioactives')->__('Bioactive Description'),
            'required'  => true,
        ));

        $fieldset->addField('status', 'select', array(
            'name'      => 'status',
            'label'     => Mage::helper('alliance_bioactives')->__('Status'),
            'title'     => Mage::helper('alliance_bioactives')->__('Status'),
            'values'    => array('Disabled'=>'Disabled','Enabled' => 'Enabled'),
            'required'  => false,
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}