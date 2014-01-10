<?php

/**
 * SunshineBiz_Facebook Data Helper
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Facebook
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Facebook_Helper_Data extends Mage_Core_Helper_Abstract {

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

        $facebookCustomer = Mage::getModel('facebook/customer')
                ->getCollection()
                ->addFieldToFilter('customer_id', $customer->getId())
                ->getFirstItem();

        $method = Mage::getSingleton('facebook/method');
        $method->setAccessToken($facebookCustomer->getToken());
        $method->api('/me/permissions', 'DELETE');

        $pictureFilename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA)
                . DS . 'socialconnect' . DS . 'facebook' . DS . $facebookCustomer->getId();

        if (file_exists($pictureFilename)) {
            @unlink($pictureFilename);
        }

        $facebookCustomer->delete();
    }
    
    public function saveCustomer($facebookCustomer, $userInfo) {
        $facebookCustomer->setName($userInfo->name);
        $facebookCustomer->setEmail($userInfo->email);
        $facebookCustomer->setGender($userInfo->gender);

        if (!empty($userInfo->birthday))
            $facebookCustomer->setBirthday($userInfo->birthday);

        if (!empty($userInfo->link))
            $facebookCustomer->setLink($userInfo->link);

        if (!empty($userInfo->picture))
            $facebookCustomer->setPictureUrl($userInfo->picture->data->url);

        if (!$facebookCustomer->getCreatedAt())
            $facebookCustomer->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());

        $facebookCustomer->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        $facebookCustomer->save();
    }

}
