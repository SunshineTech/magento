<?php

/**
 * SunshineBiz_Location region model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Model_Region extends SunshineBiz_Locale_Model_Abstract {

    protected $_helper;

    protected function _construct() {
        $this->_init('location/region');
        $this->_helper = Mage::helper('location');
    }

    protected function _beforeSave() {
        
        if (!Zend_Validate::is($this->getDefaultName(), 'NotEmpty'))
            Mage::throwException($this->_helper->__('Default name is required field.'));
        
        if (!Zend_Validate::is($this->getCountryId(), 'NotEmpty'))
            Mage::throwException($this->_helper->__('Country is required field.'));
        
        if($this->_getResource()->isDefaultNameSameAsLocaleName($this))
            Mage::throwException($this->_helper->__('Default name can\'t be same as locale name of the other regions in the same country.'));
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

    public function getAreas() {

        $collection = Mage::getResourceModel('location/area_collection');
        $collection->addRegionFilter($this->getId());
        $collection->load();

        return $collection;
    }

}