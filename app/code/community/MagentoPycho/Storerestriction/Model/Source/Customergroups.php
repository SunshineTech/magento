<?php
/** 
 * GuruWebSoft (www.guruwebsoft.com)
 * 
 * @author     MagentoPycho <rajen_k_bhtt@hotmail.com>
 * @category   Catalog/Customer
 * @package    MagentoPycho_Storerestriction
 */
class MagentoPycho_Storerestriction_Model_Source_Customergroups{
    
    public function toOptionArray()
    {
        $customer_groups = Mage::getResourceModel('customer/group_collection')
                /* ->setRealGroupsFilter() */
                ->loadData()
                ->toOptionArray();        
        return $customer_groups;
    }
}