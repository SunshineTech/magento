<?php

/**
 * Alipay Login Redirect block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Block_Login_Redirect extends Mage_Core_Block_Abstract {

    protected function _toHtml() {
        
        $method = Mage::getSingleton('alipay/login');
        $fields = $method->getLoginFormFields();
        
        $form = new Varien_Data_Form();
        $form->setAction(SunshineBiz_Alipay_Helper_Data::GATEWAY . '?_input_charset=' . $fields['_input_charset'])
                ->setId('alipay_login')
                ->setName('alipay_login')
                ->setMethod('POST')
                ->setUseContainer(true);
        
        foreach ($fields as $field => $value) {
            $form->addField($field, 'hidden', array(
                'name' => $field,
                'value' => $value
            ));
        }
        
        $form->addField('submit_botton','submit', array('value' => $this->__('If browser is not jump automatically in 10 seconds, please click me.')));
        
        $html = '<html><body>';
        $html .= $this->__('You will be redirected to Alipay in a few seconds.');
        $html .= $form->toHtml();
        $html .= '<script type="text/javascript">document.getElementById("alipay_login").submit();</script>';
        $html .= '</body></html>';
        
        return $html;
    }

}
