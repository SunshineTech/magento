<?php

class Magestore_Themeswitcher_Model_Themeplatform extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('themeswitcher/themeplatform');
    }
	
	public function loadByTheme($themeId,$platform)
	{
		$themeplatform = $this->getCollection()
							->addFieldToFilter('theme_id',$themeId)
							->addFieldToFilter('platform',$platform)
                                                        //->addFieldToFilter('store_id',$store_id)
							->getFirstItem();
		$this->setData($themeplatform->getData());
		return $this;
	}
}