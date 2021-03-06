<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/**
 * Create table 'alliance_julienreviews_reviews'
 */
$reviews = $installer->getConnection()
    ->newTable($installer->getTable('alliance_julienreviews/review'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')
    ->addColumn('date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        'default'   => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
    ), 'Date')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Customer ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Product ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'default'   => '0',
    ), 'Store id')
    ->addColumn('customer_email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Customer Email')
    ->addColumn('product_sku', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Product SKU')
    ->addColumn('product_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Product Name')
    ->addColumn('star_rating', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
        'nullable'  => false,
    ), 'Star Rating')
    ->addColumn('recommended', Varien_Db_Ddl_Table::TYPE_TEXT, 16, array(
        'nullable'  => false,
    ), 'Recommended')
    ->addColumn('review_headline', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Review Headline')
    ->addColumn('review_text', Varien_Db_Ddl_Table::TYPE_TEXT, 5000, array(
        'nullable'  => false,
    ), 'Review Text')
    ->addColumn('purchased_at', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Purchased At')
    ->addColumn('age_range', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Age Range')
    ->addColumn('location', Varien_Db_Ddl_Table::TYPE_TEXT, 30, array(
        'nullable'  => false,
    ), 'Location')
    ->addColumn('helpful_yes', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'unsigned'  => true,
        'default'   => 0,
    ), 'Helpful Yes')
    ->addColumn('helpful_no', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'unsigned'  => true,
        'default'   => 0,
    ), 'Helpful No')
    ->addColumn('notify', Varien_Db_Ddl_Table::TYPE_TEXT, 16, array(
        'nullable'  => false,
        'default'   => 'No',
    ), 'Notify')
    ->addColumn('notified', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable'  => false,
        'default'   => 0,
    ), 'Notified')
    ->addColumn('contributed', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable'  => false,
        'default'   => 0,
    ), 'Contributed')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable'  => false,
        'default'   => 'Pending',
    ), 'Status');
$installer->getConnection()->createTable($reviews);

/**
 * Create table 'alliance_julienreviews_contributors'
 */
$contributors = $installer->getConnection()
    ->newTable($installer->getTable('alliance_julienreviews/contributor'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Customer ID')
    ->addColumn('reviews_count', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Reviews Count');
$installer->getConnection()->createTable($contributors);

/**
 * Create table 'alliance_julienreviews_top_contributors'
 */
$top_contributors = $installer->getConnection()
    ->newTable($installer->getTable('alliance_julienreviews/topcontributor'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Customer ID')
    ->addColumn('rank', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Rank');
$installer->getConnection()->createTable($top_contributors);

/**
 * Create table 'alliance_julienreviews_helpful'
 */
$helpful = $installer->getConnection()
    ->newTable($installer->getTable('alliance_julienreviews/helpful'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Customer ID')
    ->addColumn('review_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Product ID')
    ->addColumn('helpful', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Helpful');
$installer->getConnection()->createTable($helpful);

$installer->endSetup();