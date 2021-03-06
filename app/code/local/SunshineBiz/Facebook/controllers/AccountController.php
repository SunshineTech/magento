<?php

/**
 * SunshineBiz_Facebook account controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Facebook
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
require_once 'SunshineBiz/SocialConnect/controllers/AccountController.php';

class SunshineBiz_Facebook_AccountController extends SunshineBiz_SocialConnect_AccountController {

    public function indexAction() {
        if (!(Mage::getSingleton('facebook/method')->isAvailable())) {
            return Mage::helper('socialconnect')->redirect404($this);
        }
        
        $facebookCustomer = Mage::getModel('facebook/customer')
                ->getCollection()
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                ->getFirstItem();
        if($facebookCustomer->getId()) {
            Mage::register('facebook_customer', $facebookCustomer);
        }            

        $this->loadLayout();
        $this->renderLayout();
    }
}