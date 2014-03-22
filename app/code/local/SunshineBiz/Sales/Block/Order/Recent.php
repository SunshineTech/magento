<?php

/**
 * Sales Order Recent block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Sales
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Sales_Block_Order_Recent extends Mage_Sales_Block_Order_Recent {

    public function _construct() {
        parent::_construct();
        
        $this->setTemplate('sunshinebiz_sales/order/recent.phtml');
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
