<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Administrat
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Administrat_Block_Adminhtml_Notification_Window extends Mage_Adminhtml_Block_Notification_Window {
    
    public function canShow() {
        return false;
    }
}
