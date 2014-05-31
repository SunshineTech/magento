<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Catalog_Block_Product_Bestseller extends SunshineBiz_Catalog_Block_Product_Abstract {

    protected function _getProductCollection() {

        if ($this->getDataFrom() != self::DEFAULT_DATA_FROM) {
            return parent::_getProductCollection();
        }

        $collection = Mage::getResourceModel('reports/product_collection');
        
        $this->_addProductAttributesAndPrices($collection)
                ->addOrderedQty()
                ->setOrder('ordered_qty');
        
        $this->addCategoryFilter($collection)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds())
                ->setPageSize($this->getProductsCount())->setCurPage(1);

        return $collection;
    }

    protected function getCode() {
        return 'bestseller';
    }

    protected function getTitle() {
        $title = Mage::getStoreConfig('feature/bestseller/title');

        return $title ? $title : Mage::helper('catalog')->__('Best Seller');
    }

}
