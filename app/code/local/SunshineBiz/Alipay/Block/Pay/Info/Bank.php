<?php

/**
 * Alipay Pay Bank Info block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Block_Pay_Info_Bank extends Mage_Payment_Block_Info {

    protected function _prepareSpecificInformation($transport = null) {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }

        $transport = parent::_prepareSpecificInformation($transport);
        $data = array();
        if (($paymentBank = Mage::getModel("alipay/source_bank")->getBank($this->getInfo()->getPaymentBank()))) {
            $group = Mage::helper('alipay')->__('Personal Banking (Debit Card)');
            if ($paymentBank['group'] == 'CREDIT') {
                $group = Mage::helper('alipay')->__('Personal Banking (Credit Card)');
            } elseif ($paymentBank['group'] == 'B2B') {
                $group = Mage::helper('alipay')->__('Corporate Banking');
            }
            
            $data[Mage::helper('alipay')->__('Payment Bank')] = $paymentBank['label'] . '[' . $group . ']';
        }
        
        return $transport->setData(array_merge($data, $transport->getData()));
    }

}
