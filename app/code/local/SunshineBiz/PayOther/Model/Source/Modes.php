<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_PayOther
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_PayOther_Model_Source_Modes {

    public function toOptionArray() {
        $_helper = Mage::helper('payother');
        return array(
            '' => $_helper->__('---Please Select---'),
            'FRIEND_PAY' => $_helper->__('Friend Pay'),
            'PAYMENT_LINK' => $_helper->__('Payment Link'),
        );
    }

}