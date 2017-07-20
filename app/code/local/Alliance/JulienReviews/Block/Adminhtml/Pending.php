<?php
class Alliance_JulienReviews_Block_Adminhtml_Pending extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'alliance_julienreviews';
        $this->_controller = 'adminhtml_pending';
        $this->_headerText = $this->__('Pending Product Reviews');

        parent::__construct();
        $this->removeButton('add');
    }
}