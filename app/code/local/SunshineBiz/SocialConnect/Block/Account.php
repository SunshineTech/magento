<?php

/**
 * SocialConnect account block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Block_Account extends Mage_Core_Block_Template {

    protected function _construct() {
        parent::_construct();
        
        $parentBlock = Mage::app()->getLayout()->getBlock('customer_account_navigation');
        
        foreach (Mage::helper('socialconnect')->getStoreMethods() as $method) {
            $parentBlock->addLink('socialconnect_' . $method->getCode(), $method->getAccountUrl(), $method->getTitle());
        }
    }

}
