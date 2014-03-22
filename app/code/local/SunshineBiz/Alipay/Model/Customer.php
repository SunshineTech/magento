<?php

/**
 * SunshineBiz_Alipay customer model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Customer extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('alipay/customer');
    }

}
