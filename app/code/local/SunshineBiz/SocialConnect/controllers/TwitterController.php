<?php

/**
 * SunshineBiz_SocialConnect twitter controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_TwitterController extends Mage_Core_Controller_Front_Action {

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

    public function requestAction() {
        $method = Mage::getSingleton('socialconnect/twitter_method');
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

        $method = Mage::getSingleton('socialconnect/twitter_method');

        $token = $method->getAccessToken();

        $userInfo = (object) array_merge(
                (array) ($userInfo = $method->api('/account/verify_credentials.json', 'GET', array('skip_status' => true))), 
                array('email' => sprintf('%s@twitter-user.com', strtolower($userInfo->screen_name)))
        );

        $customersByTwitterId = Mage::helper('socialconnect')->getCustomersByClientId('socialconnect_tid', $userInfo->id);

        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            // Logged in user
            if ($customersByTwitterId->count()) {
                // Twitter account already connected to other account - deny
                Mage::getSingleton('core/session')->addNotice($this->__('Your Twitter account is already connected to one of our store accounts.'));

                return;
            }

            // Connect from account dashboard - attach
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customer->setSocialconnectTid($userInfo->id)
                    ->setSocialconnectTtoken($token)
                    ->save();
            Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);

            Mage::getSingleton('core/session')->addSuccess(
                    $this->__('Your Twitter account is now connected to your store accout. You can now login using our Twitter Connect button or using store account credentials you will receive to your email address.')
            );

            return;
        }

        if ($customersByTwitterId->count()) {
            // Existing connected user - login
            $customer = $customersByTwitterId->getFirstItem();
            Mage::helper('socialconnect')->loginByCustomer($customer);

            Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully logged in using your Twitter account.'));

            return;
        }

        $customersByEmail = Mage::helper('socialconnect')->getCustomersByEmail($userInfo->email);

        if ($customersByEmail->count()) {
            // Email account already exists - attach, login
            $customer = $customersByEmail->getFirstItem();
            $customer->setSocialconnectTid($userInfo->id)
                    ->setSocialconnectTtoken($token)
                    ->save();
            Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);

            Mage::getSingleton('core/session')->addSuccess($this->__('We have discovered you already have an account at our store. Your Twitter account is now connected to your store account.'));

            return;
        }

        // New connection - create, attach, login
        if (empty($userInfo->name)) {
            throw new Exception($this->__('Sorry, could not retrieve your Twitter last name. Please try again.'));
        }

        Mage::helper('socialconnect/twitter')->connectByCreatingAccount(
                $userInfo->email, $userInfo->name, $userInfo->id, $token
        );

        Mage::getSingleton('core/session')->addSuccess(
                $this->__('Your Twitter account is now connected to your new user accout at our store. Now you can login using our Twitter Connect button.')
        );

        Mage::getSingleton('core/session')->addNotice(
                sprintf($this->__('Since Twitter doesn\'t support third-party access to your email address, we were unable to send you your store accout credentials. To be able to login using store account credentials you will need to update your email address and password using our <a href="%s">Edit Account Information</a>.'), Mage::getUrl('customer/account/edit'))
        );
    }
    
    protected function _disconnectCallback(Mage_Customer_Model_Customer $customer) {
        $this->referer = Mage::getUrl('socialconnect/account/twitter');  
        
        Mage::helper('socialconnect/twitter')->disconnect($customer);

        Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully disconnected your Twitter account from our store account.'));
    }

}
