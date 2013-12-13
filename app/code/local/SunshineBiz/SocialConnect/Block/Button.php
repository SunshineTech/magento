<?php

/**
 * SocialConnect method button base block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
abstract class SunshineBiz_SocialConnect_Block_Button extends Mage_Core_Block_Template {

    protected $userInfo = null;    
    protected $method = null;
    protected $disconnectUrl = null;
    
    protected function _getButtonText() {

        if (empty($this->userInfo)) {
            return $this->method->getConnectText();
        } else {
            return $this->method->getDisconnectText();
        }
    }

    protected function _getButtonUrl() {
        if (empty($this->userInfo)) {
            return $this->method->createAuthUrl();
        } else {
            return $this->getUrl($this->disconnectUrl);
        }
    }
    
    public function setMethod($method) {
        // CSRF protection
        $this->setCsrf($csrf = md5(uniqid(rand(), TRUE)));
        $method->setState($csrf);
        
        if(!($redirect = Mage::getSingleton('customer/session')->getBeforeAuthUrl())) {
            $redirect = Mage::helper('core/url')->getCurrentUrl();      
        }
        // Redirect uri
        $this->setRedirect($redirect);
        
        $this->method = $method;
    }
    
    protected abstract function setCsrf($csrf);
    
    protected abstract function setRedirect($redirect);
}
