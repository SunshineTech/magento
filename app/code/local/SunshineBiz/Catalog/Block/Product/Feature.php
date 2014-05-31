<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Catalog_Block_Product_Feature extends Mage_Core_Block_Template {

    protected $_allMethods = array('bestseller', 'biggestsaving', 'featured', 'mostcarted', 'mostreviewed', 'mostviewed', 'mostwishlisted', 'newlisting', 'toprated');
    protected $_methods = null;
    protected $_categoryId;
    protected $_position = '';

    protected function _prepareLayout() {
        $this->_categoryId = $this->getRequest()->getParam('id');
        $position = explode('.', $this->getNameInLayout());
        $this->_position = $position[0];
        
        $methods = $this->getValidMethods();
        foreach ($methods as $method) {
            $block = $this->getLayout()->createBlock('sunshinebiz_catalog/product_' . $method['code'])
                    ->setCategoryId($this->_categoryId)
                    ->setDataFrom($this->getConfig($method['code'], 'data_from'))
                    ->setMode($this->getConfig($method['code'], 'mode'))
                    ->setProductsCount($this->getConfig($method['code'], 'amount'));
            
            if($method['code'] == 'newlisting') {
                $block->setNewdays($this->getConfig($method['code'], 'new_days'));
            }

            $this->setChild($this->_position . '.product.' . $method['code'], $block);
        }

        return parent::_prepareLayout();
    }

    protected function getConfig($code, $attribute) {
        $append = '_';
        if (isset($this->_categoryId) && is_numeric($this->_categoryId)) {
            $append = '_category_';
        }
        
        $prefix = 'feature/' . $code . $append . $this->_position;
        return Mage::getStoreConfig($prefix . '/' . $attribute);
    }

    protected function getValidMethods() {
        if (is_null($this->_methods)) {
            $this->_methods = array();

            foreach ($this->_allMethods as $method) {
                if ($this->getConfig($method , 'display')) {
                    $this->_methods[] = array(
                        'code' => $method,
                        'sort_order' => $this->getConfig($method , 'order')
                    );
                }
            }

            usort($this->_methods, function($a, $b) {
                return (int) $a['sort_order'] > (int) $b['sort_order'] ? 1 : ((int) $a['sort_order'] < (int) $b['sort_order'] ? -1 : 0);
            });
        }

        return $this->_methods;
    }

    protected function _toHtml() {
        $html = '';
        $methods = $this->getValidMethods($this->_position, $this->_categoryId);
        foreach ($methods as $method) {
            $html .= $this->getChildHtml($this->_position . '.product.' . $method['code']);
        }

        return $html;
    }

}
