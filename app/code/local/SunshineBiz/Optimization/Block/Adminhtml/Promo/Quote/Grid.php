<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Optimization
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Optimization_Block_Adminhtml_Promo_Quote_Grid extends Mage_Adminhtml_Block_Promo_Quote_Grid {
    
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
