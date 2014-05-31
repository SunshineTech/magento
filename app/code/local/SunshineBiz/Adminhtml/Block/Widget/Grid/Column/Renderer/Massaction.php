<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Adminhtml
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Adminhtml_Block_Widget_Grid_Column_Renderer_Massaction extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Massaction {
    
    public function renderHeader() {
        if($this->_column->getGrid()->getFilterVisibility())
            return Mage::helper('sunshinebiz_adminhtml')->__('Search in Items Selected');
    }
}