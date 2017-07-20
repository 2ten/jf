<?php

/**
 * Class Alliance_Bioactives_Block_Adminhtml_Bioactive_Grid_Renderer_Image
 */
class Alliance_Bioactives_Block_Adminhtml_Bioactive_Grid_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Returns HTML to be displayed in grid for image column
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        if ($row->getData($this->getColumn()->getIndex())=="") {
            return '';
        }
        else {
            $html = '<img ';
            $html .= 'id="' . $this->getColumn()->getId() . '" ';
            $html .= 'width="100" ';
            $html .= 'src="' . Mage::getBaseUrl("media") . $row->getData($this->getColumn()->getIndex()) . '"';
            $html .= 'class="grid-image ' . $this->getColumn()->getInlineCss() . '"/>';

            return $html;
        }
    }
}