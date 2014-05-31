<?php

/**
 * @category   SunshineBiz
 * @package    SunshineBiz_Cms
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Cms_Block_Adminhtml_Widget_Grid_Column_Renderer_Skins extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData($this->getColumn()->getIndex());
        if (!$data) {
            return '';
        }

        if ($data === 'All') {
            return Mage::helper('design')->__('All Skins (Images / CSS)');
        }

        $packageIds = array();
        $themeIds = array();
        $skinIds = array();
        foreach (explode(',', $data) as $skins) {
            $skin = explode('/', $skins);
            $count = count($skin);
            switch ($count) {
                case 1:
                    $packageIds[] = $skin[0];
                    break;
                case 2:
                    $packageIds[] = $skin[0];
                    $themeIds[] = $skins;
                    break;
                default:
                    $packageIds[] = $skin[0];
                    $themeIds[] = $skin[0] . '/' . $skin[1];
                    $skinIds[] = $skins;
                    break;
            }
        }

        $data = Mage::getSingleton('design/skin')->getSkinsStructure(false, $skinIds, $themeIds, $packageIds);
        $out = '';
        foreach ($data as $package) {
            $out .= $package['label'] . '<br/>';
            foreach (isset($package['children']) ? $package['children'] : array() as $theme) {
                $out .= str_repeat('&nbsp;', 3) . $theme['label'] . '<br/>';
                foreach (isset($theme['children']) ? $theme['children'] : array() as $skin) {
                    $out .= str_repeat('&nbsp;', 6) . $skin['label'] . '<br/>';
                }
            }
        }

        return $out;
    }

}
