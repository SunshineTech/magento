<?php

/**
 * SunshineBiz_Twitter Data Helper
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Twitter
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Twitter_Helper_Data extends Mage_Core_Helper_Abstract {
    
    public function connectByCreatingAccount($email, $name, $twitterId, $token) {
        
        $customer = Mage::getModel('customer/customer');
        $name = explode(' ', $name, 2);

        if (count($name) > 1) {
            $firstName = $name[0];
            $lastName = $name[1];
        } else {
            $firstName = $name[0];
            $lastName = $name[0];
        }

        $customer->setWebsiteId(Mage::app()->getWebsite()->getId())
                ->setEmail($email)
                ->setFirstname($firstName)
                ->setLastname($lastName)
                ->setSocialconnectTid($twitterId)
                ->setSocialconnectTtoken($token)
                ->setPassword($customer->generatePassword(10))
                ->save();

        $customer->setConfirmation(null);
        $customer->save();

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

    public function disconnect(Mage_Customer_Model_Customer $customer) {

        $twitterCustomer = Mage::getModel('Twitter/customer')
                ->getCollection()
                ->addFieldToFilter('customer_id', $customer->getId())
                ->getFirstItem();
        
        Mage::getSingleton('customer/session')->unsSocialconnectTwitterUserinfo();
        $pictureFilename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA)
                . DS . 'socialconnect' . DS . 'twitter' . DS . $twitterCustomer->getId();

        if (file_exists($pictureFilename)) {
            @unlink($pictureFilename);
        }

        $twitterCustomer->delete();
    }
    
    public function saveCustomer($twitterCustomer, $userInfo) {
        $twitterCustomer->setName($userInfo->name);
        $twitterCustomer->setScreenName($userInfo->screen_name);
        $twitterCustomer->setEmail($userInfo->email);

        if (!empty($userInfo->profile_image_url))
            $twitterCustomer->setPictureUrl($userInfo->profile_image_url);

        if (!$twitterCustomer->getCreatedAt())
            $twitterCustomer->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());

        $twitterCustomer->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        $twitterCustomer->save();
    }

}
