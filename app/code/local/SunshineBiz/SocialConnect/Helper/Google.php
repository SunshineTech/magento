<?php

/**
 * SunshineBiz_SocialConnect Google Helper
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Helper_Google extends Mage_Core_Helper_Abstract {

    public function connectByCreatingAccount($email, $firstName, $lastName, $googleId, $token) {

        $customer = Mage::getModel('customer/customer');

        $customer->setWebsiteId(Mage::app()->getWebsite()->getId())
                ->setEmail($email)
                ->setFirstname($firstName)
                ->setLastname($lastName)
                ->setSocialconnectGid($googleId)
                ->setSocialconnectGtoken($token)
                ->setPassword($customer->generatePassword(10))
                ->save();

        $customer->setConfirmation(null);
        $customer->save();

        $customer->sendNewAccountEmail('confirmed', '', Mage::app()->getStore()->getId());

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

    public function disconnect(Mage_Customer_Model_Customer $customer) {

        $method = Mage::getSingleton('socialconnect/google_method');

        try {
            $method->setAccessToken($customer->getSocialconnectGtoken());
            $method->revokeToken();
        } catch (Exception $e) {
            
        }

        $pictureFilename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA)
                . DS . 'socialconnect' . DS . 'google' . DS . $customer->getSocialconnectGid();

        if (file_exists($pictureFilename)) {
            @unlink($pictureFilename);
        }

        $customer->setSocialconnectGid(null)->setSocialconnectGtoken(null)->save();
    }
}