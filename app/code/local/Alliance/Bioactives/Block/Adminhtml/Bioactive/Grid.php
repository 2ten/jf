<?php

/**
 * Class Alliance_Bioactives_Block_Adminhtml_Bioactive_Grid
 */
class Alliance_Bioactives_Block_Adminhtml_Bioactive_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultSort('id');
        $this->setId('alliance_bioactives_bioactive_grid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Returns the collection's class alias
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'alliance_bioactives/bioactive_collection';
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepares columns for grid
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => $this->__('Bioactive ID'),
            'align'  => 'right',
            'width'  => '50px',
            'index'  => 'id',
        ));

        $this->addColumn('date_created', array(
            'header' => $this->__('Date Created'),
            'index'  => 'date_created',
            'type'   => 'date',
            'align'  => 'right',
            'width'  => '50px',
        ));

        $this->addColumn('image', array(
            'header'    => Mage::helper('alliance_bioactives')->__('Bioactive Image'),
            'align'     => 'left',
            'width'     => '100px',
            'index'     => 'image',
            'type'      => 'image',
            'escape'    => true,
            'sortable'  => false,
            'filter'    => false,
            'renderer'  => new Alliance_Bioactives_Block_Adminhtml_Bioactive_Grid_Renderer_Image,
        ));

        $this->addColumn('title', array(
            'header' => $this->__('Bioactive Title'),
            'index'  => 'title',
            'width'  => '',
        ));

        $this->addColumn('description', array(
            'header' => $this->__('Bioactive Description'),
            'index'  => 'description',
        ));

        $this->addColumn('status', array(
            'header' => $this->__('Status'),
            'index'  => 'status',
            'width'  => '50px',
        ));

        return parent::_prepareColumns();
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}