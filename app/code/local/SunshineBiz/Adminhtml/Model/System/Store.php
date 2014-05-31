<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Adminhtml
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Adminhtml_Model_System_Store extends Mage_Adminhtml_Model_System_Store {

    public function getStoreValuesForForm($empty = false, $all = false, $allSelectable = false) {

        if (!$allSelectable) {
            return parent::getStoreValuesForForm($empty, $all);
        }

        $options = array();
        if ($empty) {
            $options[] = array(
                'label' => Mage::helper('core')->__('-- Please Select --'),
                'value' => ''
            );
        }

        if ($all) {
            $options[] = array(
                'label' => Mage::helper('adminhtml')->__('All Store Views'),
                'value' => '0'
            );
        }

        $nonEscapableNbspChar = html_entity_decode('&#160;', ENT_NOQUOTES, 'UTF-8');

        foreach ($this->_websiteCollection as $website) {

            $options[] = array(
                'label' => $website->getName(),
                'value' => $website->getId()
            );

            foreach ($website->getGroupCollection() as $group) {
                $options[] = array(
                    'label' => str_repeat($nonEscapableNbspChar, 4) . $group->getName(),
                    'value' => $website->getId() . '-' . $group->getId()
                );

                foreach ($group->getStoreCollection() as $store) {
                    $options[] = array(
                        'label' => str_repeat($nonEscapableNbspChar, 8) . $store->getName(),
                        'value' => $website->getId() . '-' . $group->getId() . '-' . $store->getId()
                    );
                }
            }
        }

        return $options;
    }

}
