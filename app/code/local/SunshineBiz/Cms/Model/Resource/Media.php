<?php

/**
 * SunshineBiz_Cms Media resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Model_Resource_Media extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('sunshinebiz_cms/media', 'media_id');
    }
    
    protected function _beforeSave(Mage_Core_Model_Abstract $area) {

        if (!$area->getId()) {
            $area->setCreationTime(Mage::getSingleton('core/date')->gmtDate());
        }
        
        $area->setUpdateTime(Mage::getSingleton('core/date')->gmtDate());

        return parent::_beforeSave($area);
    }

}
