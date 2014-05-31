<?php

/**
 * Catalog Product feature Attributes Backend Model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Catalog_Model_Product_Attribute_Backend_Feature extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract {

    public function beforeSave($object) {
        if (($feature = $object->getData($this->getAttribute()->getName()))) {
            $object->setData($this->getAttribute()->getName(), implode(',', $feature));
        }
    }

}
