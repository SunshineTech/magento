<?php

/**
 * SunshineBiz_Cms Media edit block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Block_Adminhtml_Media_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    protected $_helper;

    public function __construct() {
        $this->_objectId = 'media_id';
        $this->_controller = 'adminhtml_media';
        $this->_blockGroup  = 'sunshinebiz_cms';
        parent::__construct();

        $this->_helper = Mage::helper('cms');

        $this->_updateButton('save', 'label', $this->_helper->__('Save Media'));
        $this->_updateButton('delete', 'label', $this->_helper->__('Delete Media'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('cms_media')->getId()) {
            return $this->_helper->__("Edit Media '%s'", $this->escapeHtml(Mage::registry('cms_media')->getTitle()));
        } else {
            return $this->_helper->__('New Media');
        }
    }
    
    public function getSaveUrl() {
        return $this->getUrl('*/*/save');
    }

}
