<?php

class Magestore_Themeswitcher_Model_Themebrowser extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('themeswitcher/themebrowser');
    }
	
	public function loadByTheme($themeId,$browser)
	{
		$themebrowser = $this->getCollection()
							->addFieldToFilter('theme_id',$themeId)
							->addFieldToFilter('browser',$browser)
                                                        //->addFieldToFilter('store_id',$store_id)
							->getFirstItem();
		$this->setData($themebrowser->getData());
		return $this;
	}
}