<?php

/**
 * SunshineBiz_SocialConnect Facebook Helper
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Helper_Facebook extends Mage_Core_Helper_Abstract {

    public function connectByCreatingAccount($email, $firstName, $lastName, $facebookId, $token) {

        $customer = Mage::getModel('customer/customer');
        $customer->setWebsiteId(Mage::app()->getWebsite()->getId())
                ->setEmail($email)
                ->setFirstname($firstName)
                ->setLastname($lastName)
                ->setSocialconnectFid($facebookId)
                ->setSocialconnectFtoken($token)
                ->setPassword($customer->generatePassword(10))
                ->save();

        $customer->setConfirmation(null);
        $customer->save();

        $customer->sendNewAccountEmail('confirmed', '', Mage::app()->getStore()->getId());

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

    public function disconnect(Mage_Customer_Model_Customer $customer) {
        
        $method = Mage::getSingleton('socialconnect/facebook_method');
        try {
            $method->setAccessToken($customer->getSocialconnectFtoken());
            $method->api('/me/permissions', 'DELETE');
        } catch (Exception $e) {
            
        }

        $pictureFilename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA)
                . DS . 'socialconnect' . DS . 'facebook' . DS . $customer->getSocialconnectFid();

        if (file_exists($pictureFilename)) {
            @unlink($pictureFilename);
        }

        $customer->setSocialconnectFid(null)->setSocialconnectFtoken(null)->save();
    }

}
