<?php
class Magestore_Themeswitcher_Model_Rewrite_CatalogDesign 
	extends Mage_Catalog_Model_Design
{
    public function applyDesign($object, $calledFrom = 0)
    {
        parent::applyDesign($object, $calledFrom);
		
        $detector = new Magestore_Themeswitcher_Model_Detector();
		$theme = Mage::getModel('themeswitcher/theme')
								->loadByBrowser($detector->getBrowser(),$detector->getPlatform());
		$design = Mage::getDesign();
		$_helper = Mage::helper('themeswitcher');
		
		if($theme->getId()){
			if($theme->getTemplate()){
				$package = $_helper->getPackage($theme->getTemplate());
				$templatetheme = $_helper->getTheme($theme->getTemplate());
				if($package){
					$design->setPackageName($package);		
				}
				if($templatetheme){
					$design->setTheme('template',$templatetheme);		
				}
			}
			if($theme->getLayout()){
				$layouttheme = $_helper->getTheme($theme->getLayout());
				if($templatetheme){
					$design->setTheme('layout',$layouttheme);		
				}
			}
			if($theme->getSkin()){
				$skintheme = $_helper->getTheme($theme->getSkin());
				if($skintheme){
					$design->setTheme('skin',$skintheme);		
				}	
			}
		}
		return $this;
	}
	
}