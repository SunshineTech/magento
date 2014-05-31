<?php

/**
 * SunshineBiz_Adminhtml Role controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Adminhtml
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
require_once 'Mage/Adminhtml/controllers/Permissions/RoleController.php';

class SunshineBiz_Adminhtml_Permissions_RoleController extends Mage_Adminhtml_Permissions_RoleController {

    public function saveRoleAction() {

        $rid = $this->getRequest()->getParam('role_id', false);

        $resource = $this->getRequest()->getParam('resource', false);
        $resource = str_replace('__root__,', '', $resource);
        $resource = str_replace(',__root__', '', $resource);
        $resource = str_replace('__root__', '', $resource);
        $resource = explode(',', $resource);
        $isAll = $this->getRequest()->getParam('all');
        if ($isAll)
            $resource = array("all");

        $oldRoleUsers = $this->getRequest()->getParam('in_role_user_old');
        parse_str($oldRoleUsers, $oldRoleUsers);
        $oldRoleUsers = array_keys($oldRoleUsers);

        $roleUsers = $this->getRequest()->getParam('in_role_user', null);
        parse_str($roleUsers, $roleUsers);
        $roleUsers = array_keys($roleUsers);

        $role = $this->_initRole('role_id');
        if (!$role->getId() && $rid) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('This Role no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        try {
            $roleName = $this->getRequest()->getParam('rolename', false);

            $role->setName($roleName)
                    ->setPid($this->getRequest()->getParam('parent_id', false))
                    ->setRoleType('G');
            Mage::dispatchEvent(
                    'admin_permissions_role_prepare_save', array('object' => $role, 'request' => $this->getRequest())
            );
            $role->save();

            Mage::getModel("admin/rules")
                    ->setRoleId($role->getId())
                    ->setResources($resource)
                    ->saveRel();

            foreach (array_diff($roleUsers, $oldRoleUsers) as $nRuid) {
                $this->_addUserToRole($nRuid, $role->getId());
            }

            foreach (array_diff($oldRoleUsers, $roleUsers) as $oUid) {
                $this->_deleteUserFromRole($oUid, $role->getId());
            }

            $rid = $role->getId();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The role has been successfully saved.'));
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('An error occurred while saving this role.'));
        }

        //$this->getResponse()->setRedirect($this->getUrl("*/*/editrole/rid/$rid"));
        $this->_redirect('*/*/');
        return;
    }
}
