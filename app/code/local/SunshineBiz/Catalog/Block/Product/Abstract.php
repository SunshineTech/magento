<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
abstract class SunshineBiz_Catalog_Block_Product_Abstract extends Mage_Catalog_Block_Product_Abstract {

    protected $_categoryId = 0;
    protected $_dataFrom;
    protected $_mode;
    protected $_productsCount;

    const DEFAULT_DATA_FROM = 'real data';
    const DEFAULT_PRODUCTS_COUNT = 10;

    protected function _beforeToHtml() {
        $this->setProductCollection($this->_getProductCollection());

        return parent::_beforeToHtml();
    }

    protected function _construct() {
        parent::_construct();

        $this->addColumnCountLayoutDepend('empty', 6)
                ->addColumnCountLayoutDepend('one_column', 5)
                ->addColumnCountLayoutDepend('two_columns_left', 4)
                ->addColumnCountLayoutDepend('two_columns_right', 4)
                ->addColumnCountLayoutDepend('three_columns', 3);
    }

    protected function _getProductCollection() {

        $collection = Mage::getResourceModel('catalog/product_collection');

        $this->_addProductAttributesAndPrices($collection);
        $this->addCategoryFilter($collection)
                ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds())
                ->addAttributeToFilter("feature", array('is' => new Zend_Db_Expr('not null')))
                ->addAttributeToFilter("feature", array(
                    array('eq' => $this->getCode()),
                    array('like' => $this->getCode() . ',%'),
                    array('like' => '%,' . $this->getCode() . ',%'),
                    array('like' => '%,' . $this->getCode())
                ))
                ->addAttributeToSort('created_at')
                ->setPageSize($this->getProductsCount())
                ->setCurPage(1);
        
        return $collection;
    }

    protected function _prepareLayout() {
        $this->setTemplate('sunshinebiz/catalog/product/feature.phtml');

        return parent::_prepareLayout();
    }

    protected function addCategoryFilter($collection) {
        $categoryId = $this->_categoryId ? $this->_categoryId : $reqId = $this->getRequest()->getParam('id', 0) ? $reqId : Mage::app()->getStore()->getRootCategoryId();

        return $collection->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id=entity_id')
                        ->addAttributeToFilter('category_id', array('in' => Mage::getModel('catalog/category')->load($categoryId)->getAllChildren(true)));
    }

    public function setCategoryId($categoryId) {
        $categoryId = (int) $categoryId;
        if ($categoryId > 0) {
            $this->_categoryId = $categoryId;
        }
        
        return $this;
    }

    public function setDataFrom($dataFrom) {
        $this->_dataFrom = $dataFrom;

        return $this;
    }

    public function getDataFrom() {
        if (null === $this->_dataFrom) {
            $this->_dataFrom = self::DEFAULT_DATA_FROM;
        }

        return $this->_dataFrom;
    }

    public function setMode($mode) {
        $this->_mode = $mode;

        return $this;
    }

    public function getMode() {
        if (null === $this->_mode) {
            $this->_mode = 'slider-horiz';
        }

        return $this->_mode;
    }

    public function setProductsCount($count) {
        $this->_productsCount = $count;

        return $this;
    }

    public function getProductsCount() {
        if (!$this->_productsCount) {
            $this->_productsCount = self::DEFAULT_PRODUCTS_COUNT;
        }
        
        return $this->_productsCount;
    }

    abstract protected function getCode();

    abstract protected function getTitle();
}
