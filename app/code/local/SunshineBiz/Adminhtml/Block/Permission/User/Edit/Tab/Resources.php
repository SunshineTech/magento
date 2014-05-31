<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Adminhtml
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Adminhtml_Block_Permission_User_Edit_Tab_Resources extends Mage_Adminhtml_Block_Permissions_Tab_Rolesedit {

    public function __construct() {
        Mage_Adminhtml_Block_Widget_Form::__construct();

        $this->setSelectedResources(Mage::helper('sunshinebiz_admin')->getAllowResources(Mage::registry('permissions_user')));
        $this->setTemplate('sunshinebiz/adminhtml/permission/resources.phtml');
    }

    public function getTabLabel() {
        return Mage::helper('sunshinebiz_adminhtml')->__('User Resources');
    }
    
    public function getResourcesUrl() {
        return $this->getUrl('*/*/resources', array('user_id' => Mage::registry('permissions_user')->getUserId()));
    }
}
