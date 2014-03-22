<?php

/**
 * Alipay Secured Transactions pay method
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Secured extends SunshineBiz_Alipay_Model_Payment {

    protected $_code = 'alipay_secured';

    protected function getLogisticsType() {
        
        $carrierCode = $this->getOrder()->getShippingMethod(true)->getCarrierCode();
        if(in_array($carrierCode, explode('_', $this->getConfigData('express')))) {
            return 'EXPRESS';
        }
        
        if(in_array($carrierCode, explode('_', $this->getConfigData('ems')))) {
            return 'EMS';
        }
        
        if(in_array($carrierCode, explode('_', $this->getConfigData('post')))) {
            return 'POST';
        }
        
        return 'EXPRESS';
    }
    
    public function getFormFields() {

        $params = parent::getFormFields();

        $params['service'] = 'create_partner_trade_by_buyer';

        $logisticsType = $this->getLogisticsType();
        $params['logistics_type'] = $logisticsType;
        $params['logistics_fee'] = sprintf('%.2f', 0);
        $params['logistics_payment'] = 'BUYER_PAY';

        $address = $this->getOrder()->getShippingAddress();
        if ($address) {
            $params['receive_name'] = $address->getLastname() . $address->getFirstname();
            $params['receive_address'] = $address->getRegion() . $address->getCity() . $address->getStreet1() . $address->getStreet2();
        }
        
        if ($this->getConfigData('support_custom_timeout')) {
            $params['it_b_pay'] = $this->getConfigData('pay_timeout');
            $params['t_s_send_1'] = $this->getConfigData('allow_refund_time');
            $params['t_s_send_2'] = $this->getConfigData('suggest_refund_time');
            if($logisticsType == 'POST') {
                $params['t_b_rec_post'] = $this->getConfigData('post_receipt_timeout');
            } 
        }

        return Mage::helper('alipay')->buildRequestParams($params, $this->getSecurityCode());
    }

    public function getTitle() {
        return Mage::helper('alipay')->__('Alipay Secured Transactions');
    }
    
    public function processStatus($params) {
        if(empty($params["refund_status"])) {//Not refund transaction
            $this->processSecuredTransaction($params);
        } else {
            $this->processSecuredRefund($params['refund_status']);            
        }
    }

    public function canDelivery() {
        //After shipment, the order is complete.
        return $this->getOrder()->getStatus() == Mage::getSingleton('sales/order_config')->getStateDefaultStatus(Mage_Sales_Model_Order::STATE_COMPLETE);
    }
}
