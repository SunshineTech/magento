<?php

/**
 * Alipay notification processor model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Event {
    /*
     * @param Mage_Sales_Model_Order
     */

    protected $_order = null;

    /**
     * Event request data
     * @var array
     */
    protected $_eventData = array();

    /**
     * Get singleton of Checkout Session Model
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckout() {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Enent request data setter
     * @param array $data
     * @return Phoenix_Moneybookers_Model_Event
     */
    public function setEventData(array $data) {
        $this->_eventData = $data;
        return $this;
    }

    /**
     * Validate request
     * Can throw Mage_Core_Exception and Exception
     *
     * @return int
     */
    public function successEvent() {
        $this->_validateEventData(false);
        return $this->_order;
    }

    /**
     * Process status notification from Monebookers server
     *
     * @return String
     */
    public function processStatusEvent() {
        
        try {
            $params = $this->_validateEventData();
            $methodInstance = $this->_order->getPayment()->getMethodInstance();
            $methodInstance->processStatus($params);
            
            return 'success';
        } catch (Mage_Core_Exception $e) {
            return Mage::helper('alipay')->__($e->getMessage());
        }
    }

    protected function checkOrderId($fullCheck, $realOrderId) {
        if ($fullCheck == false) {
            $session = $this->_getCheckout();
            if (($orderId = $session->getAlipayPaymentOrderId())) {
                $alipayRealOrderId = Mage::getModel('sales/order')->load($orderId)->getRealOrderId();
            } else {
                $alipayRealOrderId = $session->getAlipayRealOrderId();
            }
            if ($alipayRealOrderId != $realOrderId) {
                Mage::throwException('Invalid order ID.');
            }
        }
    }

    /**
     * Checking returned parameters
     * Thorws Mage_Core_Exception if error
     * @param bool $fullCheck Whether to make additional validations such as payment status, transaction signature etc.
     *
     * @return array  $params request params
     */
    protected function _validateEventData($fullCheck = true) {
        // get request variables
        $params = $this->_eventData;Mage::log(empty($params["sign_type"]));
        if (empty($params) || empty($params["sign_type"]) || empty($params["sign"]) || empty($params["notify_id"]) || empty($params["trade_status"]) || empty($params["trade_no"]) || empty($params["out_trade_no"])) {
            Mage::throwException('Invalid Request.');
        }

        // check order ID
        $this->checkOrderId($fullCheck, $params["out_trade_no"]);

        // load order for further validation
        $this->_order = Mage::getModel('sales/order')->loadByIncrementId($params['out_trade_no']);
        if (!$this->_order->getId()) {
            Mage::throwException('Order not found.');
        }

        if (!(($methodInstance = $this->_order->getPayment()->getMethodInstance()) instanceof SunshineBiz_Alipay_Model_Payment)) {
            Mage::throwException('Unknown payment method.');
        }

        $methodInstance->setStore($this->_order->getStore());

        if (preg_match("/true$/i", Mage::helper('alipay')->verifyNotify($methodInstance->getPartnerId(), $params["notify_id"])) && Mage::helper('alipay')->verifySign($params, $methodInstance->getSecurityCode())) {
            Mage::throwException('Not Alipay notification or invalid sign.');
        }

        return $params;
    }

}
