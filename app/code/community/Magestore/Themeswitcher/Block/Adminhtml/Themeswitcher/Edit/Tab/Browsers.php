<?php

class Magestore_Themeswitcher_Block_Adminhtml_Themeswitcher_Edit_Tab_Browsers 
	extends Mage_Adminhtml_Block_Template
{
	protected function _prepareLayout()
	{
		parent::_prepareLayout();
		$this->setTemplate('themeswitcher/browsers.phtml');
	}
}