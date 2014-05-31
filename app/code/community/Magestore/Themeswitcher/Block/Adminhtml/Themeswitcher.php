<?php

class Magestore_Themeswitcher_Block_Adminhtml_Themeswitcher extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_themeswitcher';
        $this->_blockGroup = 'themeswitcher';
        $this->_headerText = Mage::helper('themeswitcher')->__('Theme Manager');
        $this->_addButtonLabel = Mage::helper('themeswitcher')->__('Add Theme');
        parent::__construct();
    }

}
