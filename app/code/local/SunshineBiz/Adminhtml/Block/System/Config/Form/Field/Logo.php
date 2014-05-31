<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Adminhtml
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Adminhtml_Block_System_Config_Form_Field_Logo extends Varien_Data_Form_Element_Image {

    protected function _getUrl() {
        $url = parent::_getUrl();

        $config = $this->getFieldConfig();
        /* @var $config Varien_Simplexml_Element */
        if (!empty($config->base_url)) {
            $el = $config->descend('base_url');
            $urlType = empty($el['type']) ? 'link' : (string) $el['type'];
            
            $url = Mage::getBaseUrl($urlType) . 'frontend/' . $this->_getPackageName() . '/' . $this->_getThemeName() . '/' . (string) $config->base_url . '/' . $url;
        }

        return $url;
    }

    protected function _getPackageName() {
        return $this->_getValue('design/package/name');
    }

    protected function _getThemeName() {
        $skin = $this->_getValue('design/theme/skin');
        $theme = $skin ? $skin : $this->_getValue('design/theme/default');
        
        return $theme ? $theme : 'default';
    }

    protected function _getValue($path) {
        $storeCode = $this->getStoreCode();
        $websiteCode = $this->getWebsiteCode();

        if ($storeCode) {
            return Mage::app()->getStore($storeCode)->getConfig($path);
        }
        if ($websiteCode) {
            return Mage::app()->getWebsite($websiteCode)->getConfig($path);
        }

        return (string) Mage::getConfig()->getNode('default/' . $path);
    }

}
