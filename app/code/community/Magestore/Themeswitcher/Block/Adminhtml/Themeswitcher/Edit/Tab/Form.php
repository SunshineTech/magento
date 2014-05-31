<?php

class Magestore_Themeswitcher_Block_Adminhtml_Themeswitcher_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('themeswitcher_form', array('legend' => Mage::helper('themeswitcher')->__('Theme information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('themeswitcher')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));
        
        $fieldset->addField('stores', 'multiselect', array(
            'name' => 'stores',
            'label' => Mage::helper('cms')->__('Store View'),
            'title' => Mage::helper('cms')->__('Store View'),
            'required' => true,
            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
        ));        
        
        $fieldset->addField('platform', 'multiselect', array(
            'label' => Mage::helper('themeswitcher')->__('Devices'),
            'class' => 'required-entry',
            'required' => true,
            'values' => Mage::helper('themeswitcher')->getPlatformOption(),
            'name' => 'platform',
        ));

        $fieldset->addField('template', 'select', array(
            'label' => Mage::helper('themeswitcher')->__('Template'),
            'class' => 'required-entry',
            'required' => true,
            'values' => Mage::helper('themeswitcher')->getTemplateOption(),
            'name' => 'template',
        ));
        
        $fieldset->addField('cmshomepage', 'select', array(
            'label' => Mage::helper('themeswitcher')->__('CMS Home Page'),
            'title' => Mage::helper('themeswitcher')->__('CMS Home Page'),
            'required' => false,
            'name' => 'cmshomepage',
            'values' => Mage::helper('themeswitcher')->getPageList(),
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('themeswitcher')->__('Status'),
            'name' => 'status',
            'required' => true,
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('themeswitcher')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('themeswitcher')->__('Disabled'),
                ),
            ),
        ));

        $fieldset->addField('description', 'editor', array(
            'name' => 'description',
            'label' => Mage::helper('themeswitcher')->__('Description'),
            'title' => Mage::helper('themeswitcher')->__('Description'),
            'style' => 'width:400px; height:150px;',
            'wysiwyg' => false,
        ));

        if (Mage::registry('themeswitcher_data')) {
            $data = Mage::registry('themeswitcher_data')->getData();
            if (isset($data['stores'])) {
                $data['stores'] = explode(',', $data['stores']);
            }
            
            $form->setValues($data);
        }
        
        return parent::_prepareForm();
    }

}
