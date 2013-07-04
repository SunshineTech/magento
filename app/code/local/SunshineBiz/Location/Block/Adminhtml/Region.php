<?php

/**
 * SunshineBiz_Location region block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Adminhtml_Region extends SunshineBiz_Location_Block_Adminhtml_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_region';
        $this->_headerText = Mage::helper('location')->__('Region Management');
        $this->_addButtonLabel = Mage::helper('location')->__('Add New Region');
        parent::__construct();
    }
}