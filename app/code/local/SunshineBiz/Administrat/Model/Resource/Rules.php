<?php

/**
 * SunshineBiz_Administrat rules resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Administrat
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Administrat_Model_Resource_Rules extends Mage_Admin_Model_Resource_Rules {

    //保存权限规则
    public function saveRel(Mage_Admin_Model_Rules $rule) {

        if ($rule->getRoleId() > 0) {
            $this->saveRoleRules($rule);
        } elseif ($rule->getUserId() > 0) {
            $this->saveUserRules($rule);
        }
    }

    //保存角色权限规则
    protected function saveRoleRules(Mage_Admin_Model_Rules $rule) {

        $roleId = $rule->getRoleId();

        $read = $this->_getReadAdapter();
        $select = $read->select()
                ->from($this->getMainTable(), 'resource_id')
                ->where('role_id = ?', (int) $roleId);
        $resources = $read->fetchCol($select); //角色现存的权限规则的资源

        $postedResources = $rule->getResources(); //修改后的资源，即新的权限规则的资源
        //计算新的权限规则的资源是否为全部资源
        if ($postedResources && $postedResources !== array('all')) {
            if (!array_diff(array_keys(Mage::getModel('admin/roles')->getResourcesList()), array('all', 'admin'), $postedResources))
                $postedResources = array('all');
        }

        //新增的权限规则的资源，在新的权限规则的资源中存在，而不存在于现存的权限规则的资源中
        $insertResources = array_diff($postedResources, $resources);
        //删除的权限规则的资源，在现存的权限规则的资源中存在，而不存在于新的权限规则的资源中
        $deleteResources = array_diff($resources, $postedResources);

        $adapter = $this->_getWriteAdapter();
        $adapter->beginTransaction();
        try {
            if ($insertResources) {
                $row = array(
                    'role_type' => 'G',
                    'privileges' => '', // not used yet
                    'assert_id' => 0,
                    'role_id' => $roleId,
                    'permission' => 'allow'
                );

                $datas = array();
                foreach ($insertResources as $insertResource)
                    $datas[] = array_merge($row, array('resource_id' => $insertResource));

                $adapter->insertMultiple($this->getMainTable(), $datas);
            }

            if ($deleteResources) {
                if ($postedResources) {
                    $condition = array(
                        'role_id = ?' => (int) $roleId,
                        'resource_id IN (?)' => $deleteResources
                    );

                    $adapter->delete($this->getMainTable(), $condition);
                } else {
                    //如果新的权限规则的资源为空，即没有任何资源的权限，那么删除现存的权限规则
                    $condition = array(
                        'role_id = ?' => (int) $roleId,
                    );

                    $adapter->delete($this->getMainTable(), $condition);
                }
            }

            $adapter->commit();
        } catch (Mage_Core_Exception $e) {
            $adapter->rollBack();
            throw $e;
        } catch (Exception $e) {
            $adapter->rollBack();
            Mage::logException($e);
        }
    }

    protected function saveUserRules(Mage_Admin_Model_Rules $rule) {

        $userId = $rule->getUserId();
        $user = Mage::getModel('admin/user')->load($userId);
        $allowResources = Mage::helper('administrat')->getAllowResources($user); //角色现存的权限规则的资源
        $userResources = array();
        $denyResources = array();
        foreach ($user->getUserRules() as $userRule)
            if($userRule['permission'] === 'allow')
                $userResources[] = $userRule['resource_id'];
            else
                $denyResources[] = $userRule['resource_id'];

        $roleResources = array();
        foreach ($user->getRoleRules() as $roleRule)
            $roleResources[] = $roleRule['resource_id'];

        $postedResources = $rule->getResources(); //修改后的资源，即新的权限规则的资源
        //计算新的权限规则的资源是否为全部资源
        if ($postedResources && $postedResources !== array('all')) {
            if (!array_diff(array_keys(Mage::getModel('admin/roles')->getResourcesList()), array('all', 'admin'), $postedResources))
                $postedResources = array('all');
        }

        //新增的权限规则的资源，在新的权限规则的资源中存在，而不存在于现存的权限规则的资源中
        $insertResources = array_diff($postedResources, $allowResources);
        //删除的权限规则的资源，在现存的权限规则的资源中存在，而不存在于新的权限规则的资源中
        $deleteResources = array_diff($allowResources, $postedResources);

        $adapter = $this->_getWriteAdapter();
        $adapter->beginTransaction();
        $userRuleTable = $this->getTable('administrat/user_rule');
        try {
            if ($insertResources) {
                $row = array(
                    'privileges' => '', // not used yet
                    'assert_id' => 0,
                    'user_id' => $userId,
                    'permission' => 'allow'
                );
                $datas = array();
                foreach (array_diff($insertResources, $denyResources) as $insertResource)
                    $datas[] = array_merge($row, array('resource_id' => $insertResource));
                if($datas)
                    $adapter->insertMultiple($userRuleTable, $datas);
                
                $condition = array(
                    'user_id = ?' => (int) $userId,
                    'resource_id IN (?)' => array_intersect($insertResources, array_intersect($denyResources, $roleResources))
                );
                $adapter->delete($userRuleTable, $condition);
                
                $condition = array(
                    'user_id = ?' => (int) $userId,
                    'resource_id IN (?)' => array_intersect($insertResources, array_diff($denyResources, $roleResources))
                );
                $adapter->update($userRuleTable, array('permission' => 'allow'), $condition);
            }

            if ($deleteResources) {
                $row = array(
                    'privileges' => '', // not used yet
                    'assert_id' => 0,
                    'user_id' => $userId,
                    'permission' => 'deny'
                );
                $datas = array();
                foreach (array_intersect($deleteResources, array_diff($roleResources, $userResources)) as $insertResource)
                    $datas[] = array_merge($row, array('resource_id' => $insertResource));
                if($datas)
                    $adapter->insertMultiple($userRuleTable, $datas);
                
                $condition = array(
                    'user_id = ?' => (int) $userId,
                    'resource_id IN (?)' => array_diff($deleteResources, $roleResources)
                );
                $adapter->delete($userRuleTable, $condition);
                
                $condition = array(
                    'user_id = ?' => (int) $userId,
                    'resource_id IN (?)' => array_intersect($insertResources, array_intersect($roleResources, $userResources))
                );
                $adapter->update($userRuleTable, array('permission' => 'deny'), $condition);
            }

            $adapter->commit();
        } catch (Mage_Core_Exception $e) {
            $adapter->rollBack();
            throw $e;
        } catch (Exception $e) {
            $adapter->rollBack();
            Mage::logException($e);
        }
    }
}