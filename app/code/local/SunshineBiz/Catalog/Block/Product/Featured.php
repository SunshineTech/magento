<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Catalog_Block_Product_Featured extends SunshineBiz_Catalog_Block_Product_Abstract {

    protected function getCode() {
        return 'featured';
    }

    protected function getTitle() {
        $title = Mage::getStoreConfig('feature/featured/title');

        return $title ? $title : Mage::helper('catalog')->__('Featured Goods');
    }

}
