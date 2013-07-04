<?php

/**
 * SunshineBiz_Location region grid block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Adminhtml_Region_Grid extends SunshineBiz_Location_Block_Adminhtml_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('locationsRegionGrid');
        $this->setDefaultSort('country_id');
        $this->setDefaultDir('asc');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('location/region')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        
        $this->addColumn('region_id', array(
            'header' => $this->_helper->__('ID'),
            'type' => 'number',
            'index' => 'region_id',
            'filter_index' => 'main_table.region_id'
        ));

        $this->addColumn('code', array(
            'header' => $this->_helper->__('Code'),
            'index' => 'code'
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

        $this->addColumn('country_id', array(
            'header' => $this->_helper->__('Country'),
            'index' => 'country_id',
            'type' => 'country',
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {

        $this->setMassactionIdField('region_id');
        $this->setMassactionIdFilter('main_table.region_id');
        $this->getMassactionBlock()->setFormFieldName('region');

        $this->getMassactionBlock()->addItem('massDelete', array(
            'label' => $this->_helper->__('Mass Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => $this->_helper->__('Are you sure?')
        ));

        return $this;
    }
}