<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Model_Google_Userinfo {

    protected $userInfo = null;

    public function __construct() {
        
        if (!Mage::getSingleton('customer/session')->isLoggedIn())
            return;        

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        if (($socialconnectGid = $customer->getSocialconnectGid()) && ($socialconnectGtoken = $customer->getSocialconnectGtoken())) {
            $helper = Mage::helper('socialconnect/google');

            try {
                $method = Mage::getSingleton('socialconnect/google_method');
                $method->setAccessToken($socialconnectGtoken);

                $this->userInfo = $method->api('/userinfo');

                /* The access token may have been updated automatically due to
                 * access type 'offline' */
                $customer->setSocialconnectGtoken($method->getAccessToken());
                $customer->save();
            } catch (SunshineBiz_SocialConnect_GoogleOAuthException $e) {
                $helper->disconnect($customer);
                Mage::getSingleton('core/session')->addNotice(iconv("","UTF-8",$e->getMessage()));
            } catch (Exception $e) {
                $helper->disconnect($customer);
                Mage::getSingleton('core/session')->addError(iconv("","UTF-8",$e->getMessage()));
            }
        }
    }

    public function getUserInfo() {
        return $this->userInfo;
    }

}