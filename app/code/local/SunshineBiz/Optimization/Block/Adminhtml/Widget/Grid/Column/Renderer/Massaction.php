<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Optimization
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Optimization_Block_Adminhtml_Widget_Grid_Column_Renderer_Massaction extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Massaction {
    
    public function renderHeader() {
        if($this->_column->getGrid()->getFilterVisibility())
            return Mage::helper('optimization')->__('Search in Items Selected');
    }
}