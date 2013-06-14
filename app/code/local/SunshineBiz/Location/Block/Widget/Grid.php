<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Widget_Grid extends Mage_Adminhtml_Block_Widget_Grid {
    
    protected $_helper;
    
    public function __construct() {
        parent::__construct();
        $this->_helper = Mage::helper('location');
    }
    
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}