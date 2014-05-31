<?php

class Magestore_Themeswitcher_Model_Observer {

    public function controller_action_predispatch($observer) {
        //$detector = new Magestore_Themeswitcher_Model_Detector();
		try{
			require_once Mage::getBaseDir('base').DS.'lib'.DS.'MobileDetect'.DS.'Mobile_Detect.php';
		}catch(Exception $e){}
        $detect = new Mobile_Detect;
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'computer');
        
        $scriptVersion = $detect->getScriptVersion();
        if(Mage::getSingleton('core/session')->getfullScreen() == 'fulling'){
            $theme = Mage::getModel('themeswitcher/theme')
                ->loadByDeviceAndStore('computer',Mage::app()->getStore()->getStoreId());
        }
        else{
            $theme = Mage::getModel('themeswitcher/theme')
                ->loadByDeviceAndStore($deviceType,Mage::app()->getStore()->getStoreId());
        }
        
        $design = Mage::getDesign();
        $_helper = Mage::helper('themeswitcher');

        


        if ($theme->getId()) {
            if ($theme->getTemplate()) {
                $package = $_helper->getPackage($theme->getTemplate());
                $templatetheme = $_helper->getTheme($theme->getTemplate());
                if ($package) {
                    $design->setPackageName($package);
                }
                if ($templatetheme) {
                    $design->setTheme('template', $templatetheme);
                }
            }
            if ($theme->getLayout()) {
                $layouttheme = $_helper->getTheme($theme->getLayout());
                if ($templatetheme) {
                    $design->setTheme('layout', $layouttheme);
                }
            }
            if ($theme->getSkin()) {
                $skintheme = $_helper->getTheme($theme->getSkin());
                if ($skintheme) {
                    $design->setTheme('skin', $skintheme);
                }
            }
        }
    }
	
	public function cmsHomepage($observer)
    {
       try{
			require_once Mage::getBaseDir('base').DS.'lib'.DS.'MobileDetect'.DS.'Mobile_Detect.php';
		}catch(Exception $e){}
        $detect = new Mobile_Detect;
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'computer');
        if(Mage::getSingleton('core/session')->getfullScreen() == 'fulling'){
            $theme = Mage::getModel('themeswitcher/theme')
                ->loadByDeviceAndStore('computer',Mage::app()->getStore()->getStoreId());
        }
        else{
            $theme = Mage::getModel('themeswitcher/theme')
                ->loadByDeviceAndStore($deviceType,Mage::app()->getStore()->getStoreId());
        }
        
        $pageId = $theme->getCmshomepage();
        if ($theme->getId() && $pageId != '') {
 
            $object = $observer->getEvent()->getObject(); 
            $action = $observer->getEvent()->getControllerAction();

            if (!Mage::helper('cms/page')->renderPage($action, $pageId)) {
                $action->_forward('defaultIndex');
            }
            $action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
            //them phan nay de ko goi den controller dc bat event nua
            //lam cho event giong override hay rewrite hon
        }
    }

}