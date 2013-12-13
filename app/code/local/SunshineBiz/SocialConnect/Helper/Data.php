<?php

/**
 * SunshineBiz_SocialConnect Data Helper
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Get and sort available SocialConnect methods for specified or current store
     *
     * array structure:
     *  $index => Varien_Simplexml_Element
     *
     * @return array
     */
    public function getStoreMethods() {
        $res = array();
        $storeId = Mage::app()->getStore()->getId();
        foreach (Mage::getStoreConfig('socialconnect', $storeId) as $code => $methodConfig) {
            if (Mage::getStoreConfigFlag('socialconnect/' . $code . '/active', $storeId)) {
                if (array_key_exists('model', $methodConfig)) {
                    $methodModel = Mage::getModel($methodConfig['model']);
                    if ($methodModel && $methodModel->getConfigData('active')) {
                        $sortOrder = (int) $methodModel->getConfigData('sort_order');
                        $methodModel->setSortOrder($sortOrder);
                        $res[] = $methodModel;
                    }
                }
            }
        }

        usort($res, array($this, '_sortMethods'));
        return $res;
    }

    protected function _sortMethods($a, $b) {
        if (is_object($a)) {
            return (int) $a->sort_order < (int) $b->sort_order ? -1 : ((int) $a->sort_order > (int) $b->sort_order ? 1 : 0);
        }

        return 0;
    }

    /**
     * Retreive SocialConnect button
     *
     * @param   SunshineBiz_SocialConnect_Model_Method_Abstract $method
     * @return  SunshineBiz_SocialConnect_Block_Button
     */
    public function getMethodButtonBlock(SunshineBiz_SocialConnect_Model_Method_Abstract $method) {
        $block = false;
        $blockType = $method->getButtonBlockType();
        if ($this->getLayout()) {
            $block = $this->getLayout()->createBlock($blockType);
            $block->setMethod($method);
        }

        return $block;
    }

    public function getCustomersByClientId($clientIdField, $clientId) {

        $customer = Mage::getModel('customer/customer');

        $collection = $customer->getCollection()
                ->addAttributeToFilter($clientIdField, $clientId)
                ->setPageSize(1);

        if ($customer->getSharingConfig()->isWebsiteScope()) {
            $collection->addAttributeToFilter(
                    'website_id', Mage::app()->getWebsite()->getId()
            );
        }

        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $collection->addFieldToFilter(
                    'entity_id', array('neq' => Mage::getSingleton('customer/session')->getCustomerId())
            );
        }

        return $collection;
    }

    public function loginByCustomer(Mage_Customer_Model_Customer $customer) {
        if ($customer->getConfirmation()) {
            $customer->setConfirmation(null);
            $customer->save();
        }

        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
    }

    public function getCustomersByEmail($email) {

        $customer = Mage::getModel('customer/customer');

        $collection = $customer->getCollection()
                ->addFieldToFilter('email', $email)
                ->setPageSize(1);

        if ($customer->getSharingConfig()->isWebsiteScope()) {
            $collection->addAttributeToFilter('website_id', Mage::app()->getWebsite()->getId());
        }

        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $collection->addFieldToFilter('entity_id', array('neq' => Mage::getSingleton('customer/session')->getCustomerId()));
        }

        return $collection;
    }
    
    public function getProperDimensionsPictureUrl($url, $filename, $pictureUrl) {
        
        $directory = dirname($filename);
        if (!file_exists($directory) || !is_dir($directory)) {
            if (!@mkdir($directory, 0777, true))
                return null;
        }

        if (!file_exists($filename) || (time() - filemtime($filename) >= 3600)) {
            $client = new Zend_Http_Client($pictureUrl);
            $client->setStream();
            $response = $client->request('GET');
            stream_copy_to_stream($response->getStream(), fopen($filename, 'w'));

            $imageObj = new Varien_Image($filename);
            $imageObj->constrainOnly(true);
            $imageObj->keepAspectRatio(true);
            $imageObj->keepFrame(false);
            $imageObj->resize(150, 150);
            $imageObj->save($filename);
        }

        return $url;
    }

    public function redirect404($frontController) {
        $frontController->getResponse()->setHeader('HTTP/1.1','404 Not Found');
        $frontController->getResponse()->setHeader('Status','404 File not found');
        
        $pageId = Mage::getStoreConfig('web/default/cms_no_route');
        if (!Mage::helper('cms/page')->renderPage($frontController, $pageId))
                $frontController->_forward('defaultNoRoute');
    }
}