<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Block_Adminhtml_Widget_Grid_Column_Filter_Skins extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select {

    protected function _getOptions() {
        return Mage::getSingleton('design/skin')->getSkinValuesForForm(true, true, true);
    }

    public function getCondition() {
        if (is_null($this->getValue())) {
            return null;
        }

        $skins = explode('/', $this->getValue());
        $count = count($skins);
        switch ($count) {
            case 1:
                if ($this->getValue() === 'All') {
                    return array('eq' => 'All');
                }
                
                return array(
                    array('eq' => 'All'),
                    array('eq' => $this->getValue()),
                    array('like' => $this->getValue() . ',%'),
                    array('like' => '%,' . $this->getValue() . ',%'),
                    array('like' => '%,' . $this->getValue())
                );
            case 2:
                return array(
                    array('eq' => 'All'),
                    array('eq' => $skins[0]),
                    array('like' => $skins[0] . ',%'),
                    array('like' => '%,' . $skins[0] . ',%'),
                    array('like' => '%,' . $skins[0]),
                    array('eq' => $this->getValue()),
                    array('like' => $this->getValue() . ',%'),
                    array('like' => '%,' . $this->getValue() . ',%'),
                    array('like' => '%,' . $this->getValue())
                );
            case 3:
                return array(
                    array('eq' => 'All'),
                    array('eq' => $skins[0]),
                    array('like' => $skins[0] . ',%'),
                    array('like' => '%,' . $skins[0] . ',%'),
                    array('like' => '%,' . $skins[0]),
                    array('eq' => $skins[0] . '/' . $skins[1]),
                    array('like' => $skins[0] . '/' . $skins[1] . ',%'),
                    array('like' => '%,' . $skins[0] . '/' . $skins[1] . ',%'),
                    array('like' => '%,' . $skins[0] . '/' . $skins[1]),
                    array('eq' => $this->getValue()),
                    array('like' => $this->getValue() . ',%'),
                    array('like' => '%,' . $this->getValue() . ',%'),
                    array('like' => '%,' . $this->getValue())
                );
            default:
                return null;
        }
    }

}
