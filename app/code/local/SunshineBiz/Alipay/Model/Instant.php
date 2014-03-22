<?php

/**
 * Alipay Instant Credit Transactions pay method
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Instant extends SunshineBiz_Alipay_Model_Payment {

    protected $_code = 'alipay_instant';

    public function getFormFields() {

        $params = parent::getFormFields();

        $params['service'] = 'create_direct_pay_by_user';

        if ($this->getConfigData('error_notify')) {
            $params['error_notify_url'] = Mage::getUrl('alipay/pay/error');
        }

        if ($this->getConfigData('credit_pay')) {
            $params['paymethod'] = $this->getConfigData('pay_method');
        }

        if ($this->getConfigData('ctu_check')) {
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

        return Mage::helper('alipay')->buildRequestParams($params, $this->getSecurityCode());
    }

    public function getTitle() {
        return Mage::helper('alipay')->__('Alipay Instant Credit Transactions');
    }

    public function processStatus($params) {
        //TODO refund
        $this->processInstantTransaction($params);
    }

}
