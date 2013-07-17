<?php

/**
 * SunshineBiz_Administrat Acl model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Administrat
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Administrat_Model_Acl extends Mage_Admin_Model_Acl {

    public function isAllowed($role = null, $resource = null, $privilege = null) {

        // reset role & resource to null
        $this->_isAllowedRole = null;
        $this->_isAllowedResource = null;
        $this->_isAllowedPrivilege = null;

        if (null !== $role) {
            // keep track of originally called role
            $this->_isAllowedRole = $role;
            $role = $this->_getRoleRegistry()->get($role);
            if (!$this->_isAllowedRole instanceof Zend_Acl_Role_Interface) {
                $this->_isAllowedRole = $role;
            }
        }

        if (null !== $resource) {
            // keep track of originally called resource
            $this->_isAllowedResource = $resource;
            $resource = $this->get($resource);
            if (!$this->_isAllowedResource instanceof Zend_Acl_Resource_Interface) {
                $this->_isAllowedResource = $resource;
            }
        }

        if (null === $privilege) {
            // query on all privileges
            do {
                // depth-first search on $role if it is not 'allRoles' pseudo-parent
                if (null !== $role && null !== ($result = $this->_roleDFSAllPrivileges($role, $resource, $privilege))) {
                    return $result;
                }

                // look for rule on 'allRoles' psuedo-parent
                if (null !== ($rules = $this->_getRules($resource, null))) {
                    foreach ($rules['byPrivilegeId'] as $privilege => $rule) {
                        if (self::TYPE_DENY === ($ruleTypeOnePrivilege = $this->_getRuleType($resource, null, $privilege))) {
                            return false;
                        }
                    }
                    if (null !== ($ruleTypeAllPrivileges = $this->_getRuleType($resource, null, null))) {
                        return self::TYPE_ALLOW === $ruleTypeAllPrivileges;
                    }
                }

                //$resource = $this->_resources[$resource->getResourceId()]['parent'];
                $resource = null;
            } while (true); // loop terminates at 'allResources' pseudo-parent
        } else {
            $this->_isAllowedPrivilege = $privilege;
            // query on one privilege
            do {
                // depth-first search on $role if it is not 'allRoles' pseudo-parent
                if (null !== $role && null !== ($result = $this->_roleDFSOnePrivilege($role, $resource, $privilege))) {
                    return $result;
                }

                // look for rule on 'allRoles' pseudo-parent
                if (null !== ($ruleType = $this->_getRuleType($resource, null, $privilege))) {
                    return self::TYPE_ALLOW === $ruleType;
                } else if (null !== ($ruleTypeAllPrivileges = $this->_getRuleType($resource, null, null))) {
                    return self::TYPE_ALLOW === $ruleTypeAllPrivileges;
                }

                // try next Resource
                //$resource = $this->_resources[$resource->getResourceId()]['parent'];
                $resource = null;
            } while (true); // loop terminates at 'allResources' pseudo-parent
        }
    }

}