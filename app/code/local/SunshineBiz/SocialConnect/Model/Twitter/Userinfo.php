<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Model_Twitter_Userinfo {

    protected $userInfo = null;

    public function __construct() {

        if (!Mage::getSingleton('customer/session')->isLoggedIn())
            return;

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        if (($socialconnectTid = $customer->getSocialconnectTid()) && ($socialconnectTtoken = $customer->getSocialconnectTtoken())) {
            $helper = Mage::helper('socialconnect/twitter');

            try {
                $method = Mage::getSingleton('socialconnect/twitter_method');
                $method->setAccessToken($socialconnectTtoken);

                $this->userInfo = $method->api('/account/verify_credentials.json', 'GET', array('skip_status' => true));
            } catch (SunshineBiz_SocialConnect_TwitterOAuthException $e) {
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
