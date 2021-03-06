<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Advanced Reports
 * @version   1.0.24
 * @build     758
 * @copyright Copyright (C) 2017 Mirasvit (http://mirasvit.com/)
 */



class Mirasvit_Advr_Block_Adminhtml_Catalog_Product_Detail extends Mirasvit_Advr_Block_Adminhtml_Catalog_Abstract
{
    public function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->setHeaderText(
            Mage::helper('advr')->__('Sales Report for "%s"', Mage::registry('current_product')->getName())
        );

        return $this;
    }

    protected function prepareChart()
    {
        $this->setChartType('column');

        $this->initChart()
            ->setXAxisType('datetime')
            ->setXAxisField('period_of_sale');

        return $this;
    }

    protected function prepareGrid()
    {
        $this->initGrid()
            ->setDefaultSort('period_of_sale')
            ->setDefaultDir('asc')
            ->setDefaultLimit(100000)
            ->setPagerVisibility(false);

        return $this;
    }

    protected function prepareToolbar()
    {
        $this->initToolbar()
            ->setRangesVisibility(true)
            ->setCompareVisibility(false);

        return $this;
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('advr/report_sales')
            ->setBaseTable('catalog/product')
            ->setFilterData($this->getFilterData(), true, false)
            ->selectColumns('product_id')
            ->selectColumns($this->getVisibleColumns())
            ->groupByColumn('period_of_sale')
            ->addFieldToFilter('product_id', Mage::registry('current_product')->getId());

        return $collection;
    }

    public function getColumns()
    {
        $columns = array(
            'period_of_sale' => array(
                'header' => 'Period',
                'type' => 'text',
                'frame_callback' => array(Mage::helper('advr/callback'), 'period'),
                'totals_label' => 'Total',
                'filter_totals_label' => 'Subtotal',
                'grouped' => true,
                'filter' => false,
            ),
            'avg_item_base_price' => array(
                'header' => 'Average Price',
                'type' => 'currency',
                'hidden' => false,
                'totals_label' => '',
            ),
        );

        $columns += $this->getBaseProductColumns();

        return $columns;
    }

    public function getFilterData()
    {
        $filterData = parent::getFilterData();
        $filterData->setIncludeChild($this->getRequest()->getParam('as_child'));

        return $filterData;
    }
}
