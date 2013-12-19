<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_SocialConnect
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_SocialConnect_Model_Google_Method extends SunshineBiz_SocialConnect_Model_Method_Abstract {

    protected $_code = 'google';
    protected $_buttonBlockType = 'socialconnect/google_button';
    protected $_accountUrl = 'socialconnect/account/google';

    const REDIRECT_URI_ROUTE = 'socialconnect/google/connect';
    const OAUTH2_AUTH_URI = 'https://accounts.google.com/o/oauth2/auth';
    const OAUTH2_TOKEN_URI = 'https://accounts.google.com/o/oauth2/token';
    const OAUTH2_SERVICE_URI = 'https://www.googleapis.com/oauth2/v2';
    const OAUTH2_REVOKE_URI = 'https://accounts.google.com/o/oauth2/revoke';

    protected $clientId = null;
    protected $clientSecret = null;
    protected $redirectUri = null;
    protected $scope = array(
        'https://www.googleapis.com/auth/userinfo.profile',
        'https://www.googleapis.com/auth/userinfo.email',
    );
    protected $access = 'offline';
    protected $prompt = 'auto';
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

        if (!empty($params['access'])) {
            $this->access = $params['access'];
        }

        if (!empty($params['prompt'])) {
            $this->prompt = $params['prompt'];
        }
    }

    public function createAuthUrl() {
        $url =
                self::OAUTH2_AUTH_URI . '?' .
                http_build_query(
                        array(
                            'response_type' => 'code',
                            'redirect_uri' => $this->redirectUri,
                            'client_id' => $this->clientId,
                            'scope' => implode(' ', $this->scope),
                            'state' => $this->state,
                            'access_type' => $this->access,
                            'approval_prompt' => $this->prompt
                        )
        );

        return $url;
    }

    public function api($endpoint, $method = 'GET', $params = array()) {

        if (empty($this->token)) {
            $this->fetchAccessToken();
        } else if ($this->isAccessTokenExpired()) {
            $this->refreshAccessToken();
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

        $response->created = time();

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
                break;
            default:
                throw new Exception(Mage::helper('socialconnect')->__('Required HTTP method is not supported.'));
        }

        $response = $client->request($method);

        $decoded_response = json_decode($response->getBody());

        if ($response->isError()) {
            $status = $response->getStatus();
            if (($status == 400 || $status == 401)) {
                if (isset($decoded_response->error->message)) {
                    $message = $decoded_response->error->message;
                } else {
                    $message = Mage::helper('socialconnect')->__('Unspecified OAuth error occurred.');
                }
                throw new SunshineBiz_SocialConnect_GoogleOAuthException($message);
            } else {
                $message = sprintf(
                        Mage::helper('socialconnect')->__('HTTP error %d occurred while issuing request.'), $status
                );

                throw new Exception($message);
            }
        }

        return $decoded_response;
    }

    protected function isAccessTokenExpired() {
        // If the token is set to expire in the next 30 seconds.
        $expired = ($this->token->created + ($this->token->expires_in - 30)) < time();

        return $expired;
    }

    protected function refreshAccessToken() {
        if (empty($this->token->refresh_token)) {
            throw new Exception(Mage::helper('socialconnect')->__('No refresh token, unable to refresh access token.'));
        }

        $response = $this->_httpRequest(
                self::OAUTH2_TOKEN_URI, 'POST', array(
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->token->refresh_token,
            'grant_type' => 'refresh_token'
                )
        );

        $this->token->access_token = $response->access_token;
        $this->token->expires_in = $response->expires_in;
        $this->token->created = time();
    }

    public function getAccessToken() {
        if (empty($this->token)) {
            $this->fetchAccessToken();
        } else if ($this->isAccessTokenExpired()) {
            $this->refreshAccessToken();
        }

        return json_encode($this->token);
    }

    public function setAccessToken($token) {
        $this->token = json_decode($token);
    }

    public function revokeToken() {
        if (empty($this->token)) {
            throw new Exception(Mage::helper('socialconnect')->__('No access token available.'));
        }

        if (empty($this->token->refresh_token)) {
            throw new Exception(Mage::helper('socialconnect')->__('No refresh token, nothing to revoke.'));
        }

        $this->_httpRequest(self::OAUTH2_REVOKE_URI, 'POST', array('token' => $this->token->refresh_token));
    }

    public function getDescription() {
        return Mage::helper('socialconnect')->__('You can login using your <strong>Google</strong> credentials.');
    }

    public function getTitle() {
        return Mage::helper('socialconnect')->__('Google Connect');
    }

}

class SunshineBiz_SocialConnect_GoogleOAuthException extends Exception {
    
}