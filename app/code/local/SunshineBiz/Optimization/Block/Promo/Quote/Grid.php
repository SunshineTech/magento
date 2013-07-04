<?php

class SunshineBiz_Optimization_Block_Promo_Quote_Grid extends Mage_Adminhtml_Block_Promo_Quote_Grid {
    
    protected function _prepareColumns() {        
        parent::_prepareColumns();
        $this->getColumn('is_active')->setOptions(
                array(
                    1 => Mage::helper('salesrule')->__('Active'),
                    0 => Mage::helper('salesrule')->__('Inactive')
                )
        );
        
        return $this;
    }
}
