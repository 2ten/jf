<?php
class Alliance_JulienReviews_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'alliance_julienreviews';
        $this->_controller = 'adminhtml_report';
        $this->_headerText = $this->__('Product Reviews Report');

        parent::__construct();
        $this->removeButton('add');
    }
}