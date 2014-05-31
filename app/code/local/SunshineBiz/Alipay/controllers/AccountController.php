<?php

/**
 * SunshineBiz_Alipay account controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
require_once 'SunshineBiz/SocialConnect/controllers/AccountController.php';

class SunshineBiz_Alipay_AccountController extends SunshineBiz_SocialConnect_AccountController {

    public function loginAction() {
        if (!(Mage::getSingleton('alipay/login')->isAvailable())) {
            return Mage::helper('socialconnect')->redirect404($this);
        }
        
        $alipayCustomer = Mage::getModel('alipay/customer')
                ->getCollection()
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                ->getFirstItem();
        if($alipayCustomer->getId()) {
            Mage::register('alipay_customer', $alipayCustomer);
        }            

        if ($alipayCustomer->getExpiredTime() < time()) {
            Mage::getSingleton('core/session')->addNotice($this->__('If you want to login freely when you pay using Alipay, please login using Alipay Quick Login in 30 minutes earlier than paying.'));
        }            
        
        $this->loadLayout();
        $this->renderLayout();
    }

}
