<?php
class SunshineBiz_Optimization_Block_Widget_Grid_Column_Renderer_Massaction extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Massaction {
    
    public function renderHeader() {
        if($this->_column->getGrid()->getFilterVisibility())
            return Mage::helper('optimization')->__('Search in Items Selected');
    }
}