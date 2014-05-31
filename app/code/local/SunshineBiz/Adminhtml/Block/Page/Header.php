<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Adminhtml
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Adminhtml_Block_Page_Header extends Mage_Adminhtml_Block_Page_Header {

    const LOCALE_CACHE_KEY = 'footer_locale';
    const LOCALE_CACHE_LIFETIME = 7200;
    const LOCALE_CACHE_TAG = 'adminhtml';

    public function __construct() {
        parent::__construct();
        $this->setTemplate('sunshinebiz/adminhtml/page/header.phtml');
    }

    public function getLanguageSelect() {

        $locale = Mage::app()->getLocale();
        $cacheId = self::LOCALE_CACHE_KEY . $locale->getLocaleCode();
        $html = Mage::app()->loadCache($cacheId);

        if (!$html) {
            $html = $this->getLayout()->createBlock('adminhtml/html_select')
                    ->setName('locale')
                    ->setId('interface_locale')
                    ->setTitle(Mage::helper('page')->__('Interface Language'))
                    ->setExtraParams('style="width:200px"')
                    ->setValue($locale->getLocaleCode())
                    ->setOptions($locale->getTranslatedOptionLocales())
                    ->getHtml();
            Mage::app()->saveCache($html, $cacheId, array(self::LOCALE_CACHE_TAG), self::LOCALE_CACHE_LIFETIME);
        }

        return $html;
    }

    public function getChangeLocaleUrl() {
        return $this->getUrl('adminhtml/index/changeLocale');
    }

    public function getRefererParamName() {
        return Mage_Core_Controller_Varien_Action::PARAM_NAME_URL_ENCODED;
    }

    public function getUrlForReferer() {
        return $this->getUrlEncoded('*/*/*', array('_current' => true));
    }

    public function getMyAccountLink() {
        return $this->getUrl('adminhtml/system/account');
    }

}