<?php

/**
 * SunshineBiz_Location area block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Area extends SunshineBiz_Location_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'area';
        $this->_headerText = Mage::helper('location')->__('Area Management');
        $this->_addButtonLabel = Mage::helper('location')->__('Add New Area');
        parent::__construct();
    }
}