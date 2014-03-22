<?php

/**
 * SunshineBiz_Twitter Account block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Twitter
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Twitter_Block_Account extends Mage_Core_Block_Template {

    protected $customer = null;

    protected function _prepareLayout() {

        $this->customer = Mage::registry('twitter_customer');

        $this->setChild(
                'socialconnect_account_twitter_button', 
                $this->helper('socialconnect')->getMethodButtonBlock(Mage::getSingleton('twitter/method'))
        );

        $this->setTemplate('twitter/account.phtml');

        return parent::_prepareLayout();
    }

    protected function _getStatus() {
        return '<a href="' . sprintf('https://twitter.com/%s', $this->customer->getScreenName()) . '" target="_blank">' . $this->htmlEscape($this->customer->getScreenName()) . '</a>';
    }

    protected function _getPicture() {
        if ($this->customer->getPictureUrl()) {
            $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . '/socialconnect/twitter/' . $this->customer->getId();
            $filename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . 'socialconnect' . DS . 'twitter' . DS . $this->customer->getId();

            return Mage::helper('socialconnect')->getProperDimensionsPictureUrl($url, $filename, str_replace('_normal', '', $this->customer->getPictureUrl()));
        }

        return null;
    }
}
