<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

/**
 * Create table 'location/area'
 */
$table = $installer->getConnection()
	->newTable($installer->getTable('location/area'))
	->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		'identity'  => true,			
		), 'Area Id')
	->addColumn('default_name', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
		'nullable'  => false,
		), 'Area Default Name')
        ->addColumn('default_abbr', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
		), 'Area Default Abbreviation Name')
        ->addColumn('default_mnemonic', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
		), 'Area Default Mnemonic')
	->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
		'default'	=> 1,
		), 'Is Area Active')
        ->addColumn('type', Varien_Db_Ddl_Table::TYPE_SMALLINT, 1, array(
                'nullable'  => false,
		), 'Area Type')
	->addColumn('parent_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'default'	=> 0,
		), 'Area Parent Id')
	->addColumn('region_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
		), 'Region Id')
	->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Creation Time')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Update Time')
        ->addIndex($installer->getIdxName('location/area', 'is_active'), 'is_active')
        ->addIndex($installer->getIdxName('location/area', 'type'), 'type')
        ->addIndex($installer->getIdxName('location/area', 'parent_id'), 'parent_id')
        ->addIndex($installer->getIdxName('location/area', 'region_id'), 'region_id')
        ->addIndex($installer->getIdxName('location/area', array('default_name', 'parent_id', 'region_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), 
                array('default_name', 'parent_id', 'region_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
   	->addForeignKey($installer->getFkName('location/area', 'region_id', 'location/region', 'region_id'),
		'region_id',  $installer->getTable('location/region'), 'region_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
    ->setComment('Location Area Table');
$installer->getConnection()->createTable($table);

/**
 * Create table 'location/area_name'
 */
$table = $installer->getConnection()
	->newTable($installer->getTable('location/area_name'))
	->addColumn('area_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
            ), 'Area Id')
	->addColumn('locale', Varien_Db_Ddl_Table::TYPE_TEXT, 8, array(
            'nullable'  => false,
            'primary'   => true,
            'default'   => '',
            ), 'Locale')
	->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
            'nullable'  => false,
            ), 'Area Name')
        ->addColumn('abbr', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
            ), 'Area Abbreviation Name')
        ->addColumn('mnemonic', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
            ), 'Area Mnemonic')
    ->addIndex($installer->getIdxName('location/area_name', 'area_id'), 'area_id')
    ->addForeignKey($installer->getFkName('location/area_name', 'area_id', 'location/area', 'id'), 
    	'area_id',  $installer->getTable('location/area'), 'id',
    	Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
    ->setComment('Location Area Name Table');
$installer->getConnection()->createTable($table);

/**
 * Create table 'location/building'
 */
$table = $installer->getConnection()
	->newTable($installer->getTable('location/building'))
	->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		'identity'  => true,			
		), 'Building Id')
	->addColumn('default_name', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
		'nullable'  => false,
		), 'Building Default Name')
	->addColumn('default_mnemonic', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
		), 'Building Default Mnemonic')
	->addColumn('area_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
                'nullable'  => false,
		), 'Building Area Id')
        ->addColumn('circle_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		), 'Building Trade Circle Id')
	->addColumn('default_address', Varien_Db_Ddl_Table::TYPE_TEXT, 200, array(
		'nullable'  => false,
		), 'Building Default Address')
	->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
		'default'	=> 1,
		), 'Is Building Active')
	->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            ), 'Creation Time')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            ), 'Update Time')
        ->addIndex($installer->getIdxName('location/building', array('default_name', 'area_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), 
            array('default_name', 'area_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
        ->addIndex($installer->getIdxName('location/building', 'default_name'), 'default_name')
        ->addIndex($installer->getIdxName('location/building', 'default_mnemonic'), 'default_mnemonic')
        ->addIndex($installer->getIdxName('location/building', 'area_id'), 'area_id')
        ->addIndex($installer->getIdxName('location/building', 'is_active'), 'is_active')
        ->addForeignKey($installer->getFkName('location/building', 'area_id', 'location/area', 'id'),
                    'area_id',  $installer->getTable('location/area'), 'id',
                    Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
        ->setComment('Location Building Table');
$installer->getConnection()->createTable($table);

/**
 * Create table 'location/building_name'
 */
$table = $installer->getConnection()
	->newTable($installer->getTable('location/building_name'))
	->addColumn('building_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		), 'Building Id')
	->addColumn('locale', Varien_Db_Ddl_Table::TYPE_TEXT, 8, array(
		'nullable'  => false,
		'primary'   => true,
		'default'   => '',
		), 'Locale')
	->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
		), 'Building Name')
	->addColumn('mnemonic', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
		), 'Building Mnemonic')
	->addColumn('address', Varien_Db_Ddl_Table::TYPE_TEXT, 200, array(
		), 'Building Address')
	->addIndex($installer->getIdxName('location/building_name', 'building_id'), 'building_id')
	->addForeignKey($installer->getFkName('location/building_name', 'building_id', 'location/building', 'id'),
			'building_id',  $installer->getTable('location/building'), 'id',
			Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
	->setComment('Location Building Name Table');
$installer->getConnection()->createTable($table);

$installer->getConnection()->addColumn($installer->getTable('location/region'), 'default_abbr', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 30,
    'comment' => 'Region Default Abbreviation Name'
));

$installer->getConnection()->addColumn($installer->getTable('location/region_name'), 'abbr', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 30,
    'comment' => 'Region Abbreviation Name'
));

$installer->getConnection()
	->addIndex($installer->getTable('location/region'), 
			$installer->getIdxName('location/region', array('code', 'country_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), 
			array('code', 'country_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE);
$installer->getConnection()
	->addIndex($installer->getTable('location/region'),
		$installer->getIdxName('location/region', array('default_name', 'country_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
		array('default_name', 'country_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE);
    
$installer->endSetup();