<?php

/**
 * SunshineBiz_Location area model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Model_Area extends SunshineBiz_Locale_Model_Abstract {

    const TYPE_ADMINISTRATIVE_REGION_LEVEL_2 = 2;
    const TYPE_ADMINISTRATIVE_REGION_LEVEL_3 = 3;
    const TYPE_TRADE_CIRCLE = 6;

    protected $_helper;

    protected function _construct() {
        $this->_init('location/area');
        $this->_helper = Mage::helper('location');
    }

    protected function _beforeSave() {

        if ($this->getId() && $this->getId() == $this->getParentId())
            Mage::throwException($this->_helper->__('Parent can\'t be itself.'));

        if (!Zend_Validate::is($this->getDefaultName(), 'NotEmpty'))
            Mage::throwException($this->_helper->__('Default name is required field.'));

        $parent = Mage::getModel('location/area')->load($this->getParentId());
        if ($parent->getDefaultName() == $this->getDefaultName())
            Mage::throwException($this->_helper->__('This default name can\'t be the same as its parent\'s default name.'));

        if ($parent->getType() >= $this->getType())
            Mage::throwException($this->_helper->__('Type is wrong.'));
        
        if($this->getType() == self::TYPE_TRADE_CIRCLE && (!$parent->getType() || $parent->getType() != self::TYPE_ADMINISTRATIVE_REGION_LEVEL_3))
            Mage::throwException($this->_helper->__('Parent of trade circle must be Level 3 Administrative Region.'));

        if (!Zend_Validate::is($this->getRegionId(), 'NotEmpty'))
            Mage::throwException($this->_helper->__('Region is required field.'));

        return parent::_beforeSave();
    }

    public function getLocaleAbbr() {
        return $this->getData('abbr');
    }

    public function getAbbr() {
        $name = $this->getLocaleAbbr();
        if (is_null($name)) {
            $name = $this->getData('default_abbr');
        }
        return $name;
    }

}