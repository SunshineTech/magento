<?php

/**
 * SunshineBiz_Alipay pay controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_PayController extends Mage_Core_Controller_Front_Action {

    /**
     * Get singleton of Checkout Session Model
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckout() {
        return Mage::getSingleton('checkout/session');
    }

    protected function isMultishipping() {
        $_isMultishipping = false;
        if ($this->_getCheckout()->getAlipayPaymentOrderIds()) {
            $_isMultishipping = true;
        }

        return $_isMultishipping;
    }

    public function getOrder() {
        $session = $this->_getCheckout();
        $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
        if (($orderId = $session->getAlipayPaymentOrderId())) {
            $order = Mage::getModel('sales/order')->load($orderId);
        }

        if (!$order->getId()) {
            Mage::throwException('No order for processing found!');
        }

        if (!(($methodInstance = $order->getPayment()->getMethodInstance()) instanceof SunshineBiz_Alipay_Model_Payment)) {
            Mage::throwException('Payment method is dismatch!');
        }

        $methodInstance->setStore($order->getStore());

        if (!$methodInstance->isAvailable()) {
            Mage::throwException('Invalid payment method!');
        }

        if ($order->getStatus() != $methodInstance->getConfigData('order_status')) {
            Mage::throwException('The order can\'t be pay!');
        }

        return $order;
    }

    public function indexAction() {
        $session = $this->_getCheckout();
        if (!$session->getAlipayPaymentOrderIds() && ($orderIds = Mage::getSingleton('core/session')->getOrderIds())) {//from multishipping checkout
            $session->setAlipayPaymentOrderIds(array_keys($orderIds));
        } elseif (($orderId = (int) $this->getRequest()->getParam('order_id'))) {
            $session->setBackUrl($this->_getRefererUrl());
            $session->setAlipayPaymentOrderId($orderId);
        }

        try {
            if ($this->isMultishipping()) {
                $order = Mage::getModel('sales/order')->load($session->getAlipayPaymentOrderIds()->get(0));
            } else {
                $order = $this->getOrder();
            }

            Mage::register('order', $order);

            $this->loadLayout();
            $this->renderLayout();
        } catch (Exception $e) {
            $session->addError(Mage::helper('alipay')->__($e->getMessage()));
            if ($session->getAlipayPaymentOrderId()) {
                if (($url = $session->getBackUrl(true))) {
                    $this->_redirectUrl($url);
                } else {
                    $this->norouteAction();
                }
            } else {//from onepage checkout
                $this->_redirect('checkout/cart');
            }
        }
    }

    /**
     * 
     * 提交请求参数，跳转到支付宝
     */
    public function redirectAction() {

        $session = $this->_getCheckout();
        try {
            if ($this->isMultishipping()) {
                if (($orderId = (int) $this->getRequest()->getParam('order_id')) && in_array($orderId, $session->getAlipayPaymentOrderIds())) {
                    $session->setAlipayPaymentOrderId($orderId);
                } else {
                    Mage::throwException('Illegal request!');
                }
            }

            $order = $this->getOrder();
            $methodInstance = $order->getPayment()->getMethodInstance();
            $order->addStatusHistoryComment(Mage::helper('alipay')->__('The customer was redirected to %s.', $methodInstance->getRedirectComment()));
            $order->save();

            if (!$session->getAlipayPaymentOrderId()) {//from onepage checkout
                $session->setAlipayQuoteId($session->getQuoteId());
                $session->setAlipayRealOrderId($session->getLastRealOrderId());
                $session->getQuote()->setIsActive(false)->save();
                $session->clear();
            }

            //$this->_redirectUrl(Mage::helper('alipay')->getRequestUrl($methodInstance->getFormFields()));
            return $this->getResponse()->setBody($this->getLayout()->createBlock('alipay/pay_redirect')->setOrder($order)->toHtml());
        } catch (Exception $e) {
            $session->addError(Mage::helper('alipay')->__($e->getMessage()));
            $this->_redirect('*/*/index');
        }
    }

    /**
     * Action to which the customer will be returned when the payment is made.
     */
    public function successAction() {

        $session = $this->_getCheckout();
        try {
            Mage::getModel('alipay/payment')->log($_GET);
            $order = Mage::getModel('alipay/event')->setEventData($_GET)->successEvent();
            
            $fromMultishipping = false;
            if ($this->isMultishipping()) {
                $fromMultishipping = true;
                $orderIds = false;
                foreach ($session->getAlipayPaymentOrderIds(true) as $orderId) {
                    if ($orderId != $order->getId()) {
                        $orderIds[] = $orderId;
                    }
                }

                if ($orderIds) {
                    $session->setAlipayPaymentOrderIds($orderIds);
                }
            }

            if ($session->getAlipayPaymentOrderId()) {
                $session->unsetAlipayPaymentOrderId();
            } else {//from onepage checkout
                $session->setLastSuccessQuoteId($order->getQuoteId());
            }

            $session->addMessages(Mage::helper('alipay')->__('Paying for order %s is successful.', $order->getRealOrderId()));

            if ($this->isMultishipping()) {//continue paying for the Multishipping orders
                $this->_redirect('*/*/index');
            } else {
                if ($fromMultishipping) {
                    $this->_redirect('checkout/multishipping/success');
                } elseif ($session->getLastSuccessQuoteId()) {
                    $this->_redirect('checkout/onepage/success');
                } elseif ($url = $session->getBackUrl(true)) {
                    $this->_redirectUrl($url);
                } else {
                    $this->_redirectUrl(Mage::getBaseUrl());
                }
            }

            return;
        } catch (Mage_Core_Exception $e) {
            $session->addError(Mage::helper('alipay')->__($e->getMessage()));
        } catch (Exception $e) {
            Mage::logException($e);
        }

        if ($this->isMultishipping()) {//continue paying for the other orders
            $this->_redirect('*/*/index');
            return;
        }

        if ($session->getAlipayPaymentOrderId()) {
            $this->norouteAction();
        } else {
            $this->_redirect('checkout/cart');
        }
    }

    /**
     * Action to which the transaction details will be posted after the payment
     * process is complete.
     */
    public function statusAction() {
        $payment = Mage::getModel('alipay/payment');
        if ($payment->getConfigData('verify_ip')) {
            $notifyIPs = explode(',', $payment->getConfigData('notify_ips'));
            if (!in_array(Mage::helper('core/http')->getRemoteAddr(), $notifyIPs)) {
                return;
            }
        }

        $payment->log($_POST);
        
        $event = Mage::getModel('alipay/event')->setEventData($_POST);
        $message = $event->processStatusEvent();
        $this->getResponse()->setBody($message);
    }

    public function errorAction() {
        //TODO
    }

}
