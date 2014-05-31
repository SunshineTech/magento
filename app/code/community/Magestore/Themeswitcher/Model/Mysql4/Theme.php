<?php

class Magestore_Themeswitcher_Model_Mysql4_Theme extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('themeswitcher/theme', 'theme_id');
    }
}