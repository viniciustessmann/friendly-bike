<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php'; // change path as needed

// Include required libraries
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

/*
 * Configuration and setup Facebook SDK
 */
$appId         = '283120945560809'; //Facebook App ID
$appSecret     = '656d9a15b0fef0d20134e4ad05a183d5'; //Facebook App Secret
$redirectURL   = 'http://friendlybike.viniciustessmann.web6213.kinghost.net/form.php'; //Callback URL
// $fbPermissions = array('email');  //Optional permissions

$fb = new Facebook(array(
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v2.2',
));

https://www.facebook.com/v2.2/dialog/oauth?client_id=283120945560809&state=1986cc18486f2acf01a591c7db82ae67&response_type=code&sdk=php-sdk-5.6.1&redirect_uri=http%3A%2F%2Ffriendlybike.viniciustessmann.web6213.kinghost.net%2Fform.php&scope=

$post = ['client_id'=> $appId, "redirect_uri" => $redirectURL, "client_secret" => $appSecret, 'code' => $_GET['code']];
$arr_result = getFBResponse($post);

$access_token = $arr_result->access_token;

$response = $fb->get('/me?fields=id,first_name,last_name,picture,email', $access_token);
$user = $response->getGraphUser();

echo '<pre>';
var_dump($user['id']);
var_dump($user['first_name']);
die;

// $user = $fb->api('/me');

// var_dump($user);


function getFBResponse($arr_post = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'https://graph.facebook.com/v2.10/oauth/access_token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arr_post));
    $response = curl_exec($ch);
    return json_decode($response);
}

?>