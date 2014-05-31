<?php

/**
 * PayOther payment method
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_PayOther
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_PayOther_Model_Payment extends Mage_Payment_Model_Method_Abstract {
    
    function isPayableOther() {
        return false;
    }
}
