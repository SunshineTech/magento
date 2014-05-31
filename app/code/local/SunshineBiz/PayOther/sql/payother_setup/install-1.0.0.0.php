<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_PayOther
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();

/**
 * modify table 'sales/quote_payment'
 */
$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'pay_other', array(
    'type' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'comment' => 'Is Pay Other'
));

$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'pay_other_mode', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 20,
    'comment' => 'Pay Other Mode'
));

$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'friend_email', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 50,
    'comment' => 'Friend\'s email'
));

$installer->getConnection()->addColumn($installer->getTable('sales/quote_payment'), 'message', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 100,
    'comment' => 'Message'
));

/**
 * modify table 'sales/order_payment'
 */
$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'pay_other', array(
    'type' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'comment' => 'Is Pay Other'
));

$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'pay_other_mode', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 20,
    'comment' => 'Pay Other Mode'
));

$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'friend_email', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 50,
    'comment' => 'Friend\'s email'
));

$installer->getConnection()->addColumn($installer->getTable('sales/order_payment'), 'message', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 100,
    'comment' => 'Message'
));

$installer->endSetup();
