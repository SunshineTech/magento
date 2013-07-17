<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Administrat
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

/**
 * Create table 'administrat/user_rule'
 */
$table = $installer->getConnection()
        ->newTable($installer->getTable('administrat/user_rule'))
        ->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
                ), 'Rule ID')
        ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
                ), 'User ID')
        ->addColumn('resource_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable'  => true,
            'default'   => null,
            ), 'Resource ID')
        ->addColumn('privileges', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
            'nullable'  => true,
            ), 'Privileges')
        ->addColumn('assert_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
            ), 'Assert ID')
        ->addColumn('permission', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
            ), 'Permission')
        ->addIndex($installer->getIdxName('administrat/user_rule', array('resource_id', 'user_id')), array('resource_id', 'user_id'))
        ->addIndex($installer->getIdxName('administrat/user_rule', array('user_id', 'resource_id')), array('user_id', 'resource_id'))
        ->addForeignKey($installer->getFkName('administrat/user_rule', 'user_id', 'admin/user', 'user_id'),
            'user_id', $installer->getTable('admin/user'), 'user_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)        
        ->setComment('Admin User Rule Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();