<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Administrat
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Administrat_Block_Adminhtml_Permission_User_Edit_Tab_Resources extends Mage_Adminhtml_Block_Permissions_Tab_Rolesedit {

    public function __construct() {
        Mage_Adminhtml_Block_Widget_Form::__construct();

        $this->setSelectedResources(Mage::helper('administrat')->getAllowResources(Mage::registry('permissions_user')));
        $this->setTemplate('administrat/permission/resources.phtml');
    }

    public function getTabLabel() {
        return Mage::helper('administrat')->__('User Resources');
    }
    
    public function getResourcesUrl() {
        return $this->getUrl('*/*/resources', array('user_id' => Mage::registry('permissions_user')->getUserId()));
    }
}
