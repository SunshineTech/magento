<?php

class WP_AddToCartFormKeyIssueFix_Model_Checkout_Observer
{
    function formKeyFix($observer)
    {
        $key = Mage::getSingleton('core/session')->getFormKey();
        Mage::app()->getRequest()->setParam('form_key', $key);
    }
}
