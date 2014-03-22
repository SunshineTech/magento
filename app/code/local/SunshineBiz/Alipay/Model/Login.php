<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Alipay
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Alipay_Model_Login extends SunshineBiz_SocialConnect_Model_Method_Abstract {
    
    protected $_code = 'alipay';
    protected $_buttonBlockType = 'alipay/login_button';
    protected $_accountUrl = 'alipay/account/login';
    
    const REDIRECT_URI_ROUTE = 'alipay/login/connect';
    
    protected $clientId = null;
    protected $clientSecret = null;

    public function __construct() {

        $this->clientId = $this->getConfigData('client_id');
        $this->clientSecret = $this->getConfigData('client_secret');
    }
    
    public function createAuthUrl() {
        return Mage::getUrl('alipay/login/redirect');
    }
    
     /**
     *  Return Login Form Fields for request to Alipay
     *
     *  @return	  array Array of hidden form fields
     */
    public function getLoginFormFields() {
        
        $params = array(
            'service'           => 'alipay.auth.authorize',
            'partner'           => $this->clientId,
            '_input_charset'    => 'utf-8',
            'sign_type'         => 'MD5',
            'return_url'        => Mage::getModel('core/url')->sessionUrlVar(Mage::getUrl(self::REDIRECT_URI_ROUTE, array('state' => Mage::getSingleton('core/session')->getAlipayCsrf()))),
            'target_service'    => 'user.auth.quick.login'
        );
        
        if ($this->getConfigData('antiphishing_ip_check')) {
            $params['exter_invoke_ip'] = $this->getConfigData('client_ip');
        }            

        if ($this->getConfigData('antiphishing_timestamp_verify')) {
            $params['anti_phishing_key'] = Mage::helper('alipay')->queryTimestamp($this->clientId);
        }            
        
        return Mage::helper('alipay')->buildRequestParams($params, $this->clientSecret);
    }
    
    public function getClientSecret() {
        return $this->clientSecret;
    }
    
    public function getClientId() {
        return $this->clientId;
    }

    public function getDescription() {
        return Mage::helper('alipay')->__('You can login using your <strong>Alipay</strong> credentials.');
    }

    public function getTitle() {
        return Mage::helper('alipay')->__('Alipay Quick Login');
    }
}
