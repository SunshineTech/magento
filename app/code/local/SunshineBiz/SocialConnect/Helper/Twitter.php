<?php

/**
 * SunshineBiz_SocialConnect Twitter Helper
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Helper_Twitter extends Mage_Core_Helper_Abstract {

    public function disconnect(Mage_Customer_Model_Customer $customer) {

        Mage::getSingleton('customer/session')->unsSocialconnectTwitterUserinfo();
        $pictureFilename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA)
                . DS . 'socialconnect' . DS . 'twitter' . DS . $customer->getSocialconnectTid();

        if (file_exists($pictureFilename)) {
            @unlink($pictureFilename);
        }

        $customer->setSocialconnectTid(null)->setSocialconnectTtoken(null)->save();
    }

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

        $customer->setEmail($email)
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

}
