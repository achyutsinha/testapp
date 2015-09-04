<?php
ini_set('max_execution_time', 3000);



$client = new SoapClient('http://magento-7350-19577-45479.cloudwaysapps.com/api/v2_soap/?wsdl');

// If somestuff requires api authentification,
// then get a session token
$session = $client->login('testapp', 'mytestapp');

$filter = array('filter' => array(array('key' => 'base_currency_code', 'value' => 'USD')));

//$result = $client->salesOrderList($session, $filter);

$result = $client->salesOrderList($session);
// If you don't need the session anymore
var_dump($result);
//echo 'No of Orders : '.count($result);
echo '</br>';
foreach ($result as $value)
    echo 'Increment ID : '.$value->increment_id.'</br>';
//$a=json_decode ($result);
//print_r($a);
/*

ini_set('max_execution_time', 3000);


error_reporting(E_ALL);
ini_set('display_errors', TRUE);
$api_url = "'http://magento-7350-19577-45479.cloudwaysapps.com/api/v2_soap/?wsdl=1"; //For Version 2
//$api_url = "http://yourhost.com/api/soap/?wsdl";  //For Version 1
$username = 'testapp';
$password = 'mytestapp';
$client = new SoapClient($api_url,array('cache_wsdl' => WSDL_CACHE_NONE));  //Will cnot cache the WSDL
//retreive session id from login
$session = $client->login($username, $password);
$websiteId='za';   //for me its za its just id that you have created for the websitel.

//echo "<pre>";
//print_r($client->__getFunctions ());

$resultActive = $client->productsapiActiveList($session,$websiteId); //Sample call in version 2
 
$resultInacive = $client->productsapiInactiveList($session,$websiteId);  //Sample call in version 2

echo "<pre>";print_r($resultActive);

echo "<pre>";print_r($resultInacive);
*/

?>