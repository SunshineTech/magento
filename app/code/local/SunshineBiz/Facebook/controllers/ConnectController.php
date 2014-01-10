<?php

/**
 * SunshineBiz_Facebook connect controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Facebook
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Facebook_ConnectController extends Mage_Core_Controller_Front_Action {

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
        
        $facebookCustomer = Mage::getModel('facebook/customer')
                ->getCollection()
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                ->getFirstItem();
        
        if ($facebookCustomer->getId() && $facebookCustomer->getToken()) {
            try {
                $method = Mage::getSingleton('facebook/method');
                $method->setAccessToken($facebookCustomer->getToken());
                $userInfo = $method->api('/me', 'GET', array('fields' => 'id,name,first_name,last_name,link,birthday,gender,email,picture.type(large)'));
                
                $facebookCustomer->setToken($method->getAccessToken());
                Mage::helper('facebook')->saveCustomer($facebookCustomer, $userInfo);
                
                Mage::getSingleton('core/session')->addSuccess(
                        $this->__('Connection had been refreshed.')
                );
            } catch (SunshineBiz_Facebook_OAuthException $e) {
                Mage::getSingleton('core/session')->addNotice($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
        }
        
        $this->referer = Mage::getModel('core/url')->sessionUrlVar(Mage::getUrl('facebook/account/index'));
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
        
        if (!$state || $state != Mage::getSingleton('core/session')->getFacebookCsrf()) {
            return;
        }
        Mage::getSingleton('core/session')->unsFacebookCsrf();

        $this->referer = Mage::getSingleton('core/session')->getFacebookRedirect();       
        
        if ($errorCode) {
            // Facebook API read light - abort
            if ($errorCode === 'access_denied') {
                Mage::getSingleton('core/session')->addNotice($this->__('Facebook Connect process aborted.'));

                return;
            }

            throw new Exception(sprintf($this->__('Sorry, "%s" error occured. Please try again.'), $errorCode));

            return;
        }

        if ($code) {
            // Facebook API green light - proceed
            $method = Mage::getSingleton('facebook/method');

            $userInfo = $method->api('/me');
            $token = $method->getAccessToken();

            $facebookCustomer = Mage::getModel('facebook/customer')->load($userInfo->id);

            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customer = Mage::getSingleton('customer/session')->getCustomer();
                // Logged in user
                if ($facebookCustomer->getId() && $facebookCustomer->getCustomerId() != $customer->getId()) {
                    // Facebook account already connected to other account - deny
                    Mage::getSingleton('core/session')
                            ->addNotice($this->__('Your Facebook account is already connected to one of our store accounts.'));

                    return;
                }

                // Connect from account dashboard - attach
                $facebookCustomer->setId($userInfo->id);
                $facebookCustomer->setToken($token);
                $facebookCustomer->setCustomerId($customer->getId());
                Mage::helper('facebook')->saveCustomer($facebookCustomer, $userInfo);

                Mage::getSingleton('core/session')->addSuccess(
                        $this->__('Your Facebook account is now connected to your store accout. You can now login using our Facebook Connect button or using store account credentials you will receive to your email address.')
                );

                return;
            }

            if ($facebookCustomer->getId()) {
                // Existing connected user - login
                $facebookCustomer->setToken($token);
                Mage::helper('facebook')->saveCustomer($facebookCustomer, $userInfo);
                
                $customer = Mage::getModel('customer/customer')->load($facebookCustomer->getCustomerId());

                Mage::helper('socialconnect')->loginByCustomer($customer);

                Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully logged in using your Facebook account.'));

                return;
            }

            $customersByEmail = Mage::helper('socialconnect')->getCustomersByEmail($userInfo->email);

            if ($customersByEmail->count()) {
                // Email account already exists - attach, login
                $customer = $customersByEmail->getFirstItem();
                
                $facebookCustomer->setId($userInfo->id);
                $facebookCustomer->setToken($token);
                $facebookCustomer->setCustomerId($customer->getId());
                Mage::helper('facebook')->saveCustomer($facebookCustomer, $userInfo);

                Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);

                Mage::getSingleton('core/session')->addSuccess(
                        $this->__('We have discovered you already have an account at our store. Your Facebook account is now connected to your store account.')
                );

                return;
            }

            // New connection - create, attach, login
        if (empty($userInfo->first_name)) {
            throw new Exception($this->__('Sorry, could not retrieve your Facebook first name. Please try again.'));
        }

            if (empty($userInfo->last_name)) {
                throw new Exception($this->__('Sorry, could not retrieve your Facebook last name. Please try again.'));
            }

            $customer = Mage::helper('facebook')->connectByCreatingAccount(
                    $userInfo->email, $userInfo->first_name, $userInfo->last_name, $userInfo->id, $token
            );
            
            $facebookCustomer->setId($userInfo->id);
            $facebookCustomer->setToken($token);
            $facebookCustomer->setCustomerId($customer->getId());
            Mage::helper('facebook')->saveCustomer($facebookCustomer, $userInfo);

            Mage::getSingleton('core/session')->addSuccess(
                    $this->__('Your Facebook account is now connected to your new user accout at our store. Now you can login using our Facebook Connect button or using store account credentials you will receive to your email address.')
            );
        }
    }

    protected function _disconnectCallback(Mage_Customer_Model_Customer $customer) {

        $this->referer = Mage::getModel('core/url')->sessionUrlVar(Mage::getUrl('facebook/account/index'));
        Mage::helper('facebook')->disconnect($customer);

        Mage::getSingleton('core/session')
                ->addSuccess($this->__('You have successfully disconnected your Facebook account from our store account.'));
    }

}
