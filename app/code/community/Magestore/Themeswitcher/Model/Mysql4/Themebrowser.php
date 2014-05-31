<?php

class Magestore_Themeswitcher_Model_Mysql4_Themebrowser extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('themeswitcher/themebrowser', 'theme_browser_id');
    }
}