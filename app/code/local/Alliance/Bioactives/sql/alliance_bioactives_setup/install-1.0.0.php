<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$setup = Mage::getResourceModel('catalog/setup','catalog_setup');
$setup->removeAttribute('catalog_product','key_bioactives');

/**
 * Add product attribute 'key_bioactives' to General group of all products
 *
 * This is a multiselect, with options populated dynamically via Alliance_Bioactives_Model_Bioactive_Attribute_Source
 */
$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'key_bioactives', array(
    'group'                      => 'General',
    'label'                      => 'Key Bioactives',
    'type'                       => 'text',
    'input'                      => 'multiselect',
    'backend'                    => 'eav/entity_attribute_backend_array',
    'class'                      => '',
    'source'                     => 'alliance_bioactives/bioactive_attribute_source',
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'                    => TRUE,
    'required'                   => FALSE,
    'user_defined'               => TRUE,
    'searchable'                 => TRUE,
    'filterable'                 => TRUE,
    'comparable'                 => TRUE,
    'visible_on_front'           => TRUE,
    'visible_in_advanced_search' => TRUE,
    'unique'                     => FALSE,
));

/**
 * Create table 'alliance_bioactives_bioactive'
 */
$bioactive = $installer->getConnection()
    ->newTable($installer->getTable('alliance_bioactives/bioactive'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Title')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 5000, array(
        'nullable'  => false,
    ), 'Description')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, 2083, array(
        'nullable'  => false,
    ), 'Image')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable'  => false,
        'default'   => 'Enabled',
    ), 'Status')
    ->addColumn('date_created', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, NULL, array(
        'nullable'  => false,
        'default'   => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Date Created');
$installer->getConnection()->createTable($bioactive);

/**
 * Create table 'alliance_bioactives_relation'
 */
$relation = $installer->getConnection()
    ->newTable($installer->getTable('alliance_bioactives/relation'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Product ID')
    ->addColumn('bioactive_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Bioactive ID');

/**
 * Adding unique key to product_id column of alliance_bioactives_relation
 */
$relation->addIndex(
    $installer->getIdxName(
        'alliance_bioactives/relation',
        array(
            'product_id',
        ),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array(
        'product_id',
    ),
    array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
);

$installer->getConnection()->createTable($relation);

$installer->endSetup();