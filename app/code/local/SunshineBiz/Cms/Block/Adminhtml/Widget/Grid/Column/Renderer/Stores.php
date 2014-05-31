<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Block_Adminhtml_Widget_Grid_Column_Renderer_Stores extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        if ($data = $row->getData($this->getColumn()->getIndex())) {
            $stores = explode('-', $data);
            $websiteIds = array();
            $groupIds = array();
            $storeIds = array();
            $count = count($stores);
            switch ($count) {
                case 1:
                    $websiteIds[] = $stores[0];
                    break;
                case 2:                    
                    $groupIds[] = $stores[1];
                    break;
                default:
                    $storeIds[] = $stores[2];
                    break;
            }

            $data = Mage::getSingleton('adminhtml/system_store')->getStoresStructure(false, $storeIds, $groupIds, $websiteIds);
            $out = '';
            foreach ($data as $website) {
                $out .= $website['label'] . '<br/>';
                foreach ($website['children'] as $group) {
                    $out .= str_repeat('&nbsp;', 3) . $group['label'] . '<br/>';
                    foreach ($group['children'] as $store) {
                        $out .= str_repeat('&nbsp;', 6) . $store['label'] . '<br/>';
                    }
                }
            }

            return $out;
        }

        return Mage::helper('adminhtml')->__('All Store Views');
    }

}
