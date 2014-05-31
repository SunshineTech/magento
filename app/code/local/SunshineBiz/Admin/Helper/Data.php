<?php

/**
 * SunshineBiz_Admin Data Helper
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Admin
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Admin_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getAllowResources(Mage_Admin_Model_User $user) {
        
        $allResources = array_keys(Mage::getModel('admin/roles')->getResourcesList());
        $allRules = $user->getAllRules();

        $allResource = null;
        $denyResources = array();
        $allowResources = array();
        foreach ($allRules as $allRule) {
            if ($allRule['resource_id'] === 'all') {
                if ($allRule['permission'] === 'allow') {
                    $allResource = 'all';
                }
                continue;
            }

            if ($allRule['permission'] === 'deny') {
                $denyResources[] = $allRule['resource_id'];
            } else {
                $allowResources[] = $allRule['resource_id'];
            }
        }

        if ($allResource) {
            $allowResources = array($allResource);
            if ($denyResources)
                $allowResources = array_diff($allResources, array('all', 'admin'), $denyResources);
        } elseif ($allowResources) {
            if (!array_diff($allResources, array('all', 'admin'), $allowResources))
                $allowResources = array('all');
        }
        
        return $allowResources;
    }
}