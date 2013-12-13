<?php

/**
 * SocialConnect login block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Block_Login extends Mage_Core_Block_Template {

    protected $_methods = null;

    /**
     * Prepare children blocks
     */
    protected function _prepareLayout() {

        $this->_methods = Mage::helper('socialconnect')->getStoreMethods();
        /**
         * Create child blocks
         */
        foreach ($this->_methods as $method) {
            $this->setChild(
                    'socialconnect.method.' . $method->getCode(), 
                    $this->helper('socialconnect')->getMethodButtonBlock($method)
            );
        }

        return parent::_prepareLayout();
    }

    /**
     * SocialConnect method button html getter
     * @param SunshineBiz_SocialConnect_Model_Method_Abstract $method
     */
    public function getMethodButtonHtml(SunshineBiz_SocialConnect_Model_Method_Abstract $method) {
        return $this->getChildHtml('socialconnect.method.' . $method->getCode());
    }
}