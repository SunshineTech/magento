<?php

/**
 * SunshineBiz_Cms Media model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Model_Media extends Mage_Core_Model_Abstract {

    const TYPE_IMAGE = 'Image';
    const TYPE_HTML5_VIDEO = 'Html5 Video';
    const TYPE_HTML5_AUDIO = 'Html5 Audio';
    const TYPE_FLASH = 'Flash';
    const TYPE_QUICK_TIME = 'QuickTime';
    const TYPE_SHOCK_WAVE = 'Shockwave';
    const TYPE_WINDOWS_MEDIA = 'Windows Media';
    const TYPE_REAL_MEDIA = 'Real Media';
    const POSITION_HOMEPAGE_LANDING = 'Homepage Landing';
    const UPLOAD_ROOT = 'media';

    protected $_helper;
    protected $_uploadDirs = array(
        '' => 'others',
        self::POSITION_HOMEPAGE_LANDING => 'home_landing'
    );

    protected function _construct() {
        $this->_init('sunshinebiz_cms/media');

        $this->_helper = Mage::helper('cms');
    }

    public function getTypes($emptyLabel = '') {
        $types = array(
            self::TYPE_IMAGE => Mage::helper('adminhtml')->__(self::TYPE_IMAGE),
            self::TYPE_HTML5_VIDEO => Mage::helper('cms')->__(self::TYPE_HTML5_VIDEO),
            self::TYPE_HTML5_AUDIO => Mage::helper('cms')->__(self::TYPE_HTML5_AUDIO),
            self::TYPE_FLASH => Mage::helper('cms')->__(self::TYPE_FLASH),
            self::TYPE_QUICK_TIME => Mage::helper('cms')->__(self::TYPE_QUICK_TIME),
            self::TYPE_SHOCK_WAVE => Mage::helper('cms')->__(self::TYPE_SHOCK_WAVE),
            self::TYPE_WINDOWS_MEDIA => Mage::helper('cms')->__(self::TYPE_WINDOWS_MEDIA),
            self::TYPE_REAL_MEDIA => Mage::helper('cms')->__(self::TYPE_REAL_MEDIA)
        );

        if ($emptyLabel !== false) {
            if (!$emptyLabel) {
                $emptyLabel = Mage::helper('core')->__('-- Please Select --');
            }

            array_unshift($types, array('value' => '', 'label' => $emptyLabel));
        }

        return $types;
    }

    public function getPositions($emptyLabel = '') {
        $poses = array(
            self::POSITION_HOMEPAGE_LANDING => Mage::helper('cms')->__(self::POSITION_HOMEPAGE_LANDING)
        );

        if ($emptyLabel !== false) {
            if (!$emptyLabel) {
                $emptyLabel = Mage::helper('core')->__('-- Please Select --');
            }

            array_unshift($poses, array('value' => '', 'label' => $emptyLabel));
        }

        return $poses;
    }

    protected function _appendScopeInfo($path) {
        $stores = explode('-', $this->getStores());
        $scope = 'default';
        $count = count($stores);
        if ($stores[0] !== '0') {
            switch ($count) {
                case 1:
                    $scope = 'websites';
                    break;
                case 2:
                    $scope = 'groups';
                    break;
                default:
                    $count = 3;
                    $scope = 'stores';
                    break;
            }
        }

        $path .= '/' . $scope;
        if ('default' != $scope) {
            $path .= '/' . $stores[$count - 1];
        }

        return $path;
    }

    protected function getUploadDir($positions = '') {
        return $this->_uploadDirs[$positions];
    }

    protected function _getUploadDir() {
        $uploadDir = $this->_appendScopeInfo($this->getUploadDir($this->getPosition()));
        $uploadRoot = Mage::getBaseDir(self::UPLOAD_ROOT);

        return $uploadRoot . '/' . $uploadDir;
    }

    protected function _uploadFile() {

        //upload file and update the file path
        $file = $this->getSrc();

        // if no file was set - nothing to do
        if (empty($file) && empty($_FILES['src']['name'])) {
            return;
        }

        if (is_array($file) && !empty($file['delete'])) {
            $this->unsSrc();
            return;
        }

        if (is_array($file) && empty($_FILES['src']['name'])) {
            $this->setSrc($file['value']);
            return;
        }

        $dir = $this->_getUploadDir();
        try {
            $uploader = new Mage_Core_Model_File_Uploader('src');
            $uploader->setAllowedExtensions(array('png', 'gif', 'jpg', 'jpeg', 'apng', 'svg'));
            $uploader->setAllowRenameFiles(true);
            $result = $uploader->save($dir);

            $filename = $result['file'];
            if ($filename) {
                $this->setSrc($this->_appendScopeInfo($this->getUploadDir($this->getPosition())) . '/' . $filename);
            }
        } catch (Exception $e) {
            if ($e->getCode() != Mage_Core_Model_File_Uploader::TMP_NAME_EMPTY) {
                Mage::logException($e);
            }
        }
    }

    protected function _beforeSave() {
        $this->_uploadFile();

        if (!Zend_Validate::is($this->getTitle(), 'NotEmpty')) {
            Mage::throwException($this->_helper->__('Title is required field.'));
        }

        if (!Zend_Validate::is($this->getSrc(), 'NotEmpty')) {
            Mage::throwException($this->_helper->__('File is required field.'));
        }

        if (!Zend_Validate::is($this->getStores(), 'NotEmpty')) {
            Mage::throwException($this->_helper->__('Stores is required field.'));
        }

        if (!Zend_Validate::is($this->getSkins(), 'NotEmpty')) {
            Mage::throwException($this->_helper->__('Skins is required field.'));
        }
    }

}
