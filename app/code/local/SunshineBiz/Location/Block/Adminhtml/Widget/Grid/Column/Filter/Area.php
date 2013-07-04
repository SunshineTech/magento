<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Adminhtml_Widget_Grid_Column_Filter_Area extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select {

    protected function _getOptions() {

        $options = array();
        $value = $this->getData('value');
        if (isset($value['region'])) {
            $options = Mage::getResourceModel('location/area_collection')
                    ->addRegionFilter($value['region'])
                    ->addFieldToFilter('type', array('lteq' => SunshineBiz_Location_Model_Area::TYPE_ADMINISTRATIVE_REGION_LEVEL_3))
                    ->load()
                    ->toOptionArray();
        }

        return $options;
    }

    protected function _getCountryOptions() {
        $options = Mage::getResourceModel('directory/country_collection')
                ->load()
                ->toOptionArray();

        return $options;
    }

    protected function _getRegionOptions() {
        $options = array();
        $value = $this->getData('value');
        if (isset($value['country'])) {
            $options = Mage::getModel('directory/country')
                    ->setId($value['country'])
                    ->getRegions()
                    ->toOptionArray();
        }

        return $options;
    }

    public function getHtml() {
        $value = $this->getData('value');
        $html = '<div><select name="' . $this->_getHtmlName() . '[country]" id="' . $this->_getHtmlId() . '_country" class="no-changes" onChange="locationChanged(this, \'' . $this->getUrl('*/json/countryRegion') . '\',  \'' . $this->_getHtmlId() . '_region\')">';
        foreach ($this->_getCountryOptions() as $option) {
            $html .= $this->_renderOption($option, isset($value['country']) ? $value['country'] : '');
        }
        $html .='</select></div>';

        $html .= '<div><select name="' . $this->_getHtmlName() . '[region]" id="' . $this->_getHtmlId() . '_region" class="no-changes" onChange="locationChanged(this, \'' . $this->getUrl('*/json/regionArea') . '\',  \'' . $this->_getHtmlId() . '_area\')">';
        foreach ($this->_getRegionOptions() as $option) {
            $html .= $this->_renderOption($option, isset($value['region']) ? $value['region'] : '');
        }
        $html.='</select></div>';

        $html .= '<div><select name="' . $this->_getHtmlName() . '[area]" id="' . $this->_getHtmlId() . '_area" class="no-changes" onChange="locationChanged(this, \'' . $this->getUrl('*/json/areaCircle') . '\',  \'' . $this->getColumn()->getGrid()->getId() . '_filter_circle_id\')">';
        foreach ($this->_getOptions() as $option) {
            $html .= $this->_renderOption($option, isset($value['area']) ? $value['area'] : '');
        }
        $html.='</select></div>';

        return $html;
    }

    public function getCondition() {
        $value = $this->getData('value');

        if (isset($value['country']) && isset($value['region']) && isset($value['area'])) {
            $area = Mage::getModel('location/area')->load($value['area']);
            if ($area->getType() == SunshineBiz_Location_Model_Area::TYPE_ADMINISTRATIVE_REGION_LEVEL_3)
                return array('eq' => $value['area']);
            else {
                $options = Mage::getResourceModel('location/area_collection')
                    ->addFieldToFilter('parent_id', $value['area'])
                    ->addFieldToFilter('type', SunshineBiz_Location_Model_Area::TYPE_ADMINISTRATIVE_REGION_LEVEL_3)
                    ->load()->toOptionArray(false);
                if (count($options) > 0) {
                    $areas = array();
                    foreach ($options as $option) {
                        $areas[] = $option['value'];
                    }
                    
                    return array('in' => $areas);
                } else {
                    return array('eq' => 0);
                }
            }
        }

        return null;
    }

}