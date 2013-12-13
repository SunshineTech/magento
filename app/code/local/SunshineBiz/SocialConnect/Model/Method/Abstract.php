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
    public function getConfigData($field) {
        
        $path = 'socialconnect/' . $this->getCode() . '/' . $field;
        
        return Mage::getStoreConfig($path, Mage::app()->getStore()->getId());
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
    
    public abstract function getConnectText();
    
    public abstract function getDisconnectText();
    
    public abstract function createAuthUrl();
    
    public function setState($state) {
        $this->state = $state;
    }
    
    public function isActive() {
        return $this->getConfigData('active');
    }
}
