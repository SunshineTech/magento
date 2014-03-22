<?php

/**
 * SunshineBiz_Google account controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Google
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
include_once("SunshineBiz/SocialConnect/controllers/AccountController.php");

class SunshineBiz_Google_AccountController extends SunshineBiz_SocialConnect_AccountController {

    public function indexAction() {        
        if (!(Mage::getSingleton('google/method')->isAvailable())) {
            return Mage::helper('socialconnect')->redirect404($this);
        }
        
        $googleCustomer = Mage::getModel('google/customer')
                ->getCollection()
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                ->getFirstItem();
        if($googleCustomer->getId()) {
            Mage::register('google_customer', $googleCustomer);
        }            

        $this->loadLayout();
        $this->renderLayout();
    }
}