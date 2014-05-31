<?php

/**
 * Cms index controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
require_once 'Mage/Cms/controllers/IndexController.php';

class SunshineBiz_Cms_IndexController extends Mage_Cms_IndexController {

    /**
     * Renders CMS Home page
     *
     * @param string $coreRoute
     */
    public function indexAction($coreRoute = null) {
        $pageId = Mage::getStoreConfig(Mage_Cms_Helper_Page::XML_PATH_HOME_PAGE);
        if (!Mage::getStoreConfig('web/default/custom_cms_home_page') || !Mage::helper('cms/page')->renderPage($this, $pageId)) {
            $this->_forward('noCustomIndex');
        }
    }
    
    public function noCustomIndexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Render CMS 404 Not found page
     *
     * @param string $coreRoute
     */
    public function noRouteAction($coreRoute = null) {
        $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
        $this->getResponse()->setHeader('Status', '404 File not found');

        if (!Mage::getStoreConfig('web/default/custom_cms_no_route') || !Mage::helper('cms/page')->renderPage($this, Mage::getStoreConfig(Mage_Cms_Helper_Page::XML_PATH_NO_ROUTE_PAGE))) {
            $this->_forward('defaultNoRoute');
        }
    }

}
