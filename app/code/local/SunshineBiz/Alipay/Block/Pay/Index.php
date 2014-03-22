<?php

/**
 * Alipay pay index block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Block_Pay_Index extends Mage_Core_Block_Template {
    
    protected $_isMultishipping;
    protected $_displayButton;
    protected $_expiredTime;
    
    protected function _prepareLayout() {
        if($this->isMultishipping()) {
            if ($this->buttonDisplayable()) {
                $this->getLayout()->getBlock('head')->addCss('socialconnect/css/styles.css');
                $this->setChild('alipay_quik_login_button', $this->helper('socialconnect')->getMethodButtonBlock(Mage::getSingleton('alipay/login')));
            }
            
            $this->setTemplate('alipay/pay/multishipping.phtml');
        } else {
            $this->setTemplate('alipay/pay/index.phtml');
        }
        
        return parent::_prepareLayout();
    }
    
    protected function isMultishipping() {
        if($this->_isMultishipping == null) {
            if(Mage::getSingleton('checkout/session')->getAlipayPaymentOrderIds()) {
                $this->_isMultishipping = true;
            } else {
                $this->_isMultishipping = false;
            }
        }
        
        return $this->_isMultishipping ;
    }
    
    protected function getOrders() {
        $orders = array();
        foreach (Mage::getSingleton('checkout/session')->getAlipayPaymentOrderIds() as $orderId=>$incrementId) {
            $orders[] = Mage::getModel('sales/order')->load($orderId);
        }
        
        return $orders;
    }
    
    protected function buttonDisplayable() {
        if ($this->_displayButton == null && Mage::registry('order')->getPayment()->getMethod() != 'alipay_bank' && Mage::getSingleton('alipay/login')->isAvailable()) {
            $alipayCustomer = Mage::getModel('alipay/customer')
                    ->getCollection()
                    ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
                    ->getFirstItem();

            if (!($alipayCustomer->getId() && ($this->_expiredTime = $alipayCustomer->getExpiredTime()) > time())) {
                $this->_displayButton = true;
            } else {
                $this->_displayButton = false;
            }
        }

        return $this->_displayButton;
    }
}