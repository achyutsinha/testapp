<?php

error_reporting(-1);
ini_set('display_errors', 'On');

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->hostname = 'http://magento-7350-19577-45479.cloudwaysapps.com';
        $consumerKey = 'eedcf9497ab5d8ac3d0bb36a9a5ec2ff';
        $consumerSecret = '5dfa269c5eea1403a309aac0980b7565';
        $callbackUrl = 'callback-url';
        $this->config = array(
            'callbackUrl' => $callbackUrl,
            'requestTokenUrl' => $this->hostname . '/oauth/initiate',
            'siteUrl' => $this->hostname . '/oauth',
            'consumerKey' => $consumerKey,
            'consumerSecret' => $consumerSecret,
            'authorizeUrl' => $this->hostname . '/admin/oauth_authorize',
            // 'authorizeUrl' => $this->hostname . '/oauth/authorize',
            'accessTokenUrl' => $this->hostname . '/oauth/token'
        );
    }

    public function indexAction()
    {
        $accesssession = new Zend_Session_Namespace('AccessToken');
        if (isset($accesssession->accessToken)) {
            $token = unserialize($accesssession->accessToken);
            // $client = $token->getHttpClient($this->config);
            $client = new Zend_Http_Client();
            $adapter = new Zend_Http_Client_Adapter_Curl();
            $client->setAdapter($adapter);
            $adapter->setConfig(array(
                'adapter'   => 'Zend_Http_Client_Adapter_Curl',
                'curloptions' => array(CURLOPT_FOLLOWLOCATION => true),
            ));
            $client->setUri($this->hostname . '/api/rest/products');
            $client->setParameterGet('oauth_token', $token->getToken());
            $client->setParameterGet('oauth_token_secret', $token->getTokenSecret());
            $response = $client->request('GET');
            $products = Zend_Json::decode($response->getBody());
        } else {
            $consumer = new Zend_Oauth_Consumer($this->config);
            $token = $consumer->getRequestToken();
            $requestsession = new Zend_Session_Namespace('RequestToken');
            $requestsession->requestToken = serialize($token);
            $consumer->redirect();
        }
        $this->view->products = $products;
    }

    public function callbackAction()
    {
        $requestsession = new Zend_Session_Namespace('RequestToken');
        if (!empty($_GET) && isset($requestsession->requestToken)) {
            $accesssession = new Zend_Session_Namespace('AccessToken');
            $consumer = new Zend_Oauth_Consumer($this->config);
            $token = $consumer->getAccessToken(
                $_GET,
                unserialize($requestsession->requestToken)
            );
            $accesssession->accessToken = serialize($token);
            // Now that we have an Access Token, we can discard the Request Token
            unset($requestsession->requestToken);
            // $this->_redirect();
            $this->_forward('index', 'index', 'default');
        } else {
            // Mistaken request? Some malfeasant trying something?
            throw new Exception('Invalid callback request. Oops. Sorry.');
        }
    }

    public function callbackrejectedAction()
    {
        // rejected
    }
}
?>