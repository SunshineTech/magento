<?php

/**
 * SunshineBiz_Google customer resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Google
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Google_Model_Resource_Customer extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('google/customer', 'id');
        // The primary key is not an auto_increment field
        $this->_isPkAutoIncrement = false;
    }

}
