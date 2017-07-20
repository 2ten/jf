<?php
class Alliance_JulienReviews_Block_Adminhtml_Review extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'alliance_julienreviews';
        $this->_controller = 'adminhtml_review';
        $this->_headerText = $this->__('All Product Reviews');

        parent::__construct();
        $this->removeButton('add');
    }
}