<?php

/**
 * Alipay Paym Form block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Block_Pay_Form extends Mage_Payment_Block_Form {

    protected function _construct() {
        parent::_construct();

        $this->setTemplate('alipay/pay/form.phtml');
    }

}
