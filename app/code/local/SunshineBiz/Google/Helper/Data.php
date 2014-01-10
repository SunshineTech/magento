<?php

/**
 * SunshineBiz_Google Data Helper
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Google
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Google_Helper_Data extends Mage_Core_Helper_Abstract {

    public function connectByCreatingAccount($email, $firstName, $lastName) {

        $customer = Mage::getModel('customer/customer');

        $customer->setWebsiteId(Mage::app()->getWebsite()->getId())
                ->setEmail($email)
                ->setFirstname($firstName)
                ->setLastname($lastName)
                ->setPassword($customer->generatePassword(10))
                ->save();

        $customer->setConfirmation(null);
        $customer->save();

        $customer->sendNewAccountEmail('confirmed', '', Mage::app()->getStore()->getId());

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);

        return $customer;
    }

    public function disconnect(Mage_Customer_Model_Customer $customer) {

        $googleCustomer = Mage::getModel('google/customer')
                ->getCollection()
                ->addFieldToFilter('customer_id', $customer->getId())
                ->getFirstItem();

        $method = Mage::getSingleton('google/method');
        $method->setAccessToken($googleCustomer->getToken());
        $method->revokeToken();

        $pictureFilename = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA)
                . DS . 'socialconnect' . DS . 'google' . DS . $googleCustomer->getId();

        if (file_exists($pictureFilename)) {
            @unlink($pictureFilename);
        }

        $googleCustomer->delete();
    }

    public function saveCustomer($googleCustomer, $userInfo) {
        $googleCustomer->setName($userInfo->name);
        $googleCustomer->setEmail($userInfo->email);
        $googleCustomer->setGender($userInfo->gender);

        if (!empty($userInfo->birthday))
            $googleCustomer->setBirthday($userInfo->birthday);

        if (!empty($userInfo->link))
            $googleCustomer->setLink($userInfo->link);

        if (!empty($userInfo->picture))
            $googleCustomer->setPictureUrl($userInfo->picture->data->url);

        if (!$googleCustomer->getCreatedAt())
            $googleCustomer->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());

        $googleCustomer->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        $googleCustomer->save();
    }

}