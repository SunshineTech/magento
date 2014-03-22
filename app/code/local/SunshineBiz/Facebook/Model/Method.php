<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Facebook
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2014 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Facebook_Model_Method extends SunshineBiz_SocialConnect_Model_Method_Abstract {

    protected $_code = 'facebook';
    protected $_buttonBlockType = 'facebook/button';
    protected $_accountUrl = 'facebook/account/index';

    const REDIRECT_URI_ROUTE = 'facebook/connect/connect';
    const OAUTH2_AUTH_URI = 'https://graph.facebook.com/oauth/authorize';
    const OAUTH2_TOKEN_URI = 'https://graph.facebook.com/oauth/access_token';
    const OAUTH2_SERVICE_URI = 'https://graph.facebook.com';

    protected $clientId = null;
    protected $clientSecret = null;
    protected $redirectUri = null;
    protected $scope = array('email', 'user_birthday');
    protected $token = null;

    public function __construct($params = array()) {

        $this->clientId = $this->getConfigData('client_id');
        $this->clientSecret = $this->getConfigData('client_secret');
        $this->redirectUri = Mage::getModel('core/url')->sessionUrlVar(
                Mage::getUrl(self::REDIRECT_URI_ROUTE)
        );

        if (!empty($params['scope'])) {
            $this->scope = $params['scope'];
        }

        if (!empty($params['state'])) {
            $this->state = $params['state'];
        }
    }

    public function createAuthUrl() {
        $url =
                self::OAUTH2_AUTH_URI . '?' .
                http_build_query(
                        array(
                            'redirect_uri' => $this->redirectUri,
                            'client_id' => $this->getConfigData('client_id'),
                            'scope' => implode(' ', $this->scope),
                            'state' => $this->state
                        )
        );
        return $url;
    }

    public function api($endpoint, $method = 'GET', $params = array()) {
        if (empty($this->token)) {
            $this->fetchAccessToken();
        }

        $url = self::OAUTH2_SERVICE_URI . $endpoint;

        $method = strtoupper($method);

        $params = array_merge(array('access_token' => $this->token->access_token), $params);

        $response = $this->_httpRequest($url, $method, $params);

        return $response;
    }

    protected function fetchAccessToken() {
        if (empty($_REQUEST['code'])) {
            throw new Exception(Mage::helper('socialconnect')->__('Unable to retrieve access code.'));
        }

        $response = $this->_httpRequest(self::OAUTH2_TOKEN_URI, 'POST', array(
            'code' => $_REQUEST['code'],
            'redirect_uri' => $this->redirectUri,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code'
                )
        );

        $this->token = $response;
    }

    protected function _httpRequest($url, $method = 'GET', $params = array()) {

        $client = new Zend_Http_Client($url, array('timeout' => 60));
        switch ($method) {
            case 'GET':
                $client->setParameterGet($params);
                break;
            case 'POST':
                $client->setParameterPost($params);
                break;
            case 'DELETE':
                $client->setParameterGet($params);
                break;
            default:
                throw new Exception(Mage::helper('socialconnect')->__('Required HTTP method is not supported.'));
        }

        try {
            $response = $client->request($method);
        } catch (Exception $e) {
            throw new Exception(iconv("", "UTF-8", $e->getMessage()));
        }
        $decoded_response = json_decode($response->getBody());

        if (empty($decoded_response)) {
            $parsed_response = array();
            parse_str($response->getBody(), $parsed_response);

            $decoded_response = json_decode(json_encode($parsed_response));
        }

        if ($response->isError()) {
            $status = $response->getStatus();
            if (($status == 400 || $status == 401)) {
                if (isset($decoded_response->error->message)) {
                    $message = iconv("", "UTF-8", $decoded_response->error->message);;
                } else {
                    $message = Mage::helper('socialconnect')->__('Unspecified OAuth error occurred.');
                }

                throw new SunshineBiz_Facebook_OAuthException($message);
            } else {
                $message = sprintf(Mage::helper('socialconnect')->__('HTTP error %d occurred while issuing request.'), $status);

                throw new Exception($message);
            }
        }

        return $decoded_response;
    }

    public function getAccessToken() {
        if (empty($this->token)) {
            $this->fetchAccessToken();
        }

        return json_encode($this->token);
    }

    public function setAccessToken($token) {
        $this->token = json_decode($token);
    }

    public function getDescription() {
        return Mage::helper('facebook')->__('You can login using your <strong>Facebook</strong> credentials.');
    }

    public function getTitle() {
        return Mage::helper('facebook')->__('Facebook Connect');
    }
}

class SunshineBiz_Facebook_OAuthException extends Exception {
    
}