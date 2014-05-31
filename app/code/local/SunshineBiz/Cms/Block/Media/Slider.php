<?php

/**
 * SunshineBiz_Cms Media block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Block_Media_Slider extends Mage_Core_Block_Template {

    protected $_position = '';
    protected $_medias;
    
    protected function _prepareLayout() {
        $this->setTemplate('sunshinebiz/cms/media/slider.phtml');
        
        return parent::_prepareLayout();
    }

    public function setPosition($position) {
        $this->_position = $position;
    }

    public function getMedias() {
        if ($this->_medias === null) {
            $collection = Mage::getResourceModel('sunshinebiz_cms/media_collection')
                    ->addFieldToFilter('is_active', array('eq' => 1))
                    ->addOrder('sort_order');

            if ($this->_position) {
                $collection->addFieldToFilter('position', array('eq' => $this->_position));
            } else {
                $collection->addFieldToFilter('position', array('null' => true));
            }

            $store = Mage::app()->getStore();
            $collection->addFieldToFilter('stores', array(
                array('eq' => '0'),
                array('eq' => $store->getWebsiteId()),
                array('eq' => $store->getWebsiteId() . '-' . $store->getGroupId()),
                array('eq' => $store->getWebsiteId() . '-' . $store->getGroupId() . '-' . $store->getId())
                    )
            );

            $package = Mage::getDesign();
            $packageName = $package->getPackageName();
            $themeName = $package->getTheme('default');
            $skinName = $package->getTheme('skin');
            $collection->addFieldToFilter('skins', array(
                array('eq' => 'All'),
                array('eq' => $packageName),
                array('like' => $packageName . ',%'),
                array('like' => '%,' . $packageName . ',%'),
                array('like' => '%,' . $packageName),
                array('eq' => $packageName . '/' . $themeName),
                array('like' => $packageName . '/' . $themeName . ',%'),
                array('like' => '%,' . $packageName . '/' . $themeName . ',%'),
                array('like' => '%,' . $packageName . '/' . $themeName),
                array('eq' => $packageName . '/' . $themeName . '/' . $skinName),
                array('like' => $packageName . '/' . $themeName . '/' . $skinName . ',%'),
                array('like' => '%,' . $packageName . '/' . $themeName . '/' . $skinName . ',%'),
                array('like' => '%,' . $packageName . '/' . $themeName . '/' . $skinName)
                    )
            );
            
            $this->_medias = $collection->getItems();            
        }

        return $this->_medias;
    }

}
