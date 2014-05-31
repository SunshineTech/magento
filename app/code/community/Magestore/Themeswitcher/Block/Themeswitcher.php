<?php

class Magestore_Themeswitcher_Block_Themeswitcher extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getThemeswitcher() {
        if (!$this->hasData('themeswitcher')) {
            $this->setData('themeswitcher', Mage::registry('themeswitcher'));
        }
        return $this->getData('themeswitcher');
    }

    public function addFooterLink() {
        $footerBlock = $this->getParentBlock();
        try {
            require_once Mage::getBaseDir('base') . DS . 'lib' . DS . 'MobileDetect' . DS . 'Mobile_Detect.php';
        } catch (Exception $e) {
            
        }
        $detect = new Mobile_Detect;
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'computer');
        if ($footerBlock && ($deviceType == 'tablet' || $deviceType == 'mobile')) {
            if (Mage::getSingleton('core/session')->getfullScreen() != 'fulling') {
                $footerBlock->addLink(
                        $this->__('View full version'), 'themeswitcher/index/fullVersionSceen', 'themeswitcher', true, array(), 10
                );
            } else {
                $footerBlock->addLink(
                        $this->__('Back to default'), 'themeswitcher/index/mobileVersionSceen', 'themeswitcher', true, array(), 10
                );
            }
        }
        return $this;
    }

}
