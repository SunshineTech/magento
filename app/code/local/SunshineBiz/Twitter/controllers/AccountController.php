<?php

/**
 * SunshineBiz_Twitter account controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Twitter
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
include_once("SunshineBiz/SocialConnect/controllers/AccountController.php");

class SunshineBiz_Twitter_AccountController extends SunshineBiz_SocialConnect_AccountController {

    public function indexAction() {
        if (!(Mage::getSingleton('twitter/method')->isAvailable())) {
            return Mage::helper('socialconnect')->redirect404($this);
        }
        
        $twitterCustomer = Mage::getModel('twitter/customer')
                ->getCollection()
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                ->getFirstItem();
        if($twitterCustomer->getId()) {
            Mage::register('twitter_customer', $twitterCustomer);
        }            

        $this->loadLayout();
        $this->renderLayout();
    }
}