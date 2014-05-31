<?php

/**
 * Design Observer model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Design
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Design_Model_Observer {

    public function switchSkin(Varien_Event_Observer $observer) {
        $request = $observer->getControllerAction()->getRequest();
        $storeCode = $toSkin = $request->getParam('___store');
        $store = $storeCode ? $storeCode : Mage::app()->getStore()->getCode();
        $cookieSkin = Mage::getModel('core/cookie')->get('skin_selected_' . $store);
        if (!Mage::getStoreConfig("design/theme/skin_selectable", $store)) {
            if ($cookieSkin) {
                Mage::getModel('core/cookie')->delete('skin_selected_' . $store);
            }
            
            return;
        }

        $toSkin = $request->getParam('___skin');
        $fromSkin = $request->getParam('___from_skin');
        $package = Mage::getDesign();
        $theme = $package->getTheme('default');
        if (!$storeCode && $toSkin && in_array($toSkin, $package->getSkinList($package->getPackageName(), $theme ? $theme : 'default')) && (!$fromSkin || $toSkin != $fromSkin)) {//Not switch store, only switch skin            
            $package->setArea('frontend')->setTheme('skin', $toSkin);
            Mage::getModel('core/cookie')->set('skin_selected_' . $store, $toSkin, true);
        } elseif ($cookieSkin) {
            if ($storeCode) {
                $package->setStore($storeCode)->setArea('frontend');
                $theme = $package->getTheme('default');
                $skinList = $package->getSkinList($package->getPackageName(), $theme ? $theme : 'default');
                if (count($skinList) < 1 || !in_array($cookieSkin, $skinList)) {
                    Mage::getModel('core/cookie')->delete('skin_selected_' . $storeCode);
                    return;
                }
            }
            
            $package->setArea('frontend')->setTheme('skin', $cookieSkin);
        } else {
            if ($storeCode) {
                $package->setStore($storeCode)->setArea('frontend');
            }
            
            $theme = $package->getTheme('default');
            $theme = $theme ? $theme : 'default';
            $package->setArea('frontend')->setTheme('skin', $theme);
            Mage::getModel('core/cookie')->set('skin_selected_' . $store, $theme, true);
        }
    }

}
