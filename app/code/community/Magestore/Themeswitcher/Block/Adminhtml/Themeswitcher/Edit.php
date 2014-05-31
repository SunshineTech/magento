<?php

class Magestore_Themeswitcher_Block_Adminhtml_Themeswitcher_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'themeswitcher';
        $this->_controller = 'adminhtml_themeswitcher';

        $this->_updateButton('save', 'label', Mage::helper('themeswitcher')->__('Save Theme'));
        $this->_updateButton('delete', 'label', Mage::helper('themeswitcher')->__('Delete Theme'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('themeswitcher_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'themeswitcher_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'themeswitcher_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('themeswitcher_data') && Mage::registry('themeswitcher_data')->getId()) {
            return Mage::helper('themeswitcher')->__("Edit Theme '%s'", $this->htmlEscape(Mage::registry('themeswitcher_data')->getTitle()));
        } else {
            return Mage::helper('themeswitcher')->__('Add Theme');
        }
    }

}
