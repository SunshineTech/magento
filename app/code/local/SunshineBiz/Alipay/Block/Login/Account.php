<?php

/**
 * SocialConnect alipay Account block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Block_Login_Account extends Mage_Core_Block_Template {

    protected $customer = null;

    protected function _prepareLayout() {

        $this->customer = Mage::registry('alipay_customer');

        $this->setChild(
                'socialconnect_account_alipay_button', $this->helper('socialconnect')->getMethodButtonBlock(Mage::getSingleton('alipay/login'))
        );

        $this->setTemplate('alipay/login/account.phtml');

        return parent::_prepareLayout();
    }
}
