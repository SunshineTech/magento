<?php

/**
 * SunshineBiz_Location area grid block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Adminhtml_Area_Grid extends SunshineBiz_Location_Block_Adminhtml_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('locationsAreaGrid');
        $this->setDefaultSort('parent_id');
        $this->setDefaultDir('asc');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('location/area')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('id', array(
            'header' => $this->_helper->__('ID'),
            'type' => 'number',
            'index' => 'id'
        ));

        $this->addColumn('default_name', array(
            'header' => $this->_helper->__('Default Name'),
            'index' => 'default_name'
        ));

        $this->addColumn('name', array(
            'header' => $this->_helper->__("%s Name", $this->_helper->getLocaleLabel()),
            'index' => 'name'
        ));
        
        $this->addColumn('default_abbr', array(
            'header' => $this->_helper->__('Default Abbr'),
            'index' => 'default_abbr'
        ));

        $this->addColumn('abbr', array(
            'header' => $this->_helper->__("%s Abbr", $this->_helper->getLocaleLabel()),
            'index' => 'abbr'
        ));
        
        $this->addColumn('default_mnemonic', array(
            'header' => $this->_helper->__('Default Mnemonic'),
            'index' => 'default_mnemonic'
        ));

        $this->addColumn('mnemonic', array(
            'header' => $this->_helper->__("%s Mnemonic", $this->_helper->getLocaleLabel()),
            'index' => 'mnemonic'
        ));        
        
        $this->addColumn('region_id', array(
            'header' => $this->_helper->__('Region'),
            'index' => 'region_id',
            'filter' => 'location/adminhtml_widget_grid_column_filter_region',
            'renderer' => 'location/adminhtml_widget_grid_column_renderer_region'
        ));
        
        $this->addColumn('parent_id', array(
            'header' => $this->_helper->__('Parent'),
            'index' => 'parent_id',
            'filter' => 'location/adminhtml_area_grid_column_filter_parent',
            'renderer' => 'location/adminhtml_widget_grid_column_renderer_area'
        ));
        
        $this->addColumn('type', array(
            'header' => $this->_helper->__('Type'),
            'index' => 'type',
            'type' => 'options',
            'options' => array(
                SunshineBiz_Location_Model_Area::TYPE_ADMINISTRATIVE_REGION_LEVEL_2 => $this->_helper->__('Level 2 Administrative Region'),
                SunshineBiz_Location_Model_Area::TYPE_ADMINISTRATIVE_REGION_LEVEL_3 => $this->_helper->__('Level 3 Administrative Region'),
                SunshineBiz_Location_Model_Area::TYPE_TRADE_CIRCLE => $this->_helper->__('Trade Circle')
            )
        ));
        
        $this->addColumn('is_active', array(
            'header' => $this->_helper->__('Status'),
            'index' => 'is_active',
            'type' => 'options',
            'options' => array(
                '1' => $this->_helper->__('Active'),
                '0' => $this->_helper->__('Inactive')
            )
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {

        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('area');

        $this->getMassactionBlock()->addItem('massChangeStatus', array(
            'label' => $this->_helper->__('Mass Change Status'),
            'url' => $this->getUrl('*/*/massChangeStatus'),
            'confirm' => $this->_helper->__('Are you sure?')
        ));
        
        $this->getMassactionBlock()->addItem('massDelete', array(
            'label' => $this->_helper->__('Mass Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => $this->_helper->__('Are you sure?')
        ));

        return $this;
    }
}