<?php

/**
 * Catalog Product feature Attributes Source Model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Catalog_Model_Product_Attribute_Source_Feature extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {

    public function getAllOptions() {
        if (is_null($this->_options)) {
            $this->_options = array(
                array(
                    'value' => 'bestseller',
                    'label' => $this->getTitle('bestseller') ? $this->getTitle('bestseller') : Mage::helper('catalog')->__('Best Seller')
                ),
                array(
                    'value' => 'featured',
                    'label' => $this->getTitle('featured') ? $this->getTitle('featured') : Mage::helper('catalog')->__('Featured Goods')
                ),
                array(
                    'value' => 'mostcarted',
                    'label' => $this->getTitle('mostcarted') ? $this->getTitle('mostcarted') : Mage::helper('catalog')->__('Most Carted')
                ),
                array(
                    'value' => 'mostviewed',
                    'label' => $this->getTitle('mostviewed') ? $this->getTitle('mostviewed') : Mage::helper('sunshinebiz_catalog')->__('Most Viewed')
                ),
                array(
                    'value' => 'mostwishlisted',
                    'label' => $this->getTitle('mostwishlisted') ? $this->getTitle('mostwishlisted') : Mage::helper('sunshinebiz_catalog')->__('Most Wishlisted')
                ),
                array(
                    'value' => 'newlisting',
                    'label' => $this->getTitle('newlisting') ? $this->getTitle('newlisting') : Mage::helper('catalog')->__('New Listing')
                )
            );
        }
        
        return $this->_options;
    }
    
    protected function getTitle($param) {
        return Mage::getStoreConfig('feature/' . $param . '/title');
    }
}
