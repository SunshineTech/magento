<?php

/**
 * SunshineBiz_Facebook button block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Facebook
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Facebook_Block_Button extends SunshineBiz_SocialConnect_Block_Button {
    
    protected function _construct() {
        parent::_construct();
        
        $this->customer = Mage::registry('facebook_customer');
        $this->disconnectUrl = 'facebook/connect/disconnect';
        
        $head = Mage::app()->getLayout()->getBlock('head');
        $head->addItem('skin_css', 'sunshinebiz/facebook/css/button.css');
     
        $this->setTemplate('sunshinebiz/facebook/button.phtml');
    }
    
    protected function setCsrf($csrf) {
        Mage::getSingleton('core/session')->setFacebookCsrf($csrf);
    }

    protected function setRedirect($redirect) {
        Mage::getSingleton('core/session')->setFacebookRedirect($redirect);
    }
}
