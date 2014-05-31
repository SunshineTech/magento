<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Block_Adminhtml_Widget_Grid_Column_Renderer_Media extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {

        if ($url = $row->getData($this->getColumn()->getIndex())) {
            if (!preg_match("/^http\:\/\/|https\:\/\//", $url)) {
                $url = Mage::getBaseUrl('media') . $url;
            }

            return '<img src="' . $url . '" height="22" width="22" class="small-image-preview v-middle"/>';
        }

        return null;
    }

}
