<?php

/**
 * Html page header block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Page
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Page_Block_Html_Header extends Mage_Page_Block_Html_Header {

    public function getLogoSrc() {
        if (empty($this->_data['logo_src'])) {
            $this->_data['logo_src'] = $this->_getLogoFile();
        }
        return $this->getSkinUrl($this->_data['logo_src']);
    }

    /**
     * Retrieve path to Logo
     *
     * @return string
     */
    protected function _getLogoFile() {
        $folderName = SunshineBiz_Adminhtml_Model_System_Config_Backend_Image_Logo::UPLOAD_DIR;
        $storeConfig = Mage::getStoreConfig('design/header/logo_src');
        $absolutePath = Mage::getDesign()->getSkinBaseDir() . '/' . $folderName . '/' . $storeConfig;
        
        if (!is_null($storeConfig) && $this->_isFile($absolutePath)) {
            $url = $folderName . '/' . $storeConfig;
        } else {
            $url = 'images/logo.gif';
        }

        return $url;
    }

    /**
     * If DB file storage is on - find there, otherwise - just file_exists
     *
     * @param string $filename
     * @return bool
     */
    protected function _isFile($filename) {
        if (Mage::helper('core/file_storage_database')->checkDbUsage() && !is_file($filename)) {
            Mage::helper('core/file_storage_database')->saveFileToFilesystem($filename);
        }
        
        return is_file($filename);
    }

}
