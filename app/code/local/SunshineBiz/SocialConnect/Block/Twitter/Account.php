<?php

/**
 * SocialConnect twitter Account block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Block_Twitter_Account extends Mage_Core_Block_Template {

    protected $userInfo = null;

    protected function _prepareLayout() {

        $method = Mage::getSingleton('socialconnect/twitter_method');
        if (!($method->isActive())) {
            return;
        }

        $this->userInfo = Mage::registry('twitter_userinfo');

        $this->setChild(
                'socialconnect_account_twitter_button', 
                $this->helper('socialconnect')->getMethodButtonBlock($method)
        );

        $this->setTemplate('socialconnect/twitter/account.phtml');

        return parent::_prepareLayout();
    }

    protected function _hasUserInfo() {
        return (bool) $this->userInfo;
    }

    protected function _getTwitterId() {
        return $this->userInfo->id;
    }

    protected function _getStatus() {
        return '<a href="' . sprintf('https://twitter.com/%s', $this->userInfo->screen_name) . '" target="_blank">' . $this->htmlEscape($this->userInfo->screen_name) . '</a>';
    }

    protected function _getPicture() {
        if (!empty($this->userInfo->profile_image_url)) {
            $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . '/socialconnect/twitter/' . $this->userInfo->id;
            $filename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . 'socialconnect' . DS . 'twitter' . DS . $this->userInfo->id;

            return Mage::helper('socialconnect')->getProperDimensionsPictureUrl($url, $filename, str_replace('_normal', '', $this->userInfo->profile_image_url));
        }

        return null;
    }

    protected function _getName() {
        return $this->userInfo->name;
    }
}
