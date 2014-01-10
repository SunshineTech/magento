<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Facebook
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();

/**
 * Create table 'facebook_customer'
 */
$table = $installer->getConnection()
	->newTable($installer->getTable('facebook/customer'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_TEXT, 30, array(
		'nullable'  => false,
		'primary'   => true,		
		), 'Facebook Id')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 30, array(
                'nullable'  => false,
		), 'Name')
        ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
                'nullable'  => false,
		), 'Email')
        ->addColumn('gender', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
		'nullable'  => false,
		), 'Gender')
        ->addColumn('birthday', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
		), 'Birthday')
        ->addColumn('link', Varien_Db_Ddl_Table::TYPE_TEXT, 200, array(
		), 'Link')
        ->addColumn('picture_url', Varien_Db_Ddl_Table::TYPE_TEXT, 200, array(
		), 'Picture Url')
        ->addColumn('token', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		), 'Token')
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		), 'Customer Id')
	->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Creation Time')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Update Time')
   	->addForeignKey($installer->getFkName('facebook/customer', 'customer_id', 'customer/entity', 'entity_id'),
		'customer_id',  $installer->getTable('customer/entity'), 'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
    ->setComment('Facebook Customer Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();