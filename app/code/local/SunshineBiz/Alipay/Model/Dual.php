<?php

/**
 * Alipay Dual Function Transactions pay method
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Dual extends SunshineBiz_Alipay_Model_Payment {

    protected $_code = 'alipay_dual';
    protected $_canUseForMultishipping = false;

    protected function getLogisticsType() {

        $carrierCode = $this->getOrder()->getShippingMethod(true)->getCarrierCode();
        if (in_array($carrierCode, explode('_', $this->getConfigData('express')))) {
            return 'EXPRESS';
        }

        if (in_array($carrierCode, explode('_', $this->getConfigData('ems')))) {
            return 'EMS';
        }

        if (in_array($carrierCode, explode('_', $this->getConfigData('post')))) {
            return 'POST';
        }

        return 'EXPRESS';
    }

    public function getFormFields() {

        $params = parent::getFormFields();

        $params['service'] = 'trade_create_by_buyer';

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
            if ($logisticsType == 'POST') {
                $params['t_b_rec_post'] = $this->getConfigData('post_receipt_timeout');
            }
        }

        if ($this->getConfigData('antiphishing_timestamp_verify')) {
            $params['anti_phishing_key'] = Mage::helper('alipay')->queryTimestamp($this->getPartnerId());
        }

        return Mage::helper('alipay')->buildRequestParams($params, $this->getSecurityCode());
    }

    public function getTitle() {
        return Mage::helper('alipay')->__('Alipay Dual Function Transactions');
    }

    public function processStatus($params) {
        if (empty($params["refund_status"])) {//Not refund transaction
            $order = $this->getOrder();
            if ($params['trade_status'] == 'TRADE_FINISHED' && $order->getStatus() == 'alipay_wait_buyer_pay') {//Instant Transaction
                $this->_createInvoice($order);
                $order->addStatusHistoryComment(Mage::helper('alipay')->__('Buyer had paid. The transaction is success.'), 'alipay_trade_finished');
                $order->sendOrderUpdateEmail(true, Mage::helper('alipay')->__('Payment has been received, the order is processing.'));
                $order->setEmailSent(true);
                $order->save();
            } else {
                $this->processSecuredTransaction($params);
            }
        } else {
            $this->processSecuredRefund($params['refund_status']);
        }
    }

    public function canDelivery() {
        
        $order = $this->getOrder();
        foreach ($order->getStatusHistoryCollection as $history) {
            if ($history->getStatus() == 'alipay_wait_seller_send_goods') {//Secured Transaction
                $this->_canDelivery = true;
                break;
            }
        }
        //After shipment, the order is complete.
        return parent::canDelivery() && $order->getStatus() == Mage::getSingleton('sales/order_config')->getStateDefaultStatus(Mage_Sales_Model_Order::STATE_COMPLETE);
    }

}
