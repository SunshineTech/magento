<?php

/**
 * 
 * Used in creating options for display config value selection
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Model_System_Config_Source_Display {

    public function toOptionArray() {
        return array(
            array('value' => 1, 'label' => Mage::helper('socialconnect')->__('Display Separately')),
            array('value' => 0, 'label' => Mage::helper('socialconnect')->__('Display Centralized')),
        );
    }

}
