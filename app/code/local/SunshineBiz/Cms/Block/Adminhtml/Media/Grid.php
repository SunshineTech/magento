<?php

/**
 * SunshineBiz_Cms Media grid block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Block_Adminhtml_Media_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    protected $_helper;

    public function __construct() {
        parent::__construct();
        $this->setId('cmsMediaGrid');
        $this->setDefaultSort('media_id');
        $this->setDefaultDir('DESC');

        $this->_helper = Mage::helper('cms');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('sunshinebiz_cms/media')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('title', array(
            'header' => $this->_helper->__('Title'),
            'index' => 'title'
        ));

        /**
          $this->addColumn('type', array(
          'header' => Mage::helper('adminhtml')->__('Type'),
          'index' => 'type',
          'type' => 'options',
          'options' => Mage::getSingleton('sunshinebiz_cms/media')->getTypes(false),
          ));
         * */
        $this->addColumn('position', array(
            'header' => Mage::helper('adminhtml')->__('Position'),
            'index' => 'position',
            'type' => 'options',
            'options' => Mage::getSingleton('sunshinebiz_cms/media')->getPositions(false),
        ));

        $this->addColumn('src', array(
            'header' => Mage::helper('adminhtml')->__('File'),
            'index' => 'src',
            'filter' => null,
            'renderer' => 'sunshinebiz_cms/adminhtml_widget_grid_column_renderer_media',
        ));

        $this->addColumn('sort_order', array(
            'header' => Mage::helper('core')->__('Sort Order'),
            'index' => 'sort_order',
            'type' => 'number',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('stores', array(
                'header' => Mage::helper('cms')->__('Store View'),
                'index' => 'stores',
                'filter' => 'sunshinebiz_cms/adminhtml_widget_grid_column_filter_stores',
                'renderer' => 'sunshinebiz_cms/adminhtml_widget_grid_column_renderer_stores',
            ));
        }

        $this->addColumn('skins', array(
            'header' => $this->_helper->__('Skins (Images / CSS)'),
            'index' => 'skins',
            'filter' => 'sunshinebiz_cms/adminhtml_widget_grid_column_filter_skins',
            'renderer' => 'sunshinebiz_cms/adminhtml_widget_grid_column_renderer_skins',
        ));

        $this->addColumn('is_active', array(
            'header' => $this->_helper->__('Status'),
            'index' => 'is_active',
            'type' => 'options',
            'options' => array(
                0 => Mage::helper('cms')->__('Disabled'),
                1 => Mage::helper('cms')->__('Enabled')
            ),
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {

        $this->setMassactionIdField('media_id');
        $this->getMassactionBlock()->setFormFieldName('media');

        $this->getMassactionBlock()->addItem('massChangeStatus', array(
            'label' => $this->_helper->__('Mass Change Status'),
            'url' => $this->getUrl('*/*/massChangeStatus'),
            'confirm' => Mage::helper('core')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('massDelete', array(
            'label' => $this->_helper->__('Mass Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('core')->__('Are you sure?')
        ));

        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('media_id' => $row->getId()));
    }

}
