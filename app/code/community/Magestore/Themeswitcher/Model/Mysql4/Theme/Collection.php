<?php

class Magestore_Themeswitcher_Model_Mysql4_Theme_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('themeswitcher/theme');
    }

    public function addStoresFilter($store, $withAdmin = true) {
        if (!$this->getFlag('store_filter_added')) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            $this->getSelect()->where('(find_in_set(0,stores)) OR (find_in_set(' . $store . ',stores))');

            $this->setFlag('store_filter_added', true);
        }

        return $this;
    }

}