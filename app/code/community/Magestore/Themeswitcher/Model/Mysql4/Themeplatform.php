<?php

class Magestore_Themeswitcher_Model_Mysql4_Themeplatform extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('themeswitcher/themeplatform', 'theme_platform_id');
    }
}