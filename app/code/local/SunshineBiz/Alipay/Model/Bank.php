<?php

/**
 * Alipay Internet Banking pay method
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Bank extends SunshineBiz_Alipay_Model_Payment {

    protected $_code = 'alipay_bank';
    protected $_formBlockType = 'alipay/pay_form_bank';
    protected $_infoBlockType = 'alipay/pay_info_bank';

    public function getFormFields() {

        $params = parent::getFormFields();

        $params['service'] = 'create_direct_pay_by_user';
        
        if($this->getConfigData('error_notify')) {
             $params['error_notify_url'] = Mage::getUrl('alipay/pay/error');
        }

        $params['defaultbank'] = $this->getOrder()->getPayment()->getPaymentBank();

        $params['paymethod'] = 'bankPay';
        
        if($this->getConfigData('ctu_check')) {
             $params['need_ctu_check'] = $this->getConfigData('need_ctu_check');
        }

        if ($this->getConfigData('antiphishing_timestamp_verify')) {
            $params['anti_phishing_key'] = Mage::helper('alipay')->queryTimestamp($this->getPartnerId());
        }

        if ($this->getConfigData('antiphishing_ip_check')) {
            $params['exter_invoke_ip'] = $this->getConfigData('client_ip');
        }

        if ($this->getConfigData('support_custom_timeout')) {
            $params['it_b_pay'] = $this->getConfigData('pay_timeout');
        }

        unset($params['buyer_email']);
        unset($params['token']);

        return Mage::helper('alipay')->buildRequestParams($params, $this->getSecurityCode());
    }

    public function getTitle() {
        return Mage::helper('alipay')->__('Internet Banking Payment');
    }

    public function assignData($data) {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }

        $this->getInfoInstance()->setPaymentBank($data->getPaymentBank());

        return $this;
    }

    public function validate() {
        parent::validate();

        $bankValue = $this->getInfoInstance()->getPaymentBank();
        if (empty($bankValue)) {
            Mage::throwException($this->_getHelper()->__('Please select a payment bank!'));
        }

        $paymentBank = Mage::getModel("alipay/source_bank")->getBank($bankValue);
        if (!$paymentBank) {
            Mage::throwException($this->_getHelper()->__('Invalid payment bank!'));
        }

        return $this;
    }

    public function getRedirectComment() {
        $paymentBank = Mage::getModel("alipay/source_bank")->getBank($this->getOrder()->getPayment()->getPaymentBank());
        if ($paymentBank) {
            $group = Mage::helper('alipay')->__('Personal Banking (Debit Card)');
            if ($paymentBank['group'] == 'CREDIT') {
                $group = Mage::helper('alipay')->__('Personal Banking (Credit Card)');
            } elseif ($paymentBank['group'] == 'B2B') {
                $group = Mage::helper('alipay')->__('Corporate Banking');
            }
            
            return Mage::helper('alipay')->__($paymentBank['label']) . '[' . $group . ']';
        } else {
            Mage::throwException('No payment bank!');
        }
    }
    
    public function processStatus($params) {
        //TODO refund
        $this->processInstantTransaction($params);
    }

}
