<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Source_Carriers {

    public function toOptionArray() {
        $res = array();
        foreach (Mage::getStoreConfig('carriers') as $code => $methodConfig) {
            $prefix = 'carriers/' . $code . '/';
            if (!$model = Mage::getStoreConfig($prefix . 'model')) {
                continue;
            }
            
            if (!$methodInstance = Mage::getModel($model)) {
                continue;
            }
            
            $res[] = array(
                'value' => $methodInstance->getCarrierCode(),
                'label' => $methodInstance->getConfigData('title')
            );
        }
        
        return $res;
    }

}
