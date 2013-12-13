<?php

/**
 * SunshineBiz_SocialConnect account controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_AccountController extends Mage_Core_Controller_Front_Action {

    public function preDispatch() {
        parent::preDispatch();

        if (!$this->getRequest()->isDispatched()) {
            return;
        }

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }

    public function googleAction() {
        $userInfo = Mage::getSingleton('socialconnect/google_userinfo')->getUserInfo();
        Mage::register('google_userinfo', $userInfo);

        $this->loadLayout();
        $this->renderLayout();
    }

    public function facebookAction() {
        $userInfo = Mage::getSingleton('socialconnect/facebook_userinfo')->getUserInfo();
        Mage::register('facebook_userinfo', $userInfo);

        $this->loadLayout();
        $this->renderLayout();
    }

    public function twitterAction() {
        // Cache user info inside customer session due to Twitter window frame rate limits
        if (!($userInfo = Mage::getSingleton('customer/session')->getSocialconnectTwitterUserinfo())) {
            $userInfo = Mage::getSingleton('socialconnect/twitter_userinfo')->getUserInfo();

            Mage::getSingleton('customer/session')->setSocialconnectTwitterUserinfo($userInfo);
        }
        
        Mage::register('twitter_userinfo', $userInfo);
        
        $this->loadLayout();
        $this->renderLayout();
    }

}
