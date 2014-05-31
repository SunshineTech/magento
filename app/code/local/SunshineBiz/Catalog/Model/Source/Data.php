<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Catalog_Model_Source_Data {

    public function toOptionArray() {

        return array(
            array(
                'value' => 'real data',
                'label' => Mage::helper('catalog')->__('Real Data')
            ),
            array(
                'value' => 'store owner input',
                'label' => Mage::helper('catalog')->__('Store Owner Input')
            )
        );
    }

}
