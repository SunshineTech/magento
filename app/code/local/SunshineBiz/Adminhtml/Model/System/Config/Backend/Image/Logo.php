<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Adminhtml
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Adminhtml_Model_System_Config_Backend_Image_Logo extends Mage_Adminhtml_Model_System_Config_Backend_Image {

    /**
     * The tail part of directory path for uploading
     *
     */
    const UPLOAD_DIR = 'images/logo';

    /**
     * Token for the root part of directory path for uploading
     *
     */
    const UPLOAD_ROOT = 'skin';

    /**
     * Return path to directory for upload file
     *
     * @return string
     * @throw Mage_Core_Exception
     */
    protected function _getUploadDir() {
        $uploadDir = $this->_appendScopeInfo(self::UPLOAD_DIR);
        $uploadRoot = $this->_getUploadRoot(self::UPLOAD_ROOT);
        $uploadDir = $uploadRoot . '/' . $uploadDir;
        
        return $uploadDir;
    }

    /**
     * Makes a decision about whether to add info about the scope.
     *
     * @return boolean
     */
    protected function _addWhetherScopeInfo() {
        return true;
    }

    /**
     * Getter for allowed extensions of uploaded files.
     *
     * @return array
     */
    protected function _getAllowedExtensions() {
        return array('png', 'gif', 'jpg', 'jpeg', 'apng', 'svg');
    }

    /**
     * Get real media dir path
     *
     * @param  $token
     * @return string
     */
    protected function _getUploadRoot($token) {
        $groups = $this->_getData('groups');
        $packageGroup = (is_array($groups) && isset($groups['package'])) ? $groups['package'] : null;
        $themeGroup = (is_array($groups) && isset($groups['theme'])) ? $groups['theme'] : null;

        return Mage::getBaseDir($token) . '/frontend/' . $this->_getPackageName($packageGroup) . '/' . $this->_getThemeName($themeGroup);
    }

    protected function _getPackageName($packageGroup) {
        if (!empty($packageGroup) && is_array($packageGroup) && isset($packageGroup['fields']) && isset($packageGroup['fields']['name']) && isset($packageGroup['fields']['name']['value'])) {
            return $packageGroup['fields']['name']['value'];
        }

        return $this->_getValue('design/package/name');
    }

    protected function _getThemeName($themeGroup) {
        if (!empty($themeGroup) && is_array($themeGroup) && isset($themeGroup['fields']) && isset($themeGroup['fields']['skin']) && isset($themeGroup['fields']['skin']['value'])) {
            $theme = $themeGroup['fields']['skin']['value'];
            if($theme) {
                return $theme;
            }
        }
        
        if (!empty($themeGroup) && is_array($themeGroup) && isset($themeGroup['fields']) && isset($themeGroup['fields']['default']) && isset($themeGroup['fields']['default']['value'])) {
            $theme = $themeGroup['fields']['default']['value'];
            if($theme) {
                return $theme;
            }
        }

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
