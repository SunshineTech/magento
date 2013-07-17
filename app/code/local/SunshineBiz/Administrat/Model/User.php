<?php

/**
 * SunshineBiz_Administrat user model
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Administrat
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Administrat_Model_User extends Mage_Admin_Model_User {

    public function getAllRules() {
        return $this->_getResource()->getAllRules($this);
    }

    public function getRoleRules() {
        return $this->_getResource()->getRoleRules($this);
    }

    public function getUserRules() {
        return $this->_getResource()->getUserRules($this);
    }
}