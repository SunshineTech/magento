<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Adminhtml
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Adminhtml_Block_Widget_Grid_Column_Filter_Text extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Text {
    
    public function getHtml() {
        
        $html = '<select name="'.$this->_getHtmlName().'[position]" id="'.$this->_getHtmlId().'_position" class="no-changes">';
        $position = $this->getValue('position') ? $this->getValue('position') : 'any';
        $html .= '<option value="any"'. ($position === 'any' ? 'selected="selected"' : '') .'>'.$this->escapeHtml(Mage::helper('sunshinebiz_adminhtml')->__("Include")).'</option>';
        $html .= '<option value="start"'. ($position === 'start' ? 'selected="selected"' : '') .'>'.$this->escapeHtml(Mage::helper('sunshinebiz_adminhtml')->__("Start with")).'</option>';
        $html .= '<option value="end"'. ($position === 'end' ? 'selected="selected"' : '') .'>'.$this->escapeHtml(Mage::helper('sunshinebiz_adminhtml')->__("End with")).'</option>';
        $html.='</select>';
        $html .= '<div class="field-100"><input type="text" name="'.$this->_getHtmlName().'[value]" id="'.$this->_getHtmlId().'_value" value="'.$this->getEscapedValue("value").'" class="input-text no-changes"/></div>';
        
        return $html;
    }
    
    public function getCondition() {
        
        $value = $this->getEscapedValue('value');
        if ($value && strlen($value) > 0)            
            return array('like' => Mage::getResourceHelper('core')->addLikeEscape($value, array('position' => $this->getEscapedValue('position'))));
        
        return null;
    }
}