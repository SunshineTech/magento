<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Optimization
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Optimization_Block_Adminhtml_Widget_Grid_Column_Filter_Price extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Price {
    
    protected function _getCurrencySelectHtml() {

        $value = $this->getEscapedValue('currency');
        if (!$value)
            $value = $this->getColumn()->getCurrencyCode();

        $html  = '';
        $html .= '<select name="'.$this->_getHtmlName().'[currency]" id="'.$this->_getHtmlId().'_currency">';
        foreach ($this->_getCurrencyList() as $currency) {
            $html .= '<option value="' . $currency . '" '.($currency == $value ? 'selected="selected"' : '').'>' . Mage::app()->getLocale()->currency($currency)->getName() . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
}