<?php

class Magestore_Themeswitcher_Block_Adminhtml_Themeswitcher_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('themeswitcherGrid');
        $this->setDefaultSort('theme_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('themeswitcher/theme')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('theme_id', array(
            'header' => Mage::helper('themeswitcher')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'theme_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('themeswitcher')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('template', array(
            'header' => Mage::helper('themeswitcher')->__('Template'),
            'align' => 'left',
            'type' => 'options',
            'options' => Mage::helper('themeswitcher')->getTemplateList(),
            'index' => 'template',
        ));

        $this->addColumn('cmshomepage', array(
            'header' => Mage::helper('themeswitcher')->__('CMS Home Page'),
            'align' => 'left',
            'type' => 'options',
            'options' => Mage::helper('themeswitcher')->getCmshomepage(),
            'index' => 'cmshomepage',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('themeswitcher')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => Mage::helper('themeswitcher')->__('Enabled'),
                2 => Mage::helper('themeswitcher')->__('Disabled'),
            ),
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('themeswitcher')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('themeswitcher')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('themeswitcher')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('themeswitcher')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('theme_id');
        $this->getMassactionBlock()->setFormFieldName('themeswitcher');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('themeswitcher')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('themeswitcher')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('themeswitcher/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('themeswitcher')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('themeswitcher')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
