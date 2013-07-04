<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Adminhtml_Widget_Grid_Column_Filter_Circle extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select {
    
    protected function _getOptions() {

        $options = array();
        $value = $this->getColumn()->getGrid()->getColumn('area_id')->getFilter()->getValue();
        if ($value && isset($value['area'])) {
            $options = Mage::getResourceModel('location/area_collection')
                    ->addFieldToFilter('parent_id', $value['area'])
                    ->addFieldToFilter('type', SunshineBiz_Location_Model_Area::TYPE_TRADE_CIRCLE)
                    ->load()
                    ->toOptionArray();
        }

        return $options;
    }
}
