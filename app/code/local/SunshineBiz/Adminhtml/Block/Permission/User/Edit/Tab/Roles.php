<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Adminhtml
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Adminhtml_Block_Permission_User_Edit_Tab_Roles extends Mage_Adminhtml_Block_Permissions_User_Edit_Tab_Roles {

    public function __construct() {
        if(Mage::registry('permissions_user')->getId())
            $this->setDefaultFilter(array('assigned_user_role' => 1));
        parent::__construct();
    }
    
    protected function _prepareColumns() {

        $this->addColumn('assigned_user_role', array(
            'header_css_class' => 'a-center',
            'header' => Mage::helper('adminhtml')->__('Assigned'),
            'type' => 'checkbox',
            'values' => $this->_getSelectedRoles(),
            'align' => 'center',
            'index' => 'role_id'
        ));

        $this->addColumn('role_name', array(
            'header' => Mage::helper('adminhtml')->__('Role Name'),
            'index' => 'role_name'
        ));

        return $this;
    }
}