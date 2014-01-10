<?php

/**
 * SunshineBiz_Twitter button block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Twitter
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Twitter_Block_Button extends SunshineBiz_SocialConnect_Block_Button {
    
    protected function _construct() {
        parent::_construct();
        
        $this->customer = Mage::registry('twitter_customer');
        $this->disconnectUrl = 'twitter/connect/disconnect';
        
        $head = Mage::app()->getLayout()->getBlock('head');
        $head->addItem('skin_css', 'twitter/css/button.css');
     
        $this->setTemplate('twitter/button.phtml');
    }
    
    protected function setCsrf($csrf) {
        Mage::getSingleton('core/session')->setTwitterCsrf($csrf);
    }

    protected function setRedirect($redirect) {
        Mage::getSingleton('core/session')->setTwitterRedirect($redirect);
    }
}
