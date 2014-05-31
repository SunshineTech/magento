<?php

/**
 * SunshineBiz_Cms Media collection
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Model_Resource_Media_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    protected function _construct() {
        $this->_init('sunshinebiz_cms/media');
    }

}
