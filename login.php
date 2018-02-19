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
$redirectURL   = 'http://friendlybike.viniciustessmann.web6213.kinghost.net'; //Callback URL
// $fbPermissions = array('email');  //Optional permissions

$fb = new Facebook(array(
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v2.2',
));

   
$helper = $fb->getRedirectLoginHelper();
   
$permissions = []; // Optional information that your app can access, such as 'email'
$loginUrl = $helper->getLoginUrl('http://friendlybike.viniciustessmann.web6213.kinghost.net/form.php', $permissions);
   
?>

<a target="_blank" href="<? echo $loginUrl  ?>">Logar</a>
