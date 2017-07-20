<?php

/**
 * Class Alliance_Bioactives_Block_Adminhtml_Bioactive
 */
class Alliance_Bioactives_Block_Adminhtml_Bioactive extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'alliance_bioactives';
        $this->_controller = 'adminhtml_bioactive';
        $this->_headerText = $this->__('Key Bioactives');

        parent::__construct();
    }
}