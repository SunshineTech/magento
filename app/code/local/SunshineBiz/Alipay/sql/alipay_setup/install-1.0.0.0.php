<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();

/**
 * Create table 'alipay_customer'
 */
$table = $installer->getConnection()
	->newTable($installer->getTable('alipay/customer'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_TEXT, 16, array(
		'nullable'  => false,
		'primary'   => true,		
		), 'Alipay Id')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 30, array(
                'nullable'  => false,
		), 'Name')
        ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
                'nullable'  => false,
		), 'Email')
        ->addColumn('user_grade', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
		), 'User Grade')
        ->addColumn('grade_type', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
		), 'User Grade Type')
        ->addColumn('gmt_decay', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
		), 'GMT Decay')
        ->addColumn('expired_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                'nullable'  => false,
		), 'Token Expired Time')
        ->addColumn('token', Varien_Db_Ddl_Table::TYPE_TEXT, 40, array(
                'nullable'  => false,
		), 'Token')
        ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		), 'Customer Id')
	->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Creation Time')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Update Time')
   	->addForeignKey($installer->getFkName('alipay/customer', 'customer_id', 'customer/entity', 'entity_id'),
		'customer_id',  $installer->getTable('customer/entity'), 'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
    ->setComment('Alipay Customer Table');
$installer->getConnection()->createTable($table);

/**
 * modify table 'sales/quote_payment'
 */
$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'payment_bank', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 20,
    'comment' => ' Alipay Payment Bank'
));

/**
 * modify table 'sales/order_payment'
 */
$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'payment_bank', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 20,
    'comment' => ' Alipay Payment Bank'
));

$installer->endSetup();
