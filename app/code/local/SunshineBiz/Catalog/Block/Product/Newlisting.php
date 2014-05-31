<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Catalog_Block_Product_Newlisting extends SunshineBiz_Catalog_Block_Product_Abstract {

    protected $_newdays;

    protected function _getProductCollection() {

        if ($this->getDataFrom() != self::DEFAULT_DATA_FROM) {
            return parent::_getProductCollection();
        }

        /** @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = Mage::getResourceModel('catalog/product_collection');        

        $this->_addProductAttributesAndPrices($collection);
        $this->addCategoryFilter($collection)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

        if ($this->_newdays) {
            $limitDate = Mage::app()->getLocale()->date()
                    ->subDayOfYear($this->_newdays)
                    ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

            $collection->addAttributeToFilter('created_at', array('date' => true, 'from' => $limitDate))
                    ->addAttributeToSort('created_at', 'desc');
        } else {
            $todayStartOfDayDate = Mage::app()->getLocale()->date()
                    ->setTime('00:00:00')
                    ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

            $todayEndOfDayDate = Mage::app()->getLocale()->date()
                    ->setTime('23:59:59')
                    ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);


            $collection->addAttributeToFilter('news_from_date', array('or' => array(
                            0 => array('date' => true, 'to' => $todayEndOfDayDate),
                            1 => array('is' => new Zend_Db_Expr('null')))
                            ), 'left')
                    ->addAttributeToFilter('news_to_date', array('or' => array(
                            0 => array('date' => true, 'from' => $todayStartOfDayDate),
                            1 => array('is' => new Zend_Db_Expr('null')))
                            ), 'left')
                    ->addAttributeToFilter(
                            array(
                                array('attribute' => 'news_from_date', 'is' => new Zend_Db_Expr('not null')),
                                array('attribute' => 'news_to_date', 'is' => new Zend_Db_Expr('not null'))
                            )
                    )
                    ->addAttributeToSort('news_from_date', 'desc');
        }

        $collection->setPageSize($this->getProductsCount())
                ->setCurPage(1);

        return $collection;
    }

    public function setNewdays($days) {
        $newdays = (int) $days;
        if ($newdays > 0) {
            $this->_newdays = $newdays;
        }
    }

    protected function getCode() {
        return 'newlisting';
    }

    protected function getTitle() {
        $title = Mage::getStoreConfig('feature/newlisting/title');

        return $title ? $title : Mage::helper('catalog')->__('New Listing');
    }

}
