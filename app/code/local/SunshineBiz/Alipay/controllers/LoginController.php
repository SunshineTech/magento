<?php

/**
 * SunshineBiz_Alipay Login controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_LoginController extends Mage_Core_Controller_Front_Action {

    protected $referer = null;

    public function connectAction() {
        if (!(Mage::getSingleton('alipay/login')->isAvailable())) {
            return Mage::helper('socialconnect')->redirect404($this);
        }
        
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
        if (!(Mage::getSingleton('alipay/login')->isAvailable())) {
            return Mage::helper('socialconnect')->redirect404($this);
        }

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

    public function redirectAction() {
        if (!(Mage::getSingleton('alipay/login')->isAvailable())) {
            return Mage::helper('socialconnect')->redirect404($this);
        }
        
        return $this->getResponse()->setBody($this->getLayout()->createBlock('alipay/login_redirect')->toHtml());
    }

    protected function _connectCallback() {

        $state = $this->getRequest()->getParam('state');
        if (!$state || $state != Mage::getSingleton('core/session')->getAlipayCsrf()) {
            Mage::getSingleton('core/session')->unsAlipayCsrf();
            return;
        }
        Mage::getSingleton('core/session')->unsAlipayCsrf();

        if (empty($_GET)) {
            // Direct route access - deny
            return;
        }

        if (empty($_GET["is_success"]) || empty($_GET["sign_type"]) || empty($_GET["sign"]) || empty($_GET["notify_id"])) {
            return;
        }

        $this->referer = Mage::getSingleton('core/session')->getAlipayRedirect();

        if ($_GET["is_success"] != 'T') {
            throw new Exception(sprintf($this->__('Sorry, "%s" error occured. Please try again.'), $this->__($_GET["is_success"])));
            return;
        }

        $method = Mage::getSingleton('alipay/login');
        $isVerified = Mage::helper('alipay')->verifySign($_GET, $method->getClientSecret());
        $responseTxt = Mage::helper('alipay')->verifyNotify($method->getClientId(), $_GET["notify_id"]);

        if (preg_match("/true$/i", $responseTxt) && $isVerified) {
            if (empty($_GET['token'])) {
                throw new Exception($this->__('Sorry, could not retrieve Alipay token. Please try again.'));
                return;
            }

            if (empty($_GET['user_id']) || empty($_GET['real_name']) || empty($_GET['email'])) {
                throw new Exception($this->__('Sorry, could not retrieve Alipay user id, real name or email. Please try again.'));
                return;
            }

            $userInfo = array(
                'user_id' => $_GET['user_id'],
                'real_name' => $_GET['real_name'],
                'email' => $_GET['email'],
                'user_grade' => array_key_exists('user_grade', $_GET) ? $_GET['user_grade'] : '',
                'user_grade_type' => array_key_exists('user_grade_type', $_GET) ? $_GET['user_grade_type'] : '',
                'gmt_decay' => array_key_exists('gmt_decay', $_GET) ? $_GET['gmt_decay'] : '',
                'token' => $_GET['token'],
                'expired_time' => time() + 30 * 60
            );

            $alipayCustomer = Mage::getModel('alipay/customer')->load($userInfo['user_id']);

            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customer = Mage::getSingleton('customer/session')->getCustomer();
                // Logged in user
                if ($alipayCustomer->getId() && $alipayCustomer->getCustomerId() != $customer->getId()) {
                    // Alipay account already connected to other account - deny
                    Mage::getSingleton('core/session')->addNotice($this->__('Your Alipay account is already connected to one of our store accounts.'));

                    return;
                }

                // Connect from account dashboard - attach
                $alipayCustomer->setId($userInfo['user_id']);
                $alipayCustomer->setCustomerId($customer->getId());
                Mage::helper('alipay')->saveCustomer($alipayCustomer, $userInfo);

                Mage::getSingleton('core/session')->addSuccess(
                        $this->__('Your Alipay account is now connected to your store accout. You can now login using our Alipay Quick Login button or using store account credentials.')
                );

                return;
            }

            if ($alipayCustomer->getId()) {
                // Existing connected user - login
                Mage::helper('alipay')->saveCustomer($alipayCustomer, $userInfo);

                $customer = Mage::getModel('customer/customer')->load($alipayCustomer->getCustomerId());

                Mage::helper('socialconnect')->loginByCustomer($customer);

                Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully logged in using your Alipay account.'));

                return;
            }

            $customersByEmail = Mage::helper('socialconnect')->getCustomersByEmail($_GET['email']);
            if ($customersByEmail->count()) {
                // Email account already exists - attach, login
                $customer = $customersByEmail->getFirstItem();

                $alipayCustomer->setId($userInfo['user_id']);
                $alipayCustomer->setCustomerId($customer->getId());
                Mage::helper('alipay')->saveCustomer($alipayCustomer, $userInfo);

                Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);

                Mage::getSingleton('core/session')->addSuccess($this->__('We have discovered you already have an account at our store. Your Alipay account is now connected to your store account.'));

                return;
            }

            $customer = Mage::helper('alipay')->connectByCreatingAccount(
                    $_GET['email'], $_GET['real_name'], $_GET['user_id']
            );

            $alipayCustomer->setId($userInfo['user_id']);
            $alipayCustomer->setCustomerId($customer->getId());
            Mage::helper('alipay')->saveCustomer($alipayCustomer, $userInfo);

            Mage::getSingleton('core/session')->addSuccess(
                    $this->__('Your Alipay account is now connected to your new user accout at our store. Now you can login using our Alipay Quick Login button or using store account credentials you will receive to your email address.')
            );

            return;
        }

        throw new Exception($this->__('Not return of Alipay Quik Login or Alipay response errors.'));
    }

    protected function _disconnectCallback(Mage_Customer_Model_Customer $customer) {

        $this->referer = Mage::getUrl('alipay/account/login');
        Mage::getModel('alipay/customer')
                ->getCollection()
                ->addFieldToFilter('customer_id', $customer->getId())
                ->getFirstItem()
                ->delete();

        Mage::getSingleton('core/session')->addSuccess($this->__('You have successfully disconnected your Alipay account from our store account.'));
    }

}