<?php

/**
 * SocialConnect google method button block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Block_Google_Button extends SunshineBiz_SocialConnect_Block_Button {
    
    protected function _construct() {
        parent::_construct();
        
        $this->userInfo = Mage::registry('google_userinfo');
        $this->disconnectUrl = 'socialconnect/google/disconnect';
        
        $head = Mage::app()->getLayout()->getBlock('head');
        $head->addItem('skin_css', 'socialconnect/google/css/button.css');
     
        $this->setTemplate('socialconnect/google/button.phtml');
    }
    
    protected function setCsrf($csrf) {
        Mage::getSingleton('core/session')->setGoogleCsrf($csrf);
    }

    protected function setRedirect($redirect) {
        Mage::getSingleton('core/session')->setGoogleRedirect($redirect);
    }
}
