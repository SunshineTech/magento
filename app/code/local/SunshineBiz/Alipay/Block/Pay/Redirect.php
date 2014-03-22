<?php

/**
 * Alipay Pay Redirect block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Block_Pay_Redirect extends Mage_Core_Block_Abstract {

    protected function _getFormHtml() {
        
        $methodInstance = $this->getOrder()->getPayment()->getMethodInstance();
        $fields = $methodInstance->getFormFields();
        
        $methodInstance->log($fields);

        $form = new Varien_Data_Form();
        $form->setAction(SunshineBiz_Alipay_Helper_Data::GATEWAY . '?_input_charset=' . $fields['_input_charset'])
                ->setId('alipay_pay_redirect')
                ->setName('alipay_pay_redirect')
                ->setMethod('POST')
                ->setUseContainer(true);

        foreach ($fields as $field => $value) {
            $form->addField($field, 'hidden', array(
                'name' => $field,
                'value' => $value
            ));
        }

        $form->addField('submit_botton', 'submit', array('value' => $this->__('If browser is not jump automatically in 10 seconds, please click me.')));
        
        $html = $form->toHtml();
        
        return substr($html, 0, stripos($html, '<div>')) . substr($html, strripos($html, '</div>') + 6);
    }

    protected function _toHtml() {
        
        $html = '<html><body>';
        $html .= $this->__('You will be redirected to Alipay in a few seconds.');
        $html .= $this->_getFormHtml();
        $html .= '<script type="text/javascript">document.getElementById("alipay_pay_redirect").submit();</script>';
        $html .= '</body></html>';

        return $html;
    }

}
