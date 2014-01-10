<?php

/**
 * SunshineBiz_Twitter customer resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Twitter
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Twitter_Model_Resource_Customer extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('twitter/customer', 'id');
        // The primary key is not an auto_increment field
        $this->_isPkAutoIncrement = false;
    }

}
