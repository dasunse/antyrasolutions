<?php
/**
 * Created by PhpStorm.
 * User: kanishka
 * Date: 7/2/17
 * Time: 1:24 AM
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/**
 * Create table 'maduranga_wall_visualizer'
 */
$table = $installer->getConnection()
    // The following call to getTable('maduranga_wall/visualizer') will lookup the resource for maduranga_wall (maduranga_wall_mysql4), and look
    // for a corresponding entity called visualizer. The table name in the XML is maduranga_wall_visualizer, so ths is what is created.
    ->newTable($installer->getTable('maduranga_wall/visualizer'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_CLOB, 0, array(
        'nullable'  => false,
    ), 'Name')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_VARCHAR, 0, array(
        'nullable'  => false,
    ), 'image path')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_BOOLEAN, 0, array(
        'nullable'  => false,
    ), 'wallpaper status ');
$installer->getConnection()->createTable($table);

$installer->endSetup();