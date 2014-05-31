<?php

/**
 * Design Skin method
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Design
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Design_Model_Skin {

    public function getSkinValuesForForm($empty = false, $all = false) {
        $options = array();
        if ($empty) {
            $options[] = array(
                'label' => Mage::helper('core')->__('-- Please Select --'),
                'value' => ''
            );
        }

        if ($all) {
            $options[] = array(
                'label' => Mage::helper('design')->__('All Skins (Images / CSS)'),
                'value' => 'All'
            );
        }

        $nonEscapableNbspChar = html_entity_decode('&#160;', ENT_NOQUOTES, 'UTF-8');
        $design = Mage::getModel('core/design_package')->getThemeList();
        foreach ($design as $package => $themes) {
            if ($all) {
                $options[] = array(
                    'label' => $package,
                    'value' => $package
                );
            } else {
                $options[] = array(
                    'label' => $package,
                    'value' => array()
                );
            }

            foreach ($themes as $theme) {
                if ($all) {
                    $options[] = array(
                        'label' => str_repeat($nonEscapableNbspChar, 4) . $theme,
                        'value' => $package . '/' . $theme
                    );
                } else {
                    $options[] = array(
                        'label' => str_repeat($nonEscapableNbspChar, 4) . $theme,
                        'value' => array()
                    );
                }

                $skins = Mage::getModel('core/design_package')->getSkinList($package, $theme);
                foreach ($skins as $skin) {
                    $options[] = array(
                        'label' => str_repeat($nonEscapableNbspChar, 8) . $skin,
                        'value' => $package . '/' . $theme . '/' . $skin
                    );
                }
            }
        }

        return $options;
    }

    public function getSkinsStructure($isAll = false, $skinIds = array(), $themeIds = array(), $packageIds = array()) {
        $out = array();
        if ($isAll) {
            $out[] = array(
                'value' => 'All',
                'label' => Mage::helper('design')->__('All Skins (Images / CSS)')
            );
        }

        $design = Mage::getModel('core/design_package')->getThemeList();
        foreach ($design as $package => $themes) {
            if ($packageIds && !in_array($package, $packageIds)) {
                continue;
            }

            $out[$package] = array(
                'value' => $package,
                'label' => $package
            );

            if (!$isAll && !$themeIds) {
                continue;
            }

            foreach ($themes as $theme) {
                if ($themeIds && !in_array($package . '/' . $theme, $themeIds)) {
                    continue;
                }

                $out[$package]['children'][$theme] = array(
                    'value' => $package . '/' . $theme,
                    'label' => $theme
                );

                if (!$isAll && !$skinIds) {
                    continue;
                }

                $skins = Mage::getModel('core/design_package')->getSkinList($package, $theme);
                foreach ($skins as $skin) {
                    if ($skinIds && !in_array($package . '/' . $theme . '/' . $skin, $skinIds)) {
                        continue;
                    }

                    $out[$package]['children'][$theme]['children'][$skin] = array(
                        'value' => $package . '/' . $theme . '/' . $skin,
                        'label' => $skin
                    );
                }
            }
        }

        return $out;
    }

}
