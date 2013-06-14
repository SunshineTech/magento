<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Adminhtml_JsonController extends Mage_Adminhtml_JsonController {

    public function regionAreaAction() {
        
        $arrArea = array();
        $regionId = $this->getRequest()->getParam('parent');
        $arrAreas = Mage::getResourceModel('location/area_collection')
                ->addRegionFilter($regionId)
                ->addFieldToFilter('type', array('lteq' => SunshineBiz_Location_Model_Area::TYPE_ADMINISTRATIVE_REGION_LEVEL_3))
                ->load()
                ->toOptionArray();

        if (!empty($arrAreas)) {
            foreach ($arrAreas as $area) {
                $arrArea[] = $area;
            }
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($arrArea));
    }

    public function areaCircleAction() {
        
        $arrCircle = array();
        $areaId = $this->getRequest()->getParam('parent');
        $arrCircles = Mage::getResourceModel('location/area_collection')
                ->addFieldToFilter('parent_id', $areaId)
                ->addFieldToFilter('type', SunshineBiz_Location_Model_Area::TYPE_TRADE_CIRCLE)
                ->load()
                ->toOptionArray();

        if (!empty($arrCircles)) {
            foreach ($arrCircles as $circle) {
                $arrCircle[] = $circle;
            }
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($arrCircle));
    }
}