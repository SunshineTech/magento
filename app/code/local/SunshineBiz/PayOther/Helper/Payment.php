<?php

/**
 * SunshineBiz_PayOther Payment Helper
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_PayOther
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_PayOther_Helper_Payment extends Mage_Payment_Helper_Data {

    /**
     * Retreive payment method form html
     *
     * @param   Mage_Payment_Model_Abstract $method
     * @return  Mage_Payment_Block_Form
     */
    public function getMethodFormBlock(Mage_Payment_Model_Method_Abstract $method) {
        $block = parent::getMethodFormBlock($method);
        if ($block && ($method instanceof SunshineBiz_PayOther_Model_Payment) && $method->isPayableOther()) {            
            $html = $block->toHtml();
            if ($html) {
                $start = stripos($html, '<li');
                $end = strripos($html, '</li>');
                $block->setPayMethodFormHtml(substr($html, $start, $end + 5 - $start));
            }
            $block->setTemplate('payother/form.phtml');
        }
        
        return $block;
    }

}