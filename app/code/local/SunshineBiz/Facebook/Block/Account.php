<?php

/**
 * SunshineBiz_Facebook Account block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Facebook
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Facebook_Block_Account extends Mage_Core_Block_Template {

    protected $customer = null;

    protected function _prepareLayout() {

        $method = Mage::getSingleton('facebook/method');
        if (!($method->isActive())) {
            return;
        }

        $this->customer = Mage::registry('facebook_customer');

        $this->setChild(
                'socialconnect_account_facebook_button', $this->helper('socialconnect')->getMethodButtonBlock($method)
        );

        $this->setTemplate('facebook/account.phtml');

        return parent::_prepareLayout();
    }

    protected function _getStatus() {
        if ($this->customer->getLink()) {
            $link = '<a href="' . $this->customer->getLink() . '" target="_blank">' .
                    $this->htmlEscape($this->customer->getName()) . '</a>';
        } else {
            $link = $this->customer->getName();
        }

        return $link;
    }

    protected function _getPicture() {
        if ($this->customer->getPictureUrl()) {
            $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . '/socialconnect/facebook/' . $this->customer->getId();
            $filename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . 'socialconnect' . DS . 'facebook' . DS . $this->customer->getId();

            return Mage::helper('socialconnect')->getProperDimensionsPictureUrl($url, $filename, $this->customer->getPictureUrl());
        }

        return null;
    }

    protected function _getBirthday() {
        if ($this->customer->getBirthday()) {
            $birthday = date('F j, Y', strtotime($this->customer->getBirthday()));
            return $birthday;
        }

        return null;
    }

}
