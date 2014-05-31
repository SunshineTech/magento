<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'feature', array(
    'group'             => 'General',
    'type'              => 'varchar',
    'frontend'          => '',
    'class'             => '',
    'label'             => 'Feature',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'input'             => 'multiselect',
    'source'            => 'sunshinebiz_catalog/product_attribute_source_feature',
    'backend'           => 'sunshinebiz_catalog/product_attribute_backend_feature',
    'required'          => false,
    'visible_in_advanced_search' => true,
    'filterable'        => true,
    'filterable_in_search'  => true,
    'visible_on_front'  => true,
));