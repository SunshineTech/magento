<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

/**
 * Create table 'sunshinebiz_cms/media'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('sunshinebiz_cms/media'))
    ->addColumn('media_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Media ID')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 30, array(
        'nullable'  => false,
        ), 'Media Title')
    ->addColumn('type', Varien_Db_Ddl_Table::TYPE_TEXT, 30, array(
        'nullable'  => false,
        'default'   => SunshineBiz_Cms_Model_Media_Type::TYPE_IMAGE
        ), 'Media Type')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_TEXT, 30, array(
        ), 'Media Position')    
    ->addColumn('src', Varien_Db_Ddl_Table::TYPE_TEXT, 200, array(
        'nullable'  => false,
        ), 'Media Src')
    ->addColumn('url', Varien_Db_Ddl_Table::TYPE_TEXT, 300, array(
        ), 'Media Link')
    ->addColumn('target', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        ), 'Media Target')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        ), 'Sort Order')
    ->addColumn('stores', Varien_Db_Ddl_Table::TYPE_TEXT, 30, array(
        'nullable'  => false,
        ), 'Stores')
    ->addColumn('skins', Varien_Db_Ddl_Table::TYPE_TEXT, 200, array(
        'nullable'  => false,
        ), 'Skins')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable'  => false,
        'default'   => '1',
        ), 'Is Media Active')
    ->addColumn('creation_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Media Creation Time')
    ->addColumn('update_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Media Modification Time')
    ->addIndex($installer->getIdxName('sunshinebiz_cms/media', array('position')), array('position'))
    ->addIndex($installer->getIdxName('sunshinebiz_cms/media', array('sort_order')), array('sort_order'))
    ->addIndex($installer->getIdxName('sunshinebiz_cms/media', array('stores')), array('stores'))
    ->addIndex($installer->getIdxName('sunshinebiz_cms/media', array('skins')), array('skins'))
    ->addIndex($installer->getIdxName('sunshinebiz_cms/media', array('is_active')), array('is_active'))
    ->setComment('CMS Media Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();