<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
abstract class SunshineBiz_SocialConnect_Model_Method_Abstract extends Varien_Object {

    protected $_code;
    protected $_buttonBlockType = 'socialconnect/button';
    protected $_accountUrl = null;
    protected $state = '';

    /**
     * Retrieve block type for method button generation
     *
     * @return string
     */
    public function getButtonBlockType() {
        return $this->_buttonBlockType;
    }

    /**
     * Retrieve SocialConnect method code
     *
     * @return string
     */
    public function getCode() {
        if (empty($this->_code)) {
            Mage::throwException(Mage::helper('socialconnect')->__('Cannot retrieve the SocialConnect method code.'));
        }

        return $this->_code;
    }

    public function getAccountUrl() {
        if (empty($this->_accountUrl)) {
            Mage::throwException(Mage::helper('socialconnect')->__('Cannot retrieve the SocialConnect method account url.'));
        }

        return $this->_accountUrl;
    }

    /**
     * Retrieve information from SocialConnect configuration
     *
     * @param string $field
     *
     * @return mixed
     */
    public function getConfigData($field, $storeId = null) {
        if (null === $storeId) {
            $storeId = $this->getStore();
        }
        $path = 'socialconnect/' . $this->getCode() . '/' . $field;
        
        return Mage::getStoreConfig($path, $storeId);
    }

    /**
     * Retrieve method title
     *
     * @return string
     */
    public abstract function getTitle();

    /**
     * Retrieve method description
     *
     * @return string
     */
    public abstract function getDescription();

    public abstract function createAuthUrl();

    public function setState($state) {
        $this->state = $state;
    }

    public function isAvailable($store = null) {
        if (null === $store) {
            $store = $this->getStore();
        }
        
        $checkResult = new StdClass;
        $isActive = (bool) (int) $this->getConfigData('active', $store);
        $checkResult->isAvailable = $isActive;
        $checkResult->isDeniedInConfig = !$isActive; // for future use in observers
        Mage::dispatchEvent('socialconnect_method_is_active', array(
            'result' => $checkResult,
            'method_instance' => $this,
            '$store' => $store,
        ));

        return $checkResult->isAvailable;
    }

}
