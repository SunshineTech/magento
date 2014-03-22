<?php

/**
 * SunshineBiz_Alipay customer resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Resource_Customer extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('alipay/customer', 'id');
        // The primary key is not an auto_increment field
        $this->_isPkAutoIncrement = false;
    }

}