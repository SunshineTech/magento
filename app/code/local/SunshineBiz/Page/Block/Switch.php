<?php

/**
 * Html page header block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Page
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Page_Block_Switch extends Mage_Page_Block_Switch {

    /**
     * Session entity
     *
     * @var Mage_Core_Model_Session_Abstract
     */
    protected $_session;

    public function getSkins() {
        if (!$this->getData('skins') && Mage::getStoreConfig("design/theme/skin_selectable")) {
            $package = Mage::getDesign();
            $theme = $package->getTheme('default');
            $skinList = $package->getSkinList($package->getPackageName(), $theme ? $theme : 'default');
            $currentSkin = $package->getTheme('skin');
            $skins = array();
            $imageUrl = Mage::getBaseUrl('skin') . 'frontend/' . $package->getPackageName() . '/';
            foreach ($skinList as $skin) {
                if ($skin == $currentSkin) {
                    continue;
                }

                $skins[$skin] = $imageUrl . $skin . '/images/skin.gif';
            }

            $this->setData('skins', $skins);
        }

        return $this->getData('skins');
    }

    public function getCurrentUrl($toSkin, $fromSkin = true) {
        $sidQueryParam = $this->_getSession()->getSessionIdQueryParam();
        $requestString = Mage::getSingleton('core/url')->escape(
                ltrim(Mage::app()->getRequest()->getRequestString(), '/'));

        $storeUrl = Mage::app()->getStore()->isCurrentlySecure() ? $this->getUrl('', array('_secure' => true)) : $this->getUrl('');
        $storeParsedUrl = parse_url($storeUrl);

        $storeParsedQuery = array();
        if (isset($storeParsedUrl['query'])) {
            parse_str($storeParsedUrl['query'], $storeParsedQuery);
        }

        $currQuery = Mage::app()->getRequest()->getQuery();
        if (isset($currQuery[$sidQueryParam]) && !empty($currQuery[$sidQueryParam]) && $this->_getSession()->getSessionIdForHost($storeUrl) != $currQuery[$sidQueryParam]
        ) {
            unset($currQuery[$sidQueryParam]);
        }

        foreach ($currQuery as $k => $v) {
            if($k != '___store' && $k != '___from_store') {
                $storeParsedQuery[$k] = $v;
            }            
        }

        $storeParsedQuery['___skin'] = $toSkin;
        if ($fromSkin !== false) {
            $storeParsedQuery['___from_skin'] = $fromSkin === true ? Mage::getDesign()->getTheme('skin') : $fromSkin;
        }

        return $storeParsedUrl['scheme'] . '://' . $storeParsedUrl['host']
                . (isset($storeParsedUrl['port']) ? ':' . $storeParsedUrl['port'] : '')
                . $storeParsedUrl['path'] . $requestString
                . ($storeParsedQuery ? '?' . http_build_query($storeParsedQuery, '', '&amp;') : '');
    }

    /**
     * Retrieve store session object
     *
     * @return Mage_Core_Model_Session_Abstract
     */
    protected function _getSession() {
        if (!$this->_session) {
            $this->_session = Mage::getModel('core/session')
                    ->init('store_' . Mage::app()->getStore()->getCode());
        }
        
        return $this->_session;
    }

}
