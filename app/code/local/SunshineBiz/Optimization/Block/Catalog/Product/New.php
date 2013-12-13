<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Optimization
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Optimization_Block_Catalog_Product_New extends Mage_Catalog_Block_Product_New {

    protected function _getProductCollection() {
        
        $todayStartOfDayDate = Mage::app()->getLocale()->date()
                ->setTime('00:00:00')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate = Mage::app()->getLocale()->date()
                ->setTime('23:59:59')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        /** @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());


        $collection = $this->_addProductAttributesAndPrices($collection)
                ->addStoreFilter()
                ->addAttributeToFilter('news_from_date', array('or' => array(
                        0 => array('date' => true, 'to' => $todayEndOfDayDate),
                        1 => array('is' => new Zend_Db_Expr('null')))
                        ), 'inner') //left 改为 inner
                ->addAttributeToFilter('news_to_date', array('or' => array(
                        0 => array('date' => true, 'from' => $todayStartOfDayDate),
                        1 => array('is' => new Zend_Db_Expr('null')))
                        ), 'inner') //left 改为 inner
                ->addAttributeToFilter(
                        array(
                            array('attribute' => 'news_from_date', 'is' => new Zend_Db_Expr('not null')),
                            array('attribute' => 'news_to_date', 'is' => new Zend_Db_Expr('not null'))
                        )
                )
                ->addAttributeToSort('news_from_date', 'desc')
                ->setPageSize($this->getProductsCount())
                ->setCurPage(1)
        ;

        return $collection;
    }

}
