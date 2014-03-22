<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Source_Method {

    public function toOptionArray() {

        return array(
            array(
                'value' => 'directPay',
                'label' => Mage::helper('alipay')->__('Balance Payment')
            ),
            array(
                'value' => 'creditPay',
                'label' => Mage::helper('alipay')->__('Credit Payment')
            )
        );
    }

}