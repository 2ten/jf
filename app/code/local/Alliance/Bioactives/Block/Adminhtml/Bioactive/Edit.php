<?php

/**
 * Class Alliance_Bioactives_Block_Adminhtml_Bioactive_Edit
 */
class Alliance_Bioactives_Block_Adminhtml_Bioactive_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'alliance_bioactives';
        $this->_controller = 'adminhtml_bioactive';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save Bioactive'));
        $this->_updateButton('delete', 'label', $this->__('Delete Bioactive'));
    }

    /**
     * Returns header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        $bioactive = Mage::registry('alliance_bioactives');
        if ($bioactive->getId()) {
            return $this->__('Edit Bioactive');
        }
        else {
            return $this->__('New Bioactive');
        }
    }
}