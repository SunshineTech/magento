<?php

/**
 * Alipay pay method
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Payment extends Mage_Payment_Model_Method_Abstract {

    protected $_code = 'alipay';
    protected $_formBlockType = 'alipay/pay_form';
    // Payment configuration
    protected $_canCapture = true;
    protected $_canRefund = true;
    protected $_canDelivery = false;
    protected $_order;
    
    public function log($message, $level = Zend_Log::INFO) {
        Mage::log($message, $level, 'alipay.log');
    }

    /**
     * Get order model
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder() {
        if (!$this->_order) {
            $this->_order = $this->getInfoInstance()->getOrder();
        }

        return $this->_order;
    }

    /**
     *  Return Order Place Redirect URL
     *
     *  @return string Order Redirect URL
     */
    public function getOrderPlaceRedirectUrl() {
        return Mage::getUrl('alipay/pay/index');
    }

    public function getPartnerId() {
        return Mage::getStoreConfig('payment/alipay/partner_id', $this->getStore());
    }

    public function getSecurityCode() {
        return Mage::getStoreConfig('payment/alipay/security_code', $this->getStore());
    }

    public function getSellerEmail() {
        return Mage::getStoreConfig('payment/alipay/seller_email', $this->getStore());
    }

    /**
     *  Return Checkout Form Fields for request to Alipay
     *
     *  @return	  array Array of hidden form fields
     */
    public function getFormFields() {

        $order = $this->getOrder();

        $body = '';
        foreach ($order->getAllVisibleItems() as $item) {
            $body .= $item->getProduct()->getName() . ', ';
        }
        if (strlen($body) > 400) {
            $body = substr($body, 0, 400);
        }

        $params = array(
            'partner' => $this->getPartnerId(),
            '_input_charset' => 'utf-8',
            'sign_type' => 'MD5',
            'notify_url' => Mage::getUrl('alipay/pay/status'),
            'return_url' => Mage::getUrl('alipay/pay/success'),
            'out_trade_no' => $order->getRealOrderId(),
            'subject' => Mage::helper('alipay')->__('The order of %s: ', Mage::getStoreConfig('general/store_information/name', $order->getStoreId())) . $order->getRealOrderId(),
            'payment_type' => '1',
            'seller_email' => $this->getSellerEmail(),
            'price' => sprintf('%.2f', $order->getGrandTotal()),
            'quantity' => '1',
            'body' => $body,
            'show_url' => $order->getCustomerIsGuest() ? Mage::getUrl('sales/guest/form') : Mage::getUrl('sales/order/view', array('order_id' => $order->getId()))
        );

        $userInfo = Mage::helper('alipay')->getUserInfo();
        if ($userInfo) {
            $params['buyer_email'] = $userInfo->getEmail();
            $params['token'] = $userInfo->getToken();
        }

        return $params;
    }

    public function getRedirectComment() {
        return Mage::helper('alipay')->__('Alipay');
    }
    
    //Be ready for SunshineBiz_Sales
    public function getPayUrl() {
        $order = $this->getOrder();
        if($order->getStatus() == $this->getConfigData('order_status') || $order->getStatus() == 'alipay_wait_buyer_pay') {
            return 'alipay/pay/index'; 
        }
    }
    
    //Be ready for SunshineBiz_Checkout
    public function getMultishippingUrl() {
        return 'alipay/pay/index'; 
    }

    /**
     * Check delivery availability
     *
     * @return bool
     */
    public function canDelivery() {
        return $this->_canDelivery;
    }

    public function capture(Varien_Object $payment, $amount) {
        $payment->setStatus(self::STATUS_APPROVED)
                ->setLastTransId($this->getTransactionId());

        return $this;
    }

    protected function parseDeliveryResponse($node) {
        $array = false;

        foreach ($node->childNodes as $childNode) {
            if ($childNode->nodeType != XML_TEXT_NODE) {
                if ($childNode->childNodes->length == 1) {
                    if ($attValue = $childNode->getAttribute('name')) {
                        $array[$attValue] = $childNode->nodeValue;
                    } else {
                        $array[$childNode->nodeName] = $childNode->nodeValue;
                    }
                } else {
                    $array[$childNode->nodeName] = $this->parseDeliveryResponse($childNode);
                }
            }
        }

        return $array;
    }

    public function delivery() {
        $order = $this->getOrder();
        $params = array(
            'service' => 'send_goods_confirm_by_platform',
            'partner' => $this->getPartnerId(),
            '_input_charset' => 'utf-8',
            'sign_type' => 'MD5',
            'trade_no' => $order->getPayment()->getAdditionalInformation('trade_no'),
            'logistics_name' => $order->getShippingCarrier()->getConfigData('title')
        );
        
        $this->log(array_merge(array('orderId' => $order->getRealOrderId()), $params));
        $response = Mage::helper('alipay')->getHttpPostResponse(SunshineBiz_Alipay_Helper_Data::GATEWAY . "?_input_charset=" . $params['_input_charset'], Mage::helper('alipay')->buildRequestParams($params, $this->getSecurityCode()));
        $this->log('Order ' . $order->getRealOrderId() . ' Response of Delivery: ' . $response);
        $doc = new DOMDocument();        

        try {
            $doc->loadXML($response);
            $result = $this->parseDeliveryResponse($doc->getElementsByTagName("alipay")->item(0));
            if ($result['is_success'] == 'T') {
                $order->addStatusHistoryComment(Mage::helper('alipay')->__('Alipay had received the notification of delivery.'));
                $order->sendOrderUpdateEmail(true, Mage::helper('alipay')->__('Your order had already shipped completely, please confirm on Alipay as soon as possible after receiving goods.'));
                $order->setEmailSent(true);
                $order->save();
            } else {
                $order->addStatusHistoryComment(Mage::helper('alipay')->__('The notification of delivery has error, please contact technical personnel.'));
                $order->save();
                Mage::throwException('Alipay delivery notification error code: ' . $result['error']);
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    protected function setTransactionData($order, $params) {
        $payment = $order->getPayment();
        $payment->setAdditionalInformation('notify_time', $params['notify_time']);
        $payment->setAdditionalInformation('trade_no', $params['trade_no']);
        $payment->setAdditionalInformation('seller_email', $params['seller_email']);
        $payment->setAdditionalInformation('seller_id', $params['seller_id']);
        $payment->setAdditionalInformation('buyer_email', $params['buyer_email']);
        $payment->setAdditionalInformation('buyer_id', $params['buyer_id']);
        //$payment->save();
    }

    protected function _createInvoice($order) {
        if (!$order->canInvoice()) {
            return;
        }

        $invoice = $order->prepareInvoice();
        $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
        $invoice->register();

        $order->addRelatedObject($invoice);
    }

    protected function processInstantTransaction($params) {
        $order = $this->getOrder();
        switch ($params['trade_status']) {
            case 'TRADE_SUCCESS':
                if ($order->getStatus() == $this->getConfigData('order_status')) {
                    $this->setTransactionData($order, $params);
                    $this->_createInvoice($order);
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Buyer had paid. The transaction is success.'), 'alipay_trade_success');
                    $order->sendOrderUpdateEmail(true, Mage::helper('alipay')->__('Payment has been received, the order is processing.'));
                    $order->setEmailSent(true);
                    $order->save();
                }
                break;
            case 'TRADE_FINISHED':
                if ($order->getStatus() == $this->getConfigData('order_status')) {//Normal Instant
                    $this->setTransactionData($order, $params);
                    $this->_createInvoice($order);
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Buyer had paid. The transaction is success.'), 'alipay_trade_finished');
                    $order->sendOrderUpdateEmail(true, Mage::helper('alipay')->__('Payment has been received, the order is processing.'));
                    $order->setEmailSent(true);
                    $order->save();
                } elseif ($order->getStatus() == Mage::getSingleton('sales/order_config')->getStateDefaultStatus(Mage_Sales_Model_Order::STATE_COMPLETE)) {
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('The transaction is finished.'), 'alipay_trade_finished');
                    $order->save();
                }
                break;
            case 'TRADE_CLOSED':
                if ($order->getStatus() == $this->getConfigData('order_status')) {
                    $order->cancel();
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Payment is timeout. The transaction is canceled.'));
                    $order->sendOrderUpdateEmail(true, Mage::helper('alipay')->__('Payment is timeout. The order is canceled.'));
                    $order->setEmailSent(true);
                    $order->save();
                }
                break;
            case 'TRADE_PENDING':
                $order->addStatusHistoryComment(Mage::helper('alipay')->__('Payee account is locked or not activated, please log in Alipay to activate the account.'));
                $order->save();
                break;
        }
    }

    protected function _createCreditmemo($order) {
        if (!$order->canCreditmemo()) {
            return;
        }

        $creditmemo = Mage::getModel('sales/service_order', $order)->prepareCreditmemo();
        $creditmemo->register();

        $order->addRelatedObject($creditmemo);
    }

    protected function processSecuredRefund($refundStatus) {
        $order = $this->getOrder();
        switch ($refundStatus) {
            case 'WAIT_SELLER_AGREE':
                if ($order->getStatus() == 'alipay_wait_seller_send_goods' || $order->getStatus() == 'alipay_wait_buyer_confirm_goods') {
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Buyers had applied for a refund. Please operate (agree or refuse) on Alipay.'));
                    $order->save();
                }
                break;
            case 'WAIT_BUYER_RETURN_GOODS':
                if ($order->getStatus() == 'alipay_wait_buyer_confirm_goods') {
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Please wait for buyer to return goods.'));
                    $order->save();
                }
                break;
            case 'WAIT_SELLER_CONFIRM_GOODS':
                if ($order->getStatus() == 'alipay_wait_buyer_confirm_goods') {
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Buyer had returned goods. After receiving returned goods, Please refund on Alipay.'));
                    $order->save();
                }
                break;
            case 'REFUND_SUCCESS':
                if ($order->getStatus() == 'alipay_wait_seller_send_goods' || $order->getStatus() == 'alipay_wait_buyer_confirm_goods') {
                    $this->_createCreditmemo($order);
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Refund is successful, the transaction is closed.'));
                    $order->save();
                }
                break;
            case 'SELLER_REFUSE_BUYER':
                if ($order->getStatus() == 'alipay_wait_seller_send_goods' || $order->getStatus() == 'alipay_wait_buyer_confirm_goods') {
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Refused to refund.'));
                    $order->save();
                }
                break;
        }
    }

    protected function processSecuredTransaction($params) {
        $order = $this->getOrder();
        switch ($params['trade_status']) {
            case 'WAIT_BUYER_PAY':
                if ($order->getStatus() == $this->getConfigData('order_status')) {
                    $this->setTransactionData($order, $params);
                    $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, 'alipay_wait_buyer_pay', Mage::helper('alipay')->__('Waiting buyer for paying.'));
                    $order->save();
                }
                break;
            case 'WAIT_SELLER_SEND_GOODS':
                if ($order->getStatus() == 'alipay_wait_buyer_pay') {
                    $this->_createInvoice($order);
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Buyer is waitting for sending goods what had been paid.', 'alipay_wait_seller_send_goods'));
                    $order->sendOrderUpdateEmail(true, Mage::helper('alipay')->__('Payment has been received, the order is processing.'));
                    $order->setEmailSent(true);
                    $order->save();
                }
                break;
            case 'WAIT_BUYER_CONFIRM_GOODS'://After shipment, the order is complete.
                if ($order->getStatus() == Mage::getSingleton('sales/order_config')->getStateDefaultStatus(Mage_Sales_Model_Order::STATE_COMPLETE)) {
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Waiting buyer for receiving goods.'), 'alipay_wait_buyer_confirm_goods');
                    $order->save();
                }
                break;
            case 'TRADE_FINISHED':
                if ($order->getStatus() == 'alipay_wait_buyer_confirm_goods') {
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Buyer has received goods, the transaction is complete.', 'alipay_trade_finished'));
                    $order->save();
                }
            case 'TRADE_CLOSED':
                if ($order->getStatus() == $this->getConfigData('order_status') && $order->getStatus() == 'alipay_wait_buyer_pay') {
                    $order->cancel();
                    $order->addStatusHistoryComment(Mage::helper('alipay')->__('Payment is timeout. The transaction is canceled.'));
                    $order->sendOrderUpdateEmail(true, Mage::helper('alipay')->__('Payment is timeout. The order is canceled.'));
                    $order->setEmailSent(true);
                    $order->save();
                }
                break;
            case 'TRADE_PENDING':
                $order->addStatusHistoryComment(Mage::helper('alipay')->__('Payee account is locked or not activated, please log in Alipay to activate the account.'));
                $order->sendOrderUpdateEmail(false, Mage::helper('alipay')->__('Payee account is locked or not activated, please log in Alipay to activate the account.'));
                $order->setEmailSent(true);
                $order->save();
                break;
        }
    }

    public function isAvailable($quote = null) {
        return $this->getPartnerId() && $this->getSecurityCode() && $this->getSellerEmail() && parent::isAvailable($quote);
    }

}
