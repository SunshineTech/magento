<?php

/**
 * SocialConnect alipay method button block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Block_Login_Button extends SunshineBiz_SocialConnect_Block_Button {
    
    protected function _construct() {
        parent::_construct();
        
        $this->customer = Mage::registry('alipay_customer');
        $this->disconnectUrl = 'alipay/login/disconnect';
        
        $head = Mage::app()->getLayout()->getBlock('head');
        $head->addItem('skin_css', 'sunshinebiz/alipay/login/css/button.css');
     
        $this->setTemplate('sunshinebiz/alipay/login/button.phtml');
    }
    
    protected function _getButtonText() {
        return $this->__('Quick Login');
    }
    
    protected function _getButtonUrl() {
        return $this->method->createAuthUrl();
    }

    protected function setCsrf($csrf) {
        Mage::getSingleton('core/session')->setAlipayCsrf($csrf);
    }

    protected function setRedirect($redirect) {
        Mage::getSingleton('core/session')->setAlipayRedirect($redirect);
    }
}
