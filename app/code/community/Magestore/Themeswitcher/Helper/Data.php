<?php

class Magestore_Themeswitcher_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getBrowsers() {
        $list = array();
        $list['Opera']['name'] = 'Opera';                               // http://www.opera.com/
        $list['Opera']['url'] = 'http://www.opera.com';
        $list['Opera Mini']['name'] = 'Opera Mini';                      // http://www.opera.com/mini/
        $list['Opera Mini']['url'] = 'http://www.opera.com/mini';
        $list['WebTV']['name'] = 'WebTV';                                // http://www.webtv.net/pc/
        $list['WebTV']['url'] = 'http://www.webtv.net/pc';
        $list['Internet Explorer']['name'] = 'Internet Explorer';                   // http://www.microsoft.com/ie/
        $list['Internet Explorer']['url'] = 'http://www.microsoft.com/ie';
        $list['Pocket Internet Explorer']['name'] = 'Pocket Internet Explorer';    // http://en.wikipedia.org/wiki/Internet_Explorer_Mobile
        $list['Pocket Internet Explorer']['url'] = 'http://en.wikipedia.org/wiki/Internet_Explorer_Mobile';
        $list['Konqueror']['name'] = 'Konqueror';                        // http://www.konqueror.org/
        $list['Konqueror']['url'] = ' http://www.konqueror.org';
        $list['iCab']['name'] = 'iCab';                                  // http://www.icab.de/
        $list['iCab']['url'] = 'http://www.icab.de';
        $list['OmniWeb']['name'] = 'OmniWeb';                            // http://www.omnigroup.com/applications/omniweb/
        $list['OmniWeb']['url'] = 'http://www.omnigroup.com/applications/omniweb';
        $list['Firebird']['name'] = 'Firebird';                          // http://www.ibphoenix.com/
        $list['Firebird']['url'] = 'http://www.ibphoenix.com';
        $list['Firefox']['name'] = 'Firefox';                             // http://www.mozilla.com/en-US/firefox/firefox.html
        $list['Firefox']['url'] = 'http://www.mozilla.com/en-US/firefox/firefox.html';
        $list['Iceweasel']['name'] = 'Iceweasel';                      // http://www.geticeweasel.org/
        $list['Iceweasel']['url'] = 'http://www.geticeweasel.org';
        $list['Shiretoko']['name'] = 'Shiretoko';                        // http://wiki.mozilla.org/Projects/shiretoko
        $list['Shiretoko']['url'] = 'http://wiki.mozilla.org/Projects/shiretoko';
        $list['Mozilla']['name'] = 'Mozilla';                            // http://www.mozilla.com/en-US/
        $list['Mozilla']['url'] = 'http://www.mozilla.com/en-US';
        $list['Amaya']['name'] = 'Amaya';                                // http://www.w3.org/Amaya/
        $list['Amaya']['url'] = 'http://www.w3.org/Amaya';
        $list['Lynx']['name'] = 'Lynx';                                 // http://en.wikipedia.org/wiki/Lynx
        $list['Lynx']['url'] = 'http://en.wikipedia.org/wiki/Lynx';
        $list['Safari']['name'] = 'Safari';                              // http://www.apple.com/safari/
        $list['Safari']['url'] = 'http://www.apple.com/safari';
        $list['iPhone']['name'] = 'iPhone';                              // http://apple.com
        $list['iPhone']['url'] = 'http://apple.com';
        $list['iPod']['name'] = 'iPod';                                  // http://apple.com
        $list['iPod']['url'] = ' http://apple.com';
        $list['iPad']['name'] = 'iPad';                                  // http://apple.com
        $list['iPad']['url'] = 'http://apple.com';
        $list['Chrome']['name'] = 'Chrome';                              // http://www.google.com/chrome
        $list['Chrome']['url'] = 'http://www.google.com/chrome';
        $list['Android']['name'] = 'Android';                           // http://www.android.com/
        $list['Android']['url'] = 'http://www.android.com';
        $list['GoogleBot']['name'] = 'GoogleBot';                        // http://en.wikipedia.org/wiki/Googlebot
        $list['GoogleBot']['url'] = 'http://en.wikipedia.org/wiki/Googlebot';
        $list['Yahoo! Slurp']['name'] = 'Yahoo! Slurp';                        // http://en.wikipedia.org/wiki/Yahoo!_Slurp
        $list['Yahoo! Slurp']['url'] = 'http://en.wikipedia.org/wiki/Yahoo!_Slurp';
        $list['W3C Validator']['name'] = 'W3C Validator';                 // http://validator.w3.org/
        $list['W3C Validator']['url'] = 'http://validator.w3.org';
        $list['BlackBerry']['name'] = 'BlackBerry';                      // http://www.blackberry.com/
        $list['BlackBerry']['url'] = 'http://www.blackberry.com';
        $list['IceCat']['name'] = 'IceCat';                              // http://en.wikipedia.org/wiki/GNU_IceCat
        $list['IceCat']['url'] = 'http://en.wikipedia.org/wiki/GNU_IceCat';
        $list['Nokia S60 OSS Browser']['name'] = 'Nokia S60 OSS Browser';         // http://en.wikipedia.org/wiki/Web_Browser_for_S60
        $list['Nokia S60 OSS Browser']['url'] = 'http://en.wikipedia.org/wiki/Web_Browser_for_S60';
        $list['Nokia Browser']['name'] = 'Nokia Browser';                      // * all other WAP-based browsers on the Nokia Platform
        $list['Nokia Browser']['url'] = 'http://www.nokia.com';
        $list['MSN Browser']['name'] = 'MSN Browser';                          // http://explorer.msn.com/
        $list['MSN Browser']['url'] = 'http://explorer.msn.com';
        $list['MSN Bot']['name'] = 'MSN Bot';                             // http://search.msn.com/msnbot.htm
        $list['MSN Bot']['url'] = 'http://search.msn.com/msnbot.htm';
        $list['Netscape Navigator']['name'] = 'Netscape Navigator';      // http://browser.netscape.com/ (DEPRECATED)
        $list['Netscape Navigator']['url'] = 'http://browser.netscape.com';
        $list['Galeon']['name'] = 'Galeon';                              // http://galeon.sourceforge.net/ (DEPRECATED)
        $list['Galeon']['url'] = 'http://galeon.sourceforge.net';
        $list['NetPositive']['name'] = 'NetPositive';                   // http://en.wikipedia.org/wiki/NetPositive (DEPRECATED)
        $list['NetPositive']['url'] = 'http://en.wikipedia.org/wiki/NetPositive';
        $list['Phoenix']['name'] = 'Phoenix';            // http://en.wikipedia.org/wiki/History_of_Mozilla_Firefox (DEPRECATED)
        $list['Phoenix']['url'] = 'http://en.wikipedia.org/wiki/History_of_Mozilla_Firefox';
        return $list;
    }

    public function getPlatforms() {
        $list = array();
        $list['computer']['name'] = 'Computer';
        $list['mobile']['name'] = 'Mobile';
        $list['tablet']['name'] = 'Tablet';
        return $list;
    }

    public function getBrowserList() {
        $list = array();
        $list['all'] = $this->__('All');
        $browsers = $this->getBrowsers();
        foreach ($browsers as $key => $browser) {
            $list[$key] = $browser['name'];
        }
        return $list;
    }

    public function getBrowserOption() {
        $options = array();
        $list = $this->getBrowserList();
        if (count($list)) {
            foreach ($list as $key => $item) {
                $options[] = array('value' => $key, 'label' => $item);
            }
        }
        return $options;
    }

    public function getPlatformList() {
        $list = array();
        $list['all'] = $this->__('All');
        $platforms = $this->getPlatforms();
        foreach ($platforms as $key => $platform) {
            $list[$key] = $platform['name'];
        }
        return $list;
    }

    public function getPlatformOption() {
        $options = array();
        $list = $this->getPlatformList();
        if (count($list)) {
            foreach ($list as $key => $item) {
                $options[] = array('value' => $key, 'label' => $item);
            }
        }
        return $options;
    }

    public function getTemplateList() {
        $list = array();
        $options = $this->getTemplateOption();
        if (count($options)) {
            foreach ($options as $key => $option) {
                if ($key == 0)
                    continue;
                if (count($option['value'])) {
                    foreach ($option['value'] as $item) {
                        $list[$item['value']] = $item['value'];
                    }
                }
            }
        }
        return $list;
    }

    public function getTemplateOption() {
        return Mage::getSingleton('core/design_source_design')->getAllOptions();
    }

    public function getLayoutList() {
        return $this->getTemplateList();
    }

    public function getLayoutOption() {
        return $this->getTemplateOption();
    }

    public function getSkinList() {
        $list = array();
        $options = $this->getSkinOption();
        if (count($options)) {
            foreach ($options as $key => $option) {
                if ($key == 0)
                    continue;
                if (count($option['value'])) {
                    foreach ($option['value'] as $item) {
                        $list[$item['value']] = $item['value'];
                    }
                }
            }
        }
        return $list;
    }

    public function getSkinOption() {
        return Mage::getSingleton('themeswitcher/source_skin')->getAllOptions();
    }

    public function getPackage($path) {
        $arr = explode('/', $path);
        if (isset($arr[0]))
            return $arr[0];
        return null;
    }

    public function getTheme($path) {
        $arr = explode('/', $path);
        if (isset($arr[1]))
            return $arr[1];
        if (isset($arr[0]))
            return $arr[0];
        return null;
    }

    public function getStoreList() {
        $list = array();
        $list[] = array('value' => 0, 'label' => 'All');
        $stores = Mage::app()->getStores();
        if (count($stores)) {
            foreach ($stores as $store) {
                $list[] = array('value' => $store->getId(), 'label' => $store->getName());
            }
        }
        return $list;
    }

    public function getPageList() {
        $_option = array();
        $pageList = Mage::getModel('cms/page')->getCollection();
        //Zend_Debug::dump($pageList->getTitle());die();
        $_option[] = array('value' => '', 'label' => $this->__('-- Please Select --'));
        foreach ($pageList as $page) {
            //$_option[] = array('value' => $page->getId(), 'label' => $page->getTitle());
            $_option[] = array('value' => $page->getIdentifier(), 'label' => $page->getTitle());
        }
        return $_option;
    }

    public function getCmshomepage() {
        $_option = array();
        $pageList = Mage::getModel('cms/page')->getCollection();
        foreach ($pageList as $page) {
            $_option[$page->getIdentifier()] = $page->getIdentifier();
        }
        return $_option;
    }

}
