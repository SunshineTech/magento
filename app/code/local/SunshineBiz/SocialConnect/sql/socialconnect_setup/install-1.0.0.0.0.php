<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
$installer = $this;
/* @var $installer SunshineBiz_SocialConnect_Model_Resource_Setup */
$installer->startSetup();

$installer->setCustomerAttributes(
        array(
            'socialconnect_gid' => array(
                'type' => 'text',
                'visible' => false,
                'required' => false,
                'user_defined' => false
            ),
            'socialconnect_gtoken' => array(
                'type' => 'text',
                'visible' => false,
                'required' => false,
                'user_defined' => false
            ),
            'socialconnect_fid' => array(
                'type' => 'text',
                'visible' => false,
                'required' => false,
                'user_defined' => false
            ),
            'socialconnect_ftoken' => array(
                'type' => 'text',
                'visible' => false,
                'required' => false,
                'user_defined' => false
            ),
            'socialconnect_tid' => array(
                'type' => 'text',
                'visible' => false,
                'required' => false,
                'user_defined' => false
            ),
            'socialconnect_ttoken' => array(
                'type' => 'text',
                'visible' => false,
                'required' => false,
                'user_defined' => false
            )
        )
);

$installer->installCustomerAttributes();

$installer->endSetup();