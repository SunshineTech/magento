<?php

/**
 * Alipay Pay Info Container block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Block_Pay_Info_Container extends Mage_Payment_Block_Info_Container {

    /**
     * Retrieve payment info model
     *
     * @return Mage_Payment_Model_Info
     */
    public function getPaymentInfo() {
        return Mage::registry('order')->getPayment();
    }

    protected function _toHtml() {
        $html = '';
        if (($block = $this->getChild($this->_getInfoBlockName()))) {
            $html = $block->toHtml();
        }
        return $html;
    }

}
