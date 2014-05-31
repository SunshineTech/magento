<?php

/**
 * SunshineBiz_Cms media edit form block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Block_Adminhtml_Media_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected $_helper;

    public function __construct() {
        parent::__construct();

        $this->_helper = Mage::helper('cms');

        $this->setId('media_form');
        $this->setTitle($this->_helper->__('Media Information'));
    }

    protected function _prepareForm() {
        $model = Mage::registry('cms_media');

        $form = new Varien_Data_Form(
                array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data')
        );
        $form->setHtmlIdPrefix('media_');

        $fieldset = $form->addFieldset(
                'base_fieldset', array('legend' => $this->_helper->__('Media Information'))
        );

        if ($model->getId()) {
            $fieldset->addField('media_id', 'hidden', array(
                'name' => 'media_id',
            ));
        } else {
            $model->setData('is_active', '1');
        }

        $fieldset->addField('title', 'text', array(
            'name' => 'title',
            'label' => $this->_helper->__('Title'),
            'title' => $this->_helper->__('Title'),
            'required' => true,
        ));

        /**
        $fieldset->addField('type', 'select', array(
            'name' => 'type',
            'label' => Mage::helper('adminhtml')->__('Type'),
            'title' => Mage::helper('adminhtml')->__('Type'),
            'class' => 'input-select',
            'required' => true,
        ))->setValues(Mage::getSingleton('sunshinebiz_cms/media')->getTypes(false));
        **/
        
        $fieldset->addField('position', 'select', array(
            'name' => 'position',
            'label' => Mage::helper('adminhtml')->__('Position'),
            'title' => Mage::helper('adminhtml')->__('Position'),
            'class' => 'input-select',
        ))->setValues(Mage::getSingleton('sunshinebiz_cms/media')->getPositions());

        $fieldset->addField('src', 'image', array(
            'name' => 'src',
            'label' => Mage::helper('adminhtml')->__('File'),
            'title' => Mage::helper('adminhtml')->__('File'),
            'required' => true,
        ));

        $fieldset->addField('url', 'text', array(
            'name' => 'url',
            'label' => $this->_helper->__('Link Url'),
            'title' => $this->_helper->__('Link Url'),
        ));

        $fieldset->addField('target', 'text', array(
            'name' => 'target',
            'label' => $this->_helper->__('Link Target'),
            'title' => $this->_helper->__('Link Target'),
            'after_element_html' => $this->escapeHtml($this->_helper->__("Attribute 'target' of tag '<a>'. Blank means Link Url always opens in current window.")),
        ));

        $fieldset->addField('sort_order', 'text', array(
            'name' => 'sort_order',
            'label' => Mage::helper('core')->__('Sort Order'),
            'title' => Mage::helper('core')->__('Sort Order'),
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('stores', 'select', array(
                'name' => 'stores',
                'label' => $this->_helper->__('Store View'),
                'title' => $this->_helper->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true, true),
            ));
        } else {
            $fieldset->addField('stores', 'hidden', array(
                'name'      => 'stores',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('skins', 'multiselect', array(
            'name' => 'skins',
            'label' => $this->_helper->__('Skins (Images / CSS)'),
            'title' => $this->_helper->__('Skins (Images / CSS)'),
            'required' => true,
            'values' => Mage::getSingleton('design/skin')->getSkinValuesForForm(false, true),
        ));
        
        $fieldset->addField('is_active', 'select', array(
            'label'     => $this->_helper->__('Status'),
            'title'     => $this->_helper->__('Status'),
            'name'      => 'is_active',
            'required'  => true,
            'options'   => array(
                '1' => $this->_helper->__('Enabled'),
                '0' => $this->_helper->__('Disabled'),
            ),
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
