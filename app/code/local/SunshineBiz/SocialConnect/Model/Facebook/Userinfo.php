<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Model_Facebook_Userinfo {

    protected $userInfo = null;

    public function __construct() {
        if (!Mage::getSingleton('customer/session')->isLoggedIn())
            return;

        $method = Mage::getSingleton('socialconnect/facebook_method');

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        if (($socialconnectFid = $customer->getSocialconnectFid()) && ($socialconnectFtoken = $customer->getSocialconnectFtoken())) {
            $helper = Mage::helper('socialconnect/facebook');

            try {
                $method->setAccessToken($socialconnectFtoken);
                $this->userInfo = $method->api('/me', 'GET', array('fields' => 'id,name,first_name,last_name,link,birthday,gender,email,picture.type(large)'));
            } catch (SunshineBiz_SocialConnect_FacebookOAuthException $e) {
                $helper->disconnect($customer);
                Mage::getSingleton('core/session')->addNotice($e->getMessage());
            } catch (Exception $e) {
                $helper->disconnect($customer);
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
        }
    }

    public function getUserInfo() {
        return $this->userInfo;
    }

}