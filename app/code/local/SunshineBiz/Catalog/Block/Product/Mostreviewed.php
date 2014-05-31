<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Catalog_Block_Product_Mostreviewed extends SunshineBiz_Catalog_Block_Product_Abstract {

    protected function _getProductCollection() {

        $collection = Mage::getResourceModel('reports/product_collection');

        $this->_addProductAttributesAndPrices($collection);
        $this->addCategoryFilter($collection)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
        
        $collection->joinField('reviews_count', 'review/review_aggregate', 'reviews_count', 'entity_pk_value=entity_id', array('entity_type' => 1, 'store_id' => Mage::app()->getStore()->getId()))
                ->setOrder('reviews_count', 'desc')
                ->setPageSize($this->getProductsCount())->setCurPage(1);

        return $collection;
    }

    protected function getCode() {
        return 'mostreviewed';
    }

    protected function getTitle() {
        $title = Mage::getStoreConfig('feature/mostreviewed/title');

        return $title ? $title : Mage::helper('catalog')->__('Most Reviewed');
    }

}