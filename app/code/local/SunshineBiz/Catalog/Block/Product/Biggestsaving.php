<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Catalog_Block_Product_Biggestsaving extends SunshineBiz_Catalog_Block_Product_Abstract {

    protected function _getProductCollection() {
        $collection = Mage::getResourceModel('catalog/product_collection')
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

        $this->addCategoryFilter($this->_addProductAttributesAndPrices($collection))
                ->getSelect()
                ->where('price_index.final_price < price_index.price')
                ->order('1 - (price_index.final_price / price_index.price) desc');
        
        $collection->setPageSize($this->getProductsCount())
                ->setCurPage(1);

        return $collection;
    }

    protected function getCode() {
        return 'biggestsaving';
    }

    protected function getTitle() {
        $title = Mage::getStoreConfig('feature/biggestsaving/title');

        return $title ? $title : Mage::helper('catalog')->__('Biggest Saving');
    }

}
