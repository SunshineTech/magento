<?php

/**
 * SunshineBiz_Twitter connect controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Twitter
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Twitter_ConnectController extends Mage_Core_Controller_Front_Action {

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
        // Cache user info inside customer session due to Twitter window frame rate limits
        if (!Mage::getSingleton('customer/session')->getTwitterCustomer()) {
            $twitterCustomer = Mage::getModel('twitter/customer')
                    ->getCollection()
                    ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                    ->getFirstItem();

            if ($twitterCustomer->getId() && $twitterCustomer->getToken()) {

                try {
                    $method = Mage::getSingleton('twitter/method');
                    $method->setAccessToken($twitterCustomer->getToken());
                    $userInfo = $method->api('/account/verify_credentials.json', 'GET', array('skip_status' => true));

                    $twitterCustomer->setToken($method->getAccessToken());
                    Mage::helper('google')->saveCustomer($twitterCustomer, $userInfo);
                    Mage::getSingleton('customer/session')->setTwitterCustomer($twitterCustomer);

                    Mage::getSingleton('core/session')->addSuccess(
                            $this->__('Connection had been refreshed.')
                    );
                } catch (SunshineBiz_Twitter_OAuthException $e) {
                    Mage::getSingleton('core/session')->addNotice($e->getMessage());
                } catch (Exception $e) {
                    Mage::getSingleton('core/session')->addError($e->getMessage());
                }
            }
        }

        $this->referer = Mage::getModel('core/url')->sessionUrlVar(Mage::getUrl('twitter/account/index'));
        $this->_redirectUrl($this->referer);
    }

    public function requestAction() {
        $method = Mage::getSingleton('twitter/method');
        if (!($method->isActive())) {
            Mage::helper('socialconnect')->redirect404($this);
        }

        $method->fetchRequestToken();
    }

    protected function _connectCallback() {

        if (!($params = $this->getRequest()->getParams()) || !($requestToken = unserialize(Mage::getSingleton('core/session')->getTwitterRequestToken()))) {
            // Direct route access - deny
            return;
        }

        $this->referer = Mage::getSingleton('core/session')->getTwitterRedirect();

        if (isset($params['denied'])) {
            Mage::getSingleton('core/session')->addNotice($this->__('Twitter Connect process aborted.'));

            return;
        }

        $method = Mage::getSingleton('twitter/method');

        $token = $method->getAccessToken();

        $userInfo = (object) array_merge(
                        (array) ($userInfo = $method->api('/account/verify_credentials.json', 'GET', array('skip_status' => true))), array('email' => sprintf('%s@twitter-user.com', strtolower($userInfo->screen_name)))
        );

        $twitterCustomer = Mage::getModel('twitter/customer')->load($userInfo->id);

        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            // Logged in user
            if ($twitterCustomer->getId() && $twitterCustomer->getCustomerId() != $customer->getId()) {
                // Twitter account already connected to other account - deny
                Mage::getSingleton('core/session')->addNotice($this->__('Your Twitter account is already connected to one of our store accounts.'));

                return;
            }

            // Connect from account dashboard - attach
            $twitterCustomer->setId($userInfo->id);
            $twitterCustomer->setToken($token);
            $twitterCustomer->setCustomerId($customer->getId());
            Mage::helper('twitter')->saveCustomer($twitterCustomer, $userInfo);

            Mage::getSingleton('core/session')->addSuccess(
                    $this->__('Your Twitter account is now connected to your store accout. You can now login using our Twitter Connect button or using store account credentials you will receive to your email address.')
            );

            return;
        }

        if ($twitterCustomer->getId()) {
            // Existing connected user - login
            $twitterCustomer->setToken($token);
            Mage::helper('twitter')->saveCustomer($twitterCustomer, $userInfo);

            $customer = Mage::getModel('customer/customer')->load($twitterCustomer->getCustomerId());

            Mage::helper('socialconnect')->loginByCustomer($customer);

            Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully logged in using your Twitter account.'));

            return;
        }

        $customersByEmail = Mage::helper('socialconnect')->getCustomersByEmail($userInfo->email);

        if ($customersByEmail->count()) {
            // Email account already exists - attach, login
            $customer = $customersByEmail->getFirstItem();

            $twitterCustomer->setId($userInfo->id);
            $twitterCustomer->setToken($token);
            $twitterCustomer->setCustomerId($customer->getId());
            Mage::helper('twitter')->saveCustomer($twitterCustomer, $userInfo);

            Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);

            Mage::getSingleton('core/session')->addSuccess($this->__('We have discovered you already have an account at our store. Your Twitter account is now connected to your store account.'));

            return;
        }

        // New connection - create, attach, login
        if (empty($userInfo->name)) {
            throw new Exception($this->__('Sorry, could not retrieve your Twitter last name. Please try again.'));
        }

        $customer = Mage::helper('twitter')->connectByCreatingAccount(
                $userInfo->email, $userInfo->name, $userInfo->id, $token
        );

        $twitterCustomer->setId($userInfo->id);
        $twitterCustomer->setToken($token);
        $twitterCustomer->setCustomerId($customer->getId());
        Mage::helper('twitter')->saveCustomer($twitterCustomer, $userInfo);

        Mage::getSingleton('core/session')->addSuccess(
                $this->__('Your Twitter account is now connected to your new user accout at our store. Now you can login using our Twitter Connect button.')
        );

        Mage::getSingleton('core/session')->addNotice(
                sprintf($this->__('Since Twitter doesn\'t support third-party access to your email address, we were unable to send you your store accout credentials. To be able to login using store account credentials you will need to update your email address and password using our <a href="%s">Edit Account Information</a>.'), Mage::getUrl('customer/account/edit'))
        );
    }

    protected function _disconnectCallback(Mage_Customer_Model_Customer $customer) {
        
        $this->referer = Mage::getModel('core/url')->sessionUrlVar(Mage::getUrl('twitter/account/index'));
        Mage::helper('twitter')->disconnect($customer);

        Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully disconnected your Twitter account from our store account.'));
    }

}
