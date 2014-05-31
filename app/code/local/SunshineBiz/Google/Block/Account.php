<?php

/**
 * SunshineBiz_Google Account block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Google
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Google_Block_Account extends Mage_Core_Block_Template {

    protected $customer = null;

    protected function _prepareLayout() {

        $this->customer = Mage::registry('google_customer');

        $this->setChild(
                'socialconnect_account_google_button', $this->helper('socialconnect')->getMethodButtonBlock(Mage::getSingleton('google/method'))
        );

        $this->setTemplate('sunshinebiz/google/account.phtml');

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
            $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . '/socialconnect/google/' . $this->customer->getId();
            $filename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . 'socialconnect' . DS . 'google' . DS . $this->customer->getId();

            return Mage::helper('socialconnect')->getProperDimensionsPictureUrl($url, $filename, $this->customer->getPictureUrl());
        }

        return null;
    }

    protected function _getBirthday() {
        if ($this->customer->getBirthday()) {
            if ((strpos($this->customer->getBirthday(), '0000')) === false) {
                $birthday = date('F j, Y', strtotime($this->customer->getBirthday()));
            } else {
                $birthday = date('F j', strtotime(str_replace('0000', '1970', $this->customer->getBirthday())));
            }

            return $birthday;
        }

        return null;
    }

}