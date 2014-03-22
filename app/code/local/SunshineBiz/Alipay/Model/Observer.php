<?php

/**
 * Alipay Observer model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Observer {

    public function salesEventOrderAfterSave(Varien_Event_Observer $observer) {
        $order = $observer->getEvent()->getOrder();
        $methodInstance = $order->getPayment()->getMethodInstance();
        if ($methodInstance instanceof SunshineBiz_Alipay_Model_Payment && $methodInstance->canDelivery()) {
            $methodInstance->setStore($order->getStore())->delivery();
        }
    }

}
