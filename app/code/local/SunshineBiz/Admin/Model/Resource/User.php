<?php

/**
 * SunshineBiz_Admin user resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Admin
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Admin_Model_Resource_User extends Mage_Admin_Model_Resource_User {

    /**
     * Save user roles
     *
     * @param Mage_Core_Model_Abstract $user
     * @return Mage_Admin_Model_Resource_User
     */
    public function add(Mage_Core_Model_Abstract $user) {

        $dbh = $this->_getWriteAdapter();

        if ($user->getId() > 0) {
            $role = Mage::getModel('admin/role')->load($user->getRoleId());
        } else {
            $role = new Varien_Object();
            $role->setTreeLevel(0);
        }

        $data = new Varien_Object(array(
            'parent_id' => $user->getRoleId(),
            'tree_level' => ($role->getTreeLevel() + 1),
            'sort_order' => 0,
            'role_type' => 'U',
            'user_id' => $user->getUserId(),
            'role_name' => $user->getFirstname()
        ));

        $insertData = $this->_prepareDataForTable($data, $this->getTable('admin/role'));

        $dbh->insert($this->getTable('admin/role'), $insertData);

        return $this;
    }

    /**
     * 用户的最终权限规则
     * 由用户的角色权限规则和用户权限规则计算得到
     */
    public function getAllRules(Mage_Core_Model_Abstract $user) {

        $roleRules = $this->getRoleRules($user);
        $userRules = $this->getUserRules($user);

        if (count($roleRules) <= 0) {
            return $userRules;
        }

        if (count($userRules) <= 0) {
            return $roleRules;
        }

        $allRules = array();
        foreach ($roleRules as $rolesRule) {
            $flag = true;
            foreach ($userRules as $userRule) {
                //角色权限规则和用户权限规则都有的，最终权限规则以用户权限规则为准
                if ($rolesRule['resource_id'] === $userRule['resource_id']) {
                    $allRules[] = $userRule;
                    $flag = false;
                    break;
                }
            }
            //角色权限规则里有，而用户权限规则里没有，最终权限规则以角色权限规则为准
            if ($flag) {
                $allRules[] = $rolesRule;
            }
        }

        foreach ($userRules as $userRule) {
            $flag = true;
            foreach ($roleRules as $rolesRule) {
                //角色权限规则和用户权限规则都有的，前面已处理过，不再处理
                if ($rolesRule['resource_id'] === $userRule['resource_id']) {
                    $flag = false;
                    break;
                }
            }
            //用户权限规则里有，而角色权限规则里没有，最终权限规则以用户权限规则为准
            if ($flag) {
                $allRules[] = $userRule;
            }
        }

        return $allRules;
    }

    /**
     * 得到用户所属的角色的权限规则<br/>
     * 即角色权限规则表admin_rule的数据
     */
    public function getRoleRules(Mage_Core_Model_Abstract $user) {

        $adapter = $this->_getReadAdapter();
        $select = Mage::getResourceModel('admin/rules_collection')
                ->addFieldToSelect(array('resource_id', 'permission'))
                ->addFieldToFilter('role_id', array('in' => $user->getData('user_in_role') ? $user->getData('user_in_role') : $user->getRoles()))
                ->getSelect()
                ->group(array('resource_id', 'permission'));

        $rolesRules = $adapter->fetchAll($select);

        return $rolesRules;
    }
    
    /**
     * 得到用户的权限规则<br/>
     * 即用户权限规则表admin_user_rule的数据
     */
    public function getUserRules(Mage_Core_Model_Abstract $user) {

        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
                ->from($this->getTable('sunshinebiz_admin/user_rule'), array('rule_id', 'resource_id', 'permission'))
                ->where('user_id = :user_id');

        $binds = array(
            'user_id' => $user->getId()
        );

        $userRules = $adapter->fetchAll($select, $binds);
        
        return $userRules;
    }
}
