<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Source_Checkctu {

    public function toOptionArray() {

        return array(
            array(
                'value' => 'Y',
                'label' => Mage::helper('adminhtml')->__('Yes')
            ),
            array(
                'value' => 'N',
                'label' => Mage::helper('adminhtml')->__('No')
            )
        );
    }

}
