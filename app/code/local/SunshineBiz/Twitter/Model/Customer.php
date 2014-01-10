<?php

/**
 * SunshineBiz_Twitter customer model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Twitter
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Twitter_Model_Customer extends Mage_Core_Model_Abstract {
    
    protected function _construct() {
        $this->_init('twitter/customer');
    }
}