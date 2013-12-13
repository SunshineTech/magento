<?php

/**
 * SocialConnect facebook Account block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Block_Facebook_Account extends Mage_Core_Block_Template {

    protected $userInfo = null;

    protected function _prepareLayout() {

        $method = Mage::getSingleton('socialconnect/facebook_method');
        if (!($method->isActive())) {
            return;
        }

        $this->userInfo = Mage::registry('facebook_userinfo');

        $this->setChild(
                'socialconnect_account_facebook_button', 
                $this->helper('socialconnect')->getMethodButtonBlock($method)
        );

        $this->setTemplate('socialconnect/facebook/account.phtml');
        
        return parent::_prepareLayout();
    }

    protected function _hasUserInfo() {
        return (bool) $this->userInfo;
    }

    protected function _getFacebookId() {
        return $this->userInfo->id;
    }

    protected function _getStatus() {
        if (!empty($this->userInfo->link)) {
            $link = '<a href="' . $this->userInfo->link . '" target="_blank">' .
                    $this->htmlEscape($this->userInfo->name) . '</a>';
        } else {
            $link = $this->userInfo->name;
        }

        return $link;
    }

    protected function _getEmail() {
        return $this->userInfo->email;
    }

    protected function _getPicture() {
        if (!empty($this->userInfo->picture)) {
            $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . '/socialconnect/facebook/' . $this->userInfo->id;
            $filename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . 'socialconnect' . DS . 'facebook' . DS . $this->userInfo->id;
            
            return Mage::helper('socialconnect')->getProperDimensionsPictureUrl($url, $filename, $this->userInfo->picture->data->url);
        }

        return null;
    }

    protected function _getName() {
        return $this->userInfo->name;
    }

    protected function _getGender() {
        if (!empty($this->userInfo->gender)) {
            return ucfirst($this->userInfo->gender);
        }

        return null;
    }

    protected function _getBirthday() {
        if (!empty($this->userInfo->birthday)) {
            $birthday = date('F j, Y', strtotime($this->userInfo->birthday));
            return $birthday;
        }

        return null;
    }

}
