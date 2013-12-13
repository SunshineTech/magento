<?php

/**
 * SocialConnect facebook method button block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Block_Facebook_Button extends SunshineBiz_SocialConnect_Block_Button {
    
    protected function _construct() {
        parent::_construct();
        
        $this->userInfo = Mage::registry('facebook_userinfo');
        $this->disconnectUrl = 'socialconnect/facebook/disconnect';
        
        $head = Mage::app()->getLayout()->getBlock('head');
        $head->addItem('skin_css', 'socialconnect/facebook/css/button.css');
     
        $this->setTemplate('socialconnect/facebook/button.phtml');
    }
    
    protected function setCsrf($csrf) {
        Mage::getSingleton('core/session')->setFacebookCsrf($csrf);
    }

    protected function setRedirect($redirect) {
        Mage::getSingleton('core/session')->setFacebookRedirect($redirect);
    }
}
