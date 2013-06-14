<?php

/**
 * SunshineBiz_Location building block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Building extends SunshineBiz_Location_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'building';
        $this->_headerText = Mage::helper('location')->__('Building Management');
        $this->_addButtonLabel = Mage::helper('location')->__('Add New Building');
        parent::__construct();
    }

}