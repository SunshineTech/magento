<?php

/**
 * SunshineBiz_Checkout Multishipping controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Checkout
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
require_once 'Mage/Checkout/controllers/MultishippingController.php';

class SunshineBiz_Checkout_MultishippingController extends Mage_Checkout_MultishippingController {
    
    protected function getMultishippingUrl() {
        foreach ($this->_getCheckout()->getOrderIds() as $orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            $methodInstance = $order->getPayment()->getMethodInstance();
            $methodInstance->setStore($order->getStore());
            
            return $methodInstance->getMultishippingUrl();
        }
    }
    
    /**
     * Multishipping checkout after the overview page
     */
    public function overviewPostAction() {
        if (!$this->_validateFormKey()) {
            $this->_forward('backToAddresses');
            return;
        }

        if (!$this->_validateMinimumAmount()) {
            return;
        }

        try {
            if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                    $this->_getCheckoutSession()->addError($this->__('Please agree to all Terms and Conditions before placing the order.'));
                    $this->_redirect('*/*/billing');
                    return;
                }
            }

            $payment = $this->getRequest()->getPost('payment');
            $paymentInstance = $this->_getCheckout()->getQuote()->getPayment();
            if (isset($payment['cc_number'])) {
                $paymentInstance->setCcNumber($payment['cc_number']);
            }
            if (isset($payment['cc_cid'])) {
                $paymentInstance->setCcCid($payment['cc_cid']);
            }
            $this->_getCheckout()->createOrders();
            $this->_getState()->setActiveStep(
                    Mage_Checkout_Model_Type_Multishipping_State::STEP_SUCCESS
            );
            $this->_getState()->setCompleteStep(
                    Mage_Checkout_Model_Type_Multishipping_State::STEP_OVERVIEW
            );
            $this->_getCheckout()->getCheckoutSession()->clear();
            $this->_getCheckout()->getCheckoutSession()->setDisplaySuccess(true);
            
            if(($multishippingUrl = $this->getMultishippingUrl())) {
                $this->_redirect($multishippingUrl);
            } else {
                $this->_redirect('*/*/success');
            }            
        } catch (Mage_Payment_Model_Info_Exception $e) {
            $message = $e->getMessage();
            if (!empty($message)) {
                $this->_getCheckoutSession()->addError($message);
            }
            $this->_redirect('*/*/billing');
        } catch (Mage_Checkout_Exception $e) {
            Mage::helper('checkout')
                    ->sendPaymentFailedEmail($this->_getCheckout()->getQuote(), $e->getMessage(), 'multi-shipping');
            $this->_getCheckout()->getCheckoutSession()->clear();
            $this->_getCheckoutSession()->addError($e->getMessage());
            $this->_redirect('*/cart');
        } catch (Mage_Core_Exception $e) {
            Mage::helper('checkout')
                    ->sendPaymentFailedEmail($this->_getCheckout()->getQuote(), $e->getMessage(), 'multi-shipping');
            $this->_getCheckoutSession()->addError($e->getMessage());
            $this->_redirect('*/*/billing');
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')
                    ->sendPaymentFailedEmail($this->_getCheckout()->getQuote(), $e->getMessage(), 'multi-shipping');
            $this->_getCheckoutSession()->addError($this->__('Order place error.'));
            $this->_redirect('*/*/billing');
        }
    }

}
