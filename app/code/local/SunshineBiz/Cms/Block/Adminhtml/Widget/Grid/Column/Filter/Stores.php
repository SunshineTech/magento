<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Block_Adminhtml_Widget_Grid_Column_Filter_Stores extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select {

    protected function _getOptions() {
        return Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(true, true, true);
    }

    public function getCondition() {
        if (is_null($this->getValue())) {
            return null;
        }

        $stores = explode('-', $this->getValue());
        $count = count($stores);
        switch ($count) {
            case 1:
                if ($stores[0] === '0') {
                    return array('eq' => $stores[0]);
                }
                return array(
                    array('eq' => '0'),
                    array('eq' => $stores[0])
                );
            case 2:
                return array(
                    array('eq' => '0'),
                    array('eq' => $stores[0]),
                    array('eq' => $this->getValue())
                );
            default:
                return array(
                    array('eq' => '0'),
                    array('eq' => $stores[0]),
                    array('eq' => $stores[0] . '-' . $stores[1]),
                    array('eq' => $this->getValue())
                );
        }
    }

}
