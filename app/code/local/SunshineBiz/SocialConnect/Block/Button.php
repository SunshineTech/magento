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

    protected $customer = null;
    protected $method = null;
    protected $disconnectUrl = null;

    protected function _getButtonText() {
        if ($this->customer) {
            $text = $this->__('Disconnect');
        } else {
            if (!($text = Mage::registry('sunshinetech_socialconnect_button_text'))) {
                $text = $this->__('Connect');
            }            
        }

        return $text;
    }

    protected function _getButtonUrl() {
        if ($this->customer) {
            return $this->getUrl($this->disconnectUrl);            
        } else {
            return $this->method->createAuthUrl();
        }
    }

    public function setMethod($method) {
        // CSRF protection
        $this->setCsrf($csrf = md5(uniqid(rand(), TRUE)));
        $method->setState($csrf);

        if (!($redirect = Mage::getSingleton('customer/session')->getBeforeAuthUrl())) {
            $redirect = Mage::helper('core/url')->getCurrentUrl();
        }
        // Redirect uri
        $this->setRedirect($redirect);

        $this->method = $method;
    }

    protected abstract function setCsrf($csrf);

    protected abstract function setRedirect($redirect);
}
