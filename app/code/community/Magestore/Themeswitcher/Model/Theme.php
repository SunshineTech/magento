<?php

class Magestore_Themeswitcher_Model_Theme extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('themeswitcher/theme');
    }
	 public function loadByDeviceAndStore($device,$store){
        $collection = $this->getJoinedCollection()
                ->addFieldToFilter('browser','all')
                ->addFieldToFilter('platform', $device)
                ->addFieldToFilter('status', 1)
                ->addStoresFilter($store)
                ->setOrder('theme_id', 'DESC')
        ;
        if ($collection->getSize())
            return $collection->getFirstItem();


        $collection = $this->getJoinedCollection()
                ->addFieldToFilter('browser', 'all')
                ->addFieldToFilter('platform', 'all')
                ->addFieldToFilter('status', 1)
                ->addStoresFilter($store)
                ->setOrder('theme_id', 'DESC');
       
        if ($collection->getSize())
            return $collection->getFirstItem();

        return $this->setId(null);
        
    }
    public function loadByBrowserAndStore($browser,$platform,$store){
        
         $collection = $this->getJoinedCollection()
                ->addFieldToFilter('browser', $browser)
                ->addFieldToFilter('platform', $platform)
                ->addFieldToFilter('status', 1)
                ->addStoresFilter($store)
                ->setOrder('theme_id', 'DESC')
        ;
         
        if ($collection->getSize())
            return $collection->getFirstItem();
        $collection = $this->getJoinedCollection()
                ->addFieldToFilter('browser', 'all')
                ->addFieldToFilter('platform', $platform)
                ->addFieldToFilter('status', 1)
                ->addStoresFilter($store)
                ->setOrder('theme_id', 'DESC');
        
        if ($collection->getSize())
            return $collection->getFirstItem();

        $collection = $this->getJoinedCollection()
                ->addFieldToFilter('browser', $browser)
                ->addFieldToFilter('platform', 'all')
                ->addFieldToFilter('status', 1)
                ->addStoresFilter($store)
                ->setOrder('theme_id', 'DESC');
        if ($collection->getSize())
            return $collection->getFirstItem();

        $collection = $this->getJoinedCollection()
                ->addFieldToFilter('browser', 'all')
                ->addFieldToFilter('platform', 'all')
                ->addFieldToFilter('status', 1)
                ->addStoresFilter($store)
                ->setOrder('theme_id', 'DESC');
       
        if ($collection->getSize())
            return $collection->getFirstItem();

        return $this->setId(null);
    }
    public function loadByBrowser($browser, $platform) {
        $collection = $this->getJoinedCollection()
                ->addFieldToFilter('browser', $browser)
                ->addFieldToFilter('platform', $platform)
                ->addFieldToFilter('status', 1)
                ->setOrder('theme_id', 'DESC')
        ;
        if ($collection->getSize())
            return $collection->getFirstItem();

        $collection = $this->getJoinedCollection()
                ->addFieldToFilter('browser', 'all')
                ->addFieldToFilter('platform', $platform)
                ->addFieldToFilter('status', 1)
                ->setOrder('theme_id', 'DESC');
        if ($collection->getSize())
            return $collection->getFirstItem();

        $collection = $this->getJoinedCollection()
                ->addFieldToFilter('browser', $browser)
                ->addFieldToFilter('platform', 'all')
                ->addFieldToFilter('status', 1)
                ->setOrder('theme_id', 'DESC');
        if ($collection->getSize())
            return $collection->getFirstItem();

        $collection = $this->getJoinedCollection()
                ->addFieldToFilter('browser', 'all')
                ->addFieldToFilter('platform', 'all')
                ->addFieldToFilter('status', 1)
                ->setOrder('theme_id', 'DESC');
        if ($collection->getSize())
            return $collection->getFirstItem();

        return $this->setId(null);
    }

    public function getJoinedCollection() {
        $resource = Mage::getSingleton('core/resource');
        $themeBrowserTable = $resource->getTableName('themeswitcher_theme_browser');
        $themePlatformTable = $resource->getTableName('themeswitcher_theme_platform');
        $collection = Mage::getResourceModel('themeswitcher/theme_collection');
        $collection->getSelect()
                ->join($themeBrowserTable, 'main_table.theme_id=' . $themeBrowserTable . '.theme_id', array('browser' => 'browser'))
                ->join($themePlatformTable, 'main_table.theme_id=' . $themePlatformTable . '.theme_id', array('platform' => 'platform'))
        ;
        return $collection;
    }

    public function loadBrowser() {
        if (!$this->getId())
            return $this;

        $browsers = array();

        $themeBrowsers = Mage::getResourceModel('themeswitcher/themebrowser_collection')
                ->addFieldToFilter('theme_id', $this->getId());
        if (count($themeBrowsers))
            foreach ($themeBrowsers as $themeBrowser) {
                $browsers[] = $themeBrowser->getBrowser();
            }
        $this->setBrowser(implode(',', $browsers));
        return $this;
    }

    public function loadPlatform() {
        if (!$this->getId())
            return $this;

        $platforms = array();

        $themePlatforms = Mage::getResourceModel('themeswitcher/themeplatform_collection')
                ->addFieldToFilter('theme_id', $this->getId());
        if (count($themePlatforms))
            foreach ($themePlatforms as $themePlatform) {
                $platforms[] = $themePlatform->getPlatform();
            }
        $this->setPlatform(implode(',', $platforms));
        return $this;
    }

    public function assignBrowser() {
        $themeBrowser = Mage::getModel('themeswitcher/themebrowser');
        $browsers = $this->getBrowser();
        if (isset($browsers[0]) && $browsers[0] == 'all') {
            $browsers = array_keys(Mage::helper('themeswitcher')->getBrowserList());
            $browsers = array('all');
        }
        //add new themebrowsers
        if (count($browsers)) {
            foreach ($browsers as $browser) {
                $themeBrowser->loadByTheme($this->getId(), $browser);
                $themeBrowser->setThemeId($this->getId())
                        ->setBrowser($browser)
                        ->setStoreId($this->getStoreId())
                        ->save()
                        ->setId(null);
            }
        } else {
            $browsers = array(null);
        }
        //dellete old themebrowsers
        $themeBrowsers = $themeBrowser->getCollection()
                ->addFieldToFilter('theme_id', $this->getId())
                ->addFieldToFilter('browser', array('nin' => $browsers))
        ;
        if (count($themeBrowsers)) {
            foreach ($themeBrowsers as $themeBrowser) {
                $themeBrowser->delete();
            }
        }
        return $this;
    }

    public function assignPlatform() {
        $themePlatform = Mage::getModel('themeswitcher/themeplatform');
        $platforms = $this->getPlatform();
		//$cms = $this->getHomepage();
        if (isset($platforms[0]) && $platforms[0] == 'all') {
            $platforms = array_keys(Mage::helper('themeswitcher')->getPlatformList());
            $platforms = array('all');
        }
        //add new themebrowsers
        if (count($platforms)) {
            foreach ($platforms as $platform) {
                $themePlatform->loadByTheme($this->getId(), $platform);
                $themePlatform->setThemeId($this->getId())
                        ->setPlatform($platform)
						
                        ->setStoreId($this->getStoreId())
                        ->save()
                        ->setId(null);
            }
        } else {
            $platforms = array(null);
        }
        //dellete old themebrowsers
        $themePlatforms = $themePlatform->getCollection()
                ->addFieldToFilter('theme_id', $this->getId())
                ->addFieldToFilter('platform', array('nin' => $platforms))
        ;
        if (count($themePlatforms)) {
            foreach ($themePlatforms as $themePlatform) {
                $themePlatform->delete();
            }
        }
        return $this;
    }

}