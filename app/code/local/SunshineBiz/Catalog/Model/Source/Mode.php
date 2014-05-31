<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Catalog_Model_Source_Mode {

    public function toOptionArray() {

        return array(
            array(
                'value' => 'slider-horiz',
                'label' => Mage::helper('catalog')->__('Horizontal slider')
            ),
            array(
                'value' => 'slider-verti',
                'label' => Mage::helper('catalog')->__('Vertical Slider')
            ),
            array(
                'value' => 'sidebar-list',
                'label' => Mage::helper('catalog')->__('Sidebar List')
            ),
            array(
                'value' => 'grid',
                'label' => Mage::helper('catalog')->__('Grid')
            ),
            array(
                'value' => 'list',
                'label' => Mage::helper('catalog')->__('List')
            )
        );
    }

}
