<?php

/**
 * SunshineBiz_Administrat acl resource
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Administrat
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Administrat_Model_Resource_Acl extends Mage_Admin_Model_Resource_Acl {
    
    public function loadAcl() {
        
        $acl = parent::loadAcl();
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
                ->from(array('r' => $this->getTable('administrat/user_rule')), array('role_id' => 'user_id', 'role_type' => new Zend_Db_Expr('\'' . Mage_Admin_Model_Acl::ROLE_TYPE_USER . '\''), 'resource_id', 'privileges', 'assert_id', 'permission'))
                ->joinLeft(array('a' => $this->getTable('admin/assert')), 'a.assert_id = r.assert_id', array('assert_type', 'assert_data'));
        $this->loadRules($acl, $adapter->fetchAll($select));

        return $acl;
    }
}
