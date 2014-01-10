<?php

/**
 * SunshineBiz_Facebook customer collection
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Facebook
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Facebook_Model_Resource_Customer_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    protected function _construct() {
        $this->_init('facebook/customer');
    }

}
