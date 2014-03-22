<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$data = array(
    array(
        'status' => 'alipay_wait_buyer_pay',
        'label'  => 'Waiting Payment (Alipay)'
    ),
    array(
        'status' => 'alipay_wait_seller_send_goods',
        'label'  => 'Waiting Delivery (Alipay)'
    ),
    array(
        'status' => 'alipay_wait_buyer_confirm_goods',
        'label'  => 'Waiting Receipt (Alipay)'
    ),
    array(
        'status' => 'alipay_trade_finished',
        'label'  => 'Trade Finished (Alipay)'
    ),
    array(
        'status' => 'alipay_trade_success',
        'label'  => 'Trade Success (Alipay)'
    ),
);

$installer->getConnection()->insertArray(
    $installer->getTable('sales/order_status'),
    array('status', 'label'),
    $data
);