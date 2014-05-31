<?php

/**
 * Sales Order History block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Sales
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Sales_Block_Order_History extends Mage_Sales_Block_Order_History {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('sunshinebiz/sales/order/history.phtml');

        $orders = Mage::getResourceModel('sales/order_collection')
                ->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                ->addFieldToFilter('state', array('in' => Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates()))
                ->setOrder('created_at', 'desc')
        ;

        $this->setOrders($orders);

        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(Mage::helper('sales')->__('My Orders'));
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
