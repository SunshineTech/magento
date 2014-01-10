<?php

/**
 * SunshineBiz_Google connect controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Google
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Google_ConnectController extends Mage_Core_Controller_Front_Action {

    protected $referer = null;

    public function connectAction() {

        try {
            $this->_connectCallback();
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }

        if (!empty($this->referer)) {
            $this->_redirectUrl($this->referer);
        } else {
            Mage::helper('socialconnect')->redirect404($this);
        }
    }

    public function disconnectAction() {

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        try {
            $this->_disconnectCallback($customer);
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }

        if (!empty($this->referer)) {
            $this->_redirectUrl($this->referer);
        } else {
            Mage::helper('socialconnect')->redirect404($this);
        }
    }

    public function refreshAction() {

        $googleCustomer = Mage::getModel('google/customer')
                ->getCollection()
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                ->getFirstItem();

        if ($googleCustomer->getId() && $googleCustomer->getToken()) {

            try {
                $method = Mage::getSingleton('google/method');
                $method->setAccessToken($googleCustomer->getToken());

                $userInfo = $method->api('/userinfo');

                /* The access token may have been updated automatically due to
                 * access type 'offline' */     
                $googleCustomer->setToken($method->getAccessToken());
                Mage::helper('google')->saveCustomer($googleCustomer, $userInfo);
                
                Mage::getSingleton('core/session')->addSuccess(
                        $this->__('Connection had been refreshed.')
                );
            } catch (SunshineBiz_Google_OAuthException $e) {
                Mage::getSingleton('core/session')->addNotice($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
        }

        $this->referer = Mage::getModel('core/url')->sessionUrlVar(Mage::getUrl('google/account/index'));
        $this->_redirectUrl($this->referer);
    }

    protected function _connectCallback() {

        $errorCode = $this->getRequest()->getParam('error');
        $code = $this->getRequest()->getParam('code');
        $state = $this->getRequest()->getParam('state');
        if (!($errorCode || $code) && !$state) {
            // Direct route access - deny
            return;
        }

        if (!$state || $state != Mage::getSingleton('core/session')->getGoogleCsrf()) {
            return;
        }
        Mage::getSingleton('core/session')->unsGoogleCsrf();

        $this->referer = Mage::getSingleton('core/session')->getGoogleRedirect();

        if ($errorCode) {
            // Google API read light - abort
            if ($errorCode === 'access_denied') {
                Mage::getSingleton('core/session')->addNotice($this->__('Google Connect process aborted.'));

                return;
            }

            throw new Exception(sprintf($this->__('Sorry, "%s" error occured. Please try again.'), $errorCode));

            return;
        }

        if ($code) {
            // Google API green light - proceed
            $method = Mage::getSingleton('google/method');

            $userInfo = $method->api('/userinfo');            
            $token = $method->getAccessToken();

            $googleCustomer = Mage::getModel('google/customer')->load($userInfo->id);

            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customer = Mage::getSingleton('customer/session')->getCustomer();
                // Logged in user
                if ($googleCustomer->getId() && $googleCustomer->getCustomerId() != $customer->getId()) {
                    // Google account already connected to other account - deny
                    Mage::getSingleton('core/session')->addNotice($this->__('Your Google account is already connected to one of our store accounts.'));

                    return;
                }

                // Connect from account dashboard - attach
                $googleCustomer->setId($userInfo->id);
                $googleCustomer->setToken($token);
                $googleCustomer->setCustomerId($customer->getId());
                Mage::helper('google')->saveCustomer($googleCustomer, $userInfo);

                Mage::getSingleton('core/session')->addSuccess(
                        $this->__('Your Google account is now connected to your store accout. You can now login using our Google Connect button or using store account credentials you will receive to your email address.')
                );

                return;
            }

            if ($googleCustomer->getId()) {
                // Existing connected user - login
                $googleCustomer->setToken($token);
                Mage::helper('google')->saveCustomer($googleCustomer, $userInfo);

                $customer = Mage::getModel('customer/customer')->load($googleCustomer->getCustomerId());

                Mage::helper('socialconnect')->loginByCustomer($customer);

                Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully logged in using your Google account.'));

                return;
            }

            $customersByEmail = Mage::helper('socialconnect')->getCustomersByEmail($userInfo->email);

            if ($customersByEmail->count()) {
                // Email account already exists - attach, login
                $customer = $customersByEmail->getFirstItem();

                $googleCustomer->setId($userInfo->id);
                $googleCustomer->setToken($token);
                $googleCustomer->setCustomerId($customer->getId());
                Mage::helper('google')->saveCustomer($googleCustomer, $userInfo);

                Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);

                Mage::getSingleton('core/session')->addSuccess($this->__('We have discovered you already have an account at our store. Your Google account is now connected to your store account.'));

                return;
            }

            // New connection - create, attach, login
            if (empty($userInfo->given_name)) {
                throw new Exception($this->__('Sorry, could not retrieve your Google first name. Please try again.'));
            }

            if (empty($userInfo->family_name)) {
                throw new Exception($this->__('Sorry, could not retrieve your Google last name. Please try again.'));
            }

            $customer = Mage::helper('google')->connectByCreatingAccount($userInfo->email, $userInfo->given_name, $userInfo->family_name);

            $googleCustomer->setId($userInfo->id);
            $googleCustomer->setToken($token);
            $googleCustomer->setCustomerId($customer->getId());
            Mage::helper('google')->saveCustomer($googleCustomer, $userInfo);

            Mage::getSingleton('core/session')->addSuccess(
                    $this->__('Your Google account is now connected to your new user accout at our store. Now you can login using our Google Connect button or using store account credentials you will receive to your email address.')
            );
        }
    }

    protected function _disconnectCallback(Mage_Customer_Model_Customer $customer) {

        $this->referer = Mage::getModel('core/url')->sessionUrlVar(Mage::getUrl('google/account/index'));
        Mage::helper('google')->disconnect($customer);

        Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully disconnected your Google account from our store account.'));
    }

}
