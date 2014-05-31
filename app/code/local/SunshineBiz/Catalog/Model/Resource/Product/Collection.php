<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Catalog
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Catalog_Model_Resource_Product_Collection extends Mage_Reports_Model_Resource_Product_Collection {

    public function addCartsCount() {
        $countSelect = clone $this->getSelect();
        $countSelect->reset();

        $countSelect->from(array('quote_items' => $this->getTable('sales/quote_item')), 'SUM(quote_items.qty)')
                ->join(array('quotes' => $this->getTable('sales/quote')), 'quotes.entity_id = quote_items.quote_id AND quotes.is_active = 1', array())
                ->where("quote_items.product_id = e.entity_id");

        $this->getSelect()
                ->columns(array("carts" => "({$countSelect})"))
                ->group("e.entity_id")
                ->having('carts > ?', 0);

        return $this;
    }
    
    public function addOrderedQty($from = '', $to = '')
    {
        $adapter              = $this->getConnection();
        $orderTableAliasName  = $adapter->quoteIdentifier('order');

        $orderJoinCondition   = array(
            $orderTableAliasName . '.entity_id = order_items.order_id',
            $adapter->quoteInto("{$orderTableAliasName}.state <> ?", Mage_Sales_Model_Order::STATE_CANCELED),

        );

        $productJoinCondition = array(            
            'e.entity_id = order_items.product_id',
            $adapter->quoteInto('e.entity_type_id = ?', $this->getProductEntityTypeId())
        );

        if ($from != '' && $to != '') {
            $fieldName            = $orderTableAliasName . '.created_at';
            $orderJoinCondition[] = $this->_prepareBetweenSql($fieldName, $from, $to);
        }

        $this->getSelect()->reset()
            ->from(
                array('order_items' => $this->getTable('sales/order_item')),
                array(
                    'ordered_qty' => 'SUM(order_items.qty_ordered)',
                    'order_items_name' => 'order_items.name'
                ))
            ->joinInner(
                array('order' => $this->getTable('sales/order')),
                implode(' AND ', $orderJoinCondition),
                array())
            ->joinLeft(
                array('e' => $this->getProductEntityTableName()),
                implode(' AND ', $productJoinCondition),
                array(
                    'entity_id' => 'order_items.product_id',
                    'entity_type_id' => 'e.entity_type_id',
                    'attribute_set_id' => 'e.attribute_set_id',
                    'type_id' => 'e.type_id',
                    'sku' => 'e.sku',
                    'has_options' => 'e.has_options',
                    'required_options' => 'e.required_options',
                    'created_at' => 'e.created_at',
                    'updated_at' => 'e.updated_at'
                ))
            ->where('parent_item_id IS NULL')
            ->group('order_items.product_id')
            ->having('SUM(order_items.qty_ordered) > ?', 0);
        
        return $this;
    }
    
    public function addWishlistsCount($from = '', $to = '') {
        $adapter              = $this->getConnection();
        
        $productJoinCondition = array(
            'e.entity_id = wishlist_items.product_id',
            $adapter->quoteInto('e.entity_type_id = ?', $this->getProductEntityTypeId())
        );
        
        $this->getSelect()->reset()
            ->from(
                array('wishlist_items' => $this->getTable('wishlist/item')),
                array(
                    'wishlisted_qty' => 'SUM(wishlist_items.qty)'
                ))
            ->joinLeft(
                array('e' => $this->getProductEntityTableName()),
                implode(' AND ', $productJoinCondition),
                array(
                    'entity_id' => 'wishlist_items.product_id',
                    'entity_type_id' => 'e.entity_type_id',
                    'attribute_set_id' => 'e.attribute_set_id',
                    'type_id' => 'e.type_id',
                    'sku' => 'e.sku',
                    'has_options' => 'e.has_options',
                    'required_options' => 'e.required_options',
                    'created_at' => 'e.created_at',
                    'updated_at' => 'e.updated_at'
                ))
            ->group('wishlist_items.product_id')
            ->having('SUM(wishlist_items.qty) > ?', 0);
        
        return $this;
    }

}
