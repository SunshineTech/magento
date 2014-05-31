<?php

/**
 * Design Package method
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Design
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Design_Model_Package extends Mage_Core_Model_Design_Package {

    public function getSkinList($package = null, $theme = null) {
        $result = array();
        if (!is_null($package)) {
            $directory = Mage::getBaseDir('skin') . DS . 'frontend' . DS . $package;
            $skins = $this->_listDirectories($directory);
            if ($theme) {
                foreach ($skins as $skin) {
                    if (strpos($skin, $theme) === 0) {
                        $result[] = $skin;
                    }
                }
            } else {
                $result = $skins;
            }
        }

        return $result;
    }

}
