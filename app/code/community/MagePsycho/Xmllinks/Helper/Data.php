<?php

/**
 * @category   MagePsycho
 * @package    MagePsycho_Xmllinks
 * @author     info@magepsycho.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MagePsycho_Xmllinks_Helper_Data extends Mage_Core_Helper_Abstract {
    
    public function getUrl($route, $params = array())
    {
        return $this->_getUrl($route, $params);
    }
}