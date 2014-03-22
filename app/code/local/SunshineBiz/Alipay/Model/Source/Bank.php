<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Source_Bank {

    public function toOptionArray() {

        return array(
            array(
                'value' => 'ICBCBTB',
                'code' => 'ICBC',
                'label' => Mage::helper('alipay')->__('Industrial and Commercial Bank of China'),
                'group' => 'B2B'
            ),
            array(
                'value' => 'ABCBTB',
                'code' => 'ABC',
                'label' => Mage::helper('alipay')->__('Agricultural Bank of China'),
                'group' => 'B2B'
            ),
            array(
                'value' => 'CCBBTB',
                'code' => 'CCB',
                'label' => Mage::helper('alipay')->__('China Construction Bank'),
                'group' => 'B2B'
            ),
            array(
                'value' => 'SPDBB2B',
                'code' => 'SPDB',
                'label' => Mage::helper('alipay')->__('Shanghai Pudong Development Bank'),
                'group' => 'B2B'
            ),
            array(
                'value' => 'BOCBTB',
                'code' => 'BOC',
                'label' => Mage::helper('alipay')->__('Bank of China'),
                'group' => 'B2B'
            ),
            array(
                'value' => 'CMBBTB',
                'code' => 'BOC',
                'label' => Mage::helper('alipay')->__('China Merchants Bank'),
                'group' => 'B2B'
            ),
            array(
                'value' => 'BOCB2C',
                'code' => 'BOC',
                'label' => Mage::helper('alipay')->__('Bank of China'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'ICBCB2C',
                'code' => 'ICBC',
                'label' => Mage::helper('alipay')->__('Industrial and Commercial Bank of China'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'CMB',
                'code' => 'CMB',
                'label' => Mage::helper('alipay')->__('China Merchants Bank'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'CCB',
                'code' => 'CCB',
                'label' => Mage::helper('alipay')->__('China Construction Bank'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'ABC',
                'code' => 'ABC',
                'label' => Mage::helper('alipay')->__('Agricultural Bank of China'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'SPDB',
                'code' => 'SPDB',
                'label' => Mage::helper('alipay')->__('Shanghai Pudong Development Bank'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'CIB',
                'code' => 'CIB',
                'label' => Mage::helper('alipay')->__('Industrial Bank'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'GDB',
                'code' => 'GDB',
                'label' => Mage::helper('alipay')->__('Guangdong Development Bank'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'CMBC',
                'code' => 'CMBC',
                'label' => Mage::helper('alipay')->__('China Minsheng Bank'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'CITIC',
                'code' => 'CITIC',
                'label' => Mage::helper('alipay')->__('China CITIC Bank'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'HZCBB2C',
                'code' => 'HZCB',
                'label' => Mage::helper('alipay')->__('Bank of Hangzhou'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'CEBBANK',
                'code' => 'CEB',
                'label' => Mage::helper('alipay')->__('China Everbright Bank'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'SHBANK',
                'code' => 'SHBANK',
                'label' => Mage::helper('alipay')->__('Bank of Shanghai'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'NBBANK',
                'code' => 'NBBANK',
                'label' => Mage::helper('alipay')->__('Bank of Ningbo'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'SPABANK',
                'code' => 'SPABANK',
                'label' => Mage::helper('alipay')->__('PingAn Bank'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'BJRCB',
                'code' => 'BJRCB',
                'label' => Mage::helper('alipay')->__('Beijing Rural Commercial Bank'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'FDB',
                'code' => 'FDB',
                'label' => Mage::helper('alipay')->__('FuDian Bank'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'POSTGC',
                'code' => 'PSBC',
                'label' => Mage::helper('alipay')->__('Postal Savings Bank of China'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'abc1003',
                'code' => 'VISA',
                'label' => Mage::helper('alipay')->__('Visa'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'abc1004',
                'code' => 'MASTER',
                'label' => Mage::helper('alipay')->__('Master'),
                'group' => 'CREDIT'
            ),
            array(
                'value' => 'CMB-DEBIT',
                'code' => 'CMB',
                'label' => Mage::helper('alipay')->__('China Merchants Bank'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'CCB-DEBIT',
                'code' => 'CCB',
                'label' => Mage::helper('alipay')->__('China Construction Bank'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'ICBC-DEBIT',
                'code' => 'ICBC',
                'label' => Mage::helper('alipay')->__('Industrial and Commercial Bank of China'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'COMM-DEBIT',
                'code' => 'COMM',
                'label' => Mage::helper('alipay')->__('Bank of Communications'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'GDB-DEBIT',
                'code' => 'GDB',
                'label' => Mage::helper('alipay')->__('Guangdong Development Bank'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'BOC-DEBIT',
                'code' => 'BOC',
                'label' => Mage::helper('alipay')->__('Bank of China'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'CEB-DEBIT',
                'code' => 'CEB',
                'label' => Mage::helper('alipay')->__('China Everbright Bank'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'SPDB-DEBIT',
                'code' => 'SPDB',
                'label' => Mage::helper('alipay')->__('Shanghai Pudong Development Bank'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'PSBC-DEBIT',
                'code' => 'PSBC',
                'label' => Mage::helper('alipay')->__('Postal Savings Bank of China'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'BJBANK',
                'code' => 'BJBANK',
                'label' => Mage::helper('alipay')->__('Bank of Beijing'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'SHRCB',
                'code' => 'SHRCB',
                'label' => Mage::helper('alipay')->__('Shanghai Rural Commercial Bank'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'WZCBB2C-DEBIT',
                'code' => 'WZCB',
                'label' => Mage::helper('alipay')->__('Bank of Wenzhou'),
                'group' => 'DEBIT'
            ),
            array(
                'value' => 'COMM-DEBIT',
                'code' => 'COMM',
                'label' => Mage::helper('alipay')->__('Bank of Communications'),
                'group' => 'DEBIT'
            )
        );
    }

    public function getBank($value) {
        $_bank = NULL;
        
        if ($value) {
            foreach ($this->toOptionArray() as $bank) {
                if ($bank['value'] == $value) {
                    $_bank = $bank;
                    break;
                }
            }
        }

        return $_bank;
    }

    public function getBanks($group) {
        foreach ($this->toOptionArray() as $bank) {
            if ($bank['group'] == $group) {
                $banks[] = $bank;
            }
        }

        return $banks;
    }

}
