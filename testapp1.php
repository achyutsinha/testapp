<?php
/**
 * Copyright Magento 2012
 * Example of products list retrieve using Customer account via Magento
REST API. OAuth authorization is used
 */
$callbackUrl = "http://magento-7350-19577-45479.cloudwaysapps.com/oauth_customer.php";
$temporaryCredentialsRequestUrl =
"http://magento-7350-19577-45479.cloudwaysapps.com/oauth/initiate?oauth_callback=" .
urlencode($callbackUrl);
$adminAuthorizationUrl = 'http://magento-7350-19577-45479.cloudwaysapps.com/oauth/authorize';
$accessTokenRequestUrl = 'http://magento-7350-19577-45479.cloudwaysapps.com/oauth/token';
$apiUrl = 'http://magento-7350-19577-45479.cloudwaysapps.com/api/rest';
$consumerKey = 'eedcf9497ab5d8ac3d0bb36a9a5ec2ff';
$consumerSecret = '5dfa269c5eea1403a309aac0980b7565';

session_start();
if (!isset($_GET['oauth_token']) && isset($_SESSION['state']) &&
$_SESSION['state'] == 1) {
   $_SESSION['state'] = 0;
}
try {
   $authType = ($_SESSION['state'] == 2) ? OAUTH_AUTH_TYPE_AUTHORIZATION
: OAUTH_AUTH_TYPE_URI;
   $oauthClient = new OAuth($consumerKey, $consumerSecret,
OAUTH_SIG_METHOD_HMACSHA1, $authType);
   $oauthClient->enableDebug();

   if (!isset($_GET['oauth_token']) && !$_SESSION['state']) {
       $requestToken =
$oauthClient->getRequestToken($temporaryCredentialsRequestUrl);
       $_SESSION['secret'] = $requestToken['oauth_token_secret'];
       $_SESSION['state'] = 1;
       header('Location: ' . $adminAuthorizationUrl . '?oauth_token=' .
$requestToken['oauth_token']);
       exit;
   } else if ($_SESSION['state'] == 1) {
       $oauthClient->setToken($_GET['oauth_token'], $_SESSION['secret']);
       $accessToken =
$oauthClient->getAccessToken($accessTokenRequestUrl);
       $_SESSION['state'] = 2;
       $_SESSION['token'] = $accessToken['oauth_token'];
       $_SESSION['secret'] = $accessToken['oauth_token_secret'];
       header('Location: ' . $callbackUrl);
       exit;
   } else {
       $oauthClient->setToken($_SESSION['token'], $_SESSION['secret']);
       $resourceUrl = "$apiUrl/products";
       $oauthClient->fetch($resourceUrl);
       $productsList = json_decode($oauthClient->getLastResponse());
       print_r($productsList);
   }
} catch (OAuthException $e) {
   print_r($e);
}



/**
 * Example of products list retrieve using admin account via Magento REST
API. oAuth authorization is used
 */
$callbackUrl = "http://magento-7350-19577-45479.cloudwaysapps.com/oauth_admin.php";
$temporaryCredentialsRequestUrl =
"http://magento-7350-19577-45479.cloudwaysapps.com/oauth/initiate?oauth_callback=" .
urlencode($callbackUrl);
$adminAuthorizationUrl = 'http://magento-7350-19577-45479.cloudwaysapps.com/admin/oAuth_authorize';
$accessTokenRequestUrl = 'http://magento-7350-19577-45479.cloudwaysapps.com/oauth/token';
$apiUrl = 'http://magento-7350-19577-45479.cloudwaysapps.com/api/rest';
$consumerKey = 'eedcf9497ab5d8ac3d0bb36a9a5ec2ff';
$consumerSecret = '5dfa269c5eea1403a309aac0980b7565';

session_start();
if (!isset($_GET['oauth_token']) && isset($_SESSION['state']) &&
$_SESSION['state'] == 1) {
   $_SESSION['state'] = 0;
}
try {
   $authType = ($_SESSION['state'] == 2) ? OAUTH_AUTH_TYPE_AUTHORIZATION
: OAUTH_AUTH_TYPE_URI;
   $oauthClient = new OAuth($consumerKey, $consumerSecret,
OAUTH_SIG_METHOD_HMACSHA1, $authType);
   $oauthClient->enableDebug();

   if (!isset($_GET['oauth_token']) && !$_SESSION['state']) {
       $requestToken =
$oauthClient->getRequestToken($temporaryCredentialsRequestUrl);
       $_SESSION['secret'] = $requestToken['oauth_token_secret'];
       $_SESSION['state'] = 1;
       header('Location: ' . $adminAuthorizationUrl . '?oauth_token=' .
$requestToken['oauth_token']);
       exit;
   } else if ($_SESSION['state'] == 1) {
       $oauthClient->setToken($_GET['oauth_token'], $_SESSION['secret']);
       $accessToken =
$oauthClient->getAccessToken($accessTokenRequestUrl);
       $_SESSION['state'] = 2;
       $_SESSION['token'] = $accessToken['oauth_token'];
       $_SESSION['secret'] = $accessToken['oauth_token_secret'];
       header('Location: ' . $callbackUrl);
       exit;
   } else {
       $oauthClient->setToken($_SESSION['token'], $_SESSION['secret']);
       $resourceUrl = "$apiUrl/products";
       $oauthClient->fetch($resourceUrl);
       $productsList = json_decode($oauthClient->getLastResponse());
       print_r($productsList);
   }
} catch (OAuthException $e) {
   print_r($e);
}