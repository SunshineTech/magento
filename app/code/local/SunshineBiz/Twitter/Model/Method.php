<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Twitter
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Twitter_Model_Method extends SunshineBiz_SocialConnect_Model_Method_Abstract {

    protected $_code = 'twitter';
    protected $_buttonBlockType = 'twitter/button';
    protected $_accountUrl = 'twitter/account/index';

    const REDIRECT_URI_ROUTE = 'twitter/connect/connect';
    const REQUEST_TOKEN_URI_ROUTE = 'twitter/connect/request';
    const OAUTH_URI = 'https://api.twitter.com/oauth';
    const OAUTH2_SERVICE_URI = 'https://api.twitter.com/1.1';

    protected $clientId = null;
    protected $clientSecret = null;
    protected $redirectUri = null;
    protected $client = null;
    protected $token = null;

    public function __construct() {

        $this->clientId = $this->getConfigData('client_id');
        $this->clientSecret = $this->getConfigData('client_secret');
        $this->redirectUri = Mage::getModel('core/url')->sessionUrlVar(Mage::getUrl(self::REDIRECT_URI_ROUTE));

        $this->client = new Zend_Oauth_Consumer(array(
            'callbackUrl' => $this->redirectUri,
            'siteUrl' => self::OAUTH_URI,
            'authorizeUrl' => self::OAUTH_URI . '/authenticate',
            'consumerKey' => $this->clientId,
            'consumerSecret' => $this->clientSecret
        ));
    }

    public function api($endpoint, $method = 'GET', $params = array()) {
        if (empty($this->token)) {
            throw new Exception(Mage::helper('socialconnect')->__('Unable to proceed without an access token.'));
        }

        return $this->_httpRequest(self::OAUTH2_SERVICE_URI . $endpoint, strtoupper($method), $params);
    }

    public function createAuthUrl() {
        return Mage::getUrl(self::REQUEST_TOKEN_URI_ROUTE);
    }

    public function getAccessToken() {
        if (empty($this->token)) {
            $this->fetchAccessToken();
        }

        return serialize($this->token);
    }

    public function getClient() {
        return $this->client;
    }

    public function fetchRequestToken() {
        if (!($requestToken = $this->client->getRequestToken())) {
            throw new Exeption(Mage::helper('socialconnect')->__('Unable to retrieve request token.'));
        }

        Mage::getSingleton('core/session')->setTwitterRequestToken(serialize($requestToken));

        $this->client->redirect();
    }

    protected function fetchAccessToken() {
        if (!($params = Mage::app()->getFrontController()->getRequest()->getParams()) ||
                !($requestToken = Mage::getSingleton('core/session')->getTwitterRequestToken())) {
            throw new Exception(Mage::helper('socialconnect')->__('Unable to retrieve access code.'));
        }

        if (!($token = $this->client->getAccessToken($params, unserialize($requestToken)))) {
            throw new Exeption(Mage::helper('socialconnect')->__('Unable to retrieve access token.'));
        }

        Mage::getSingleton('core/session')->unsTwitterRequestToken();

        return $this->token = $token;
    }

    public function setAccessToken($token) {
        $this->token = unserialize($token);
    }

    protected function _httpRequest($url, $method = 'GET', $params = array()) {

        $client = $this->token->getHttpClient(
                array(
                    'callbackUrl' => $this->redirectUri,
                    'siteUrl' => self::OAUTH_URI,
                    'consumerKey' => $this->clientId,
                    'consumerSecret' => $this->clientSecret
                )
        );

        $client->setUri($url);

        switch ($method) {
            case 'GET':
                $client->setMethod(Zend_Http_Client::GET);
                $client->setParameterGet($params);
                break;
            case 'POST':
                $client->setMethod(Zend_Http_Client::POST);
                $client->setParameterPost($params);
                break;
            case 'DELETE':
                $client->setMethod(Zend_Http_Client::DELETE);
                break;
            default:
                throw new Exception(Mage::helper('socialconnect')->__('Required HTTP method is not supported.'));
        }

        $response = $client->request();
        $decoded_response = json_decode($response->getBody());

        if ($response->isError()) {
            $status = $response->getStatus();
            if (($status == 400 || $status == 401 || $status == 429)) {
                if (isset($decoded_response->error->message)) {
                    $message = iconv("", "UTF-8", $decoded_response->error->message);
                } else {
                    $message = Mage::helper('socialconnect')->__('Unspecified OAuth error occurred.');
                }

                throw new SunshineBiz_Twitter_OAuthException($message);
            } else {
                $message = sprintf(Mage::helper('socialconnect')->__('HTTP error %d occurred while issuing request.'), $status);

                throw new Exception($message);
            }
        }

        return $decoded_response;
    }

    public function getDescription() {
        return Mage::helper('twitter')->__('You can login using your <strong>Twitter</strong> credentials.');
    }

    public function getTitle() {
        return Mage::helper('twitter')->__('Twitter Connect');
    }

}

class SunshineBiz_Twitter_OAuthException extends Exception {
    
}
