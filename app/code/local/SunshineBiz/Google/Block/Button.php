<?php

/**
 * SunshineBiz_Google button block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Google
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Google_Block_Button extends SunshineBiz_SocialConnect_Block_Button {
    
    protected function _construct() {
        parent::_construct();
        
        $this->customer = Mage::registry('google_customer');
        $this->disconnectUrl = 'google/connect/disconnect';
        
        $head = Mage::app()->getLayout()->getBlock('head');
        $head->addItem('skin_css', 'sunshinebiz/google/css/button.css');
     
        $this->setTemplate('sunshinebiz/google/button.phtml');
    }
    
    protected function setCsrf($csrf) {
        Mage::getSingleton('core/session')->setGoogleCsrf($csrf);
    }

    protected function setRedirect($redirect) {
        Mage::getSingleton('core/session')->setGoogleRedirect($redirect);
    }
}