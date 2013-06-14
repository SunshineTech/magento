<?php

/**
 * SunshineBiz_Location area grid column filter block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Area_Grid_Column_Filter_Parent extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select {

    protected function _getOptions() {

        $options = array();
        $value = $this->getColumn()->getGrid()->getColumn('region_id')->getFilter()->getValue();
        if ($value && isset($value['region'])) {
            $options = Mage::getResourceModel('location/area_collection')
                    ->addRegionFilter($value['region'])
                    ->addFieldToFilter('type', array('lteq' => SunshineBiz_Location_Model_Area::TYPE_ADMINISTRATIVE_REGION_LEVEL_3))
                    ->load()
                    ->toOptionArray();
        }

        return $options;
    }
}