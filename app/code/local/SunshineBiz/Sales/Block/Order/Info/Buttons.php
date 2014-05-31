<?php

/**
 * Block of links in Order view page
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Sales
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Sales_Block_Order_Info_Buttons extends Mage_Sales_Block_Order_Info_Buttons {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('sunshinebiz/sales/order/info/buttons.phtml');
    }

    public function getPayUrl($order) {
        $methodInstance = $order->getPayment()->getMethodInstance();
        $methodInstance->setStore($order->getStore());
        
        if(($payUrl = $methodInstance->getPayUrl())) {
            return $this->getUrl($payUrl, array('order_id' => $order->getId()));
        }
        
        return false;
    }
}
