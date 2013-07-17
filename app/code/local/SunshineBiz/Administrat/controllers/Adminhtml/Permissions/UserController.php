<?php

/**
 * SunshineBiz_Administrat User controller
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Administrat
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
include_once("Mage/Adminhtml/controllers/Permissions/UserController.php");

class SunshineBiz_Administrat_Adminhtml_Permissions_UserController extends Mage_Adminhtml_Permissions_UserController {
    
    public function editAction()
    {
        $this->_title($this->__('System'))
             ->_title($this->__('Permissions'))
             ->_title($this->__('Users'));

        $id = $this->getRequest()->getParam('user_id');
        $model = Mage::getModel('admin/user');

        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This user no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New User'));

        // Restore previously entered form data from session
        $data = Mage::getSingleton('adminhtml/session')->getUserData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('permissions_user', $model);

        if (isset($id)) {
            $breadcrumb = $this->__('Edit User');
        } else {
            $breadcrumb = $this->__('New User');
        }
        $this->_initAction()
            ->_addBreadcrumb($breadcrumb, $breadcrumb);
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->getLayout()->getBlock('adminhtml.permissions.user.edit')
            ->setData('action', $this->getUrl('*/permissions_user/save'));

        $this->renderLayout();
    }
    
    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        if (!$data) {
            $this->_redirect('*/*/');
            return null;
        }
                
        $oldUserRoles = $this->getRequest()->getParam('user_roles_old', null);
        parse_str($oldUserRoles, $oldUserRoles);
        $oldUserRoles = array_keys($oldUserRoles);
        
        $userRoles = $this->getRequest()->getParam('user_roles', null);
        parse_str($userRoles, $userRoles);
        $userRoles = array_keys($userRoles);
        
        if(!$userRoles) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('The user must be assigned to at least one role.'));
            $this->_redirect('*/*/');
            return;
        }
        
        $resource = $this->getRequest()->getParam('resource', false);
        $resource = str_replace('__root__,', '', $resource);
        $resource = str_replace(',__root__', '', $resource);
        $resource = str_replace('__root__', '', $resource);
        $resource = explode(',', $resource);
        $isAll = $this->getRequest()->getParam('all');
        if ($isAll)
            $resource = array('all');
        
        $id = $this->getRequest()->getParam('user_id');
        $model = Mage::getModel('admin/user')->load($id);
        if (!$model->getId() && $id) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('This user no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $model->setData($data);

        /*
         * Unsetting new password and password confirmation if they are blank
         */
        if ($model->hasNewPassword() && $model->getNewPassword() === '') {
            $model->unsNewPassword();
        }
        if ($model->hasPasswordConfirmation() && $model->getPasswordConfirmation() === '') {
            $model->unsPasswordConfirmation();
        }

        $result = $model->validate();
        if (is_array($result)) {
            Mage::getSingleton('adminhtml/session')->setUserData($data);
            foreach ($result as $message) {
                Mage::getSingleton('adminhtml/session')->addError($message);
            }
            $this->_redirect('*/*/edit', array('_current' => true));
            return $this;
        }
        
        try {
            $model->save();
            
            foreach (array_diff($userRoles, $oldUserRoles) as $role) {
                $model->setRoleId($role)
                        ->setUserId($model->getUserId())
                        ->add();
            }

            foreach (array_diff($oldUserRoles, $userRoles) as $role) {
                Mage::getModel("admin/user")
                        ->setRoleId($role)
                        ->setUserId($model->getUserId())
                        ->deleteFromRole();
            }
            
            Mage::getModel('admin/rules')
                    ->setUserId($model->getId())
                    ->setResources($resource)
                    ->saveRel();
                
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The user has been saved.'));
            Mage::getSingleton('adminhtml/session')->setUserData(false);
            $this->_redirect('*/*/');
            return;
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setUserData($data);
            $this->_redirect('*/*/edit', array('user_id' => $model->getUserId()));
            return;
        }
    }
    
    public function resourcesAction() {
        
        $userRoles = $this->getRequest()->getParam('user_roles', null);
        parse_str($userRoles, $userRoles);
        $userRoles = array_keys($userRoles);
        
        $id = $this->getRequest()->getParam('user_id');
        $model = Mage::getModel('admin/user');

        if ($id) {
            $model->load($id);
        }
        $model->setData('user_in_role', $userRoles);
        Mage::register('permissions_user', $model);
        $this->getResponse()->setBody(
            $this->getLayout()
                ->createBlock('administrat/adminhtml_permission_user_edit_tab_resources')
                ->toHtml()
        );
    }
}
