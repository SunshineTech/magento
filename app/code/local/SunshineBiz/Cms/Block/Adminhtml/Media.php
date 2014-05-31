<?php

/**
 * SunshineBiz_Cms Media block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Block_Adminhtml_Media extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_media';
        $this->_blockGroup = 'sunshinebiz_cms';
        $this->_headerText = Mage::helper('cms')->__('Medias');
        $this->_addButtonLabel = Mage::helper('cms')->__('Add New Medias');
        
        parent::__construct();
    }

}
