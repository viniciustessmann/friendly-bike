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


$ipAddr = getUserIP();
$geoIP  = json_decode(file_get_contents("http://freegeoip.net/json/$ipAddr"), true);

$post = ['client_id'=> $appId, "redirect_uri" => $redirectURL, "client_secret" => $appSecret, 'code' => $_GET['code']];
$arr_result = getFBResponse($post);

$access_token = $arr_result->access_token;

$response = $fb->get('/me?fields=id,first_name,last_name,picture,email', $access_token);
$user = $response->getGraphUser();

$facebook_data_array = array(
    'base_url'     => 'https://graph.facebook.com/v2.10/',
    'node'         => 'search?',
    'query_param'  => 'q=',
    'root_node'    => 'type=place',
    'coords'       => 'center='.$geoIP['latitude'].','.$geoIP['longitude'],
    'fields'       => 'fields=name,talking_about_count',
    'access_token' => 'access_token=' . $access_token
  );

var_dump($user['id']);
var_dump($user['first_name']);
var_dump(get_facebook_data($facebook_data_array));
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


function get_facebook_data( $args ) {

    /* Concatenate the array values. */
    $url = $args['base_url'] . $args['node'] . $args['query_param'] . '&' . $args['root_node'] . '&' . $args['coords'] . '&' . $args['fields'] . '&' . $args['access_token'];
  
    /* Initiate request. Store the results in the $response varialbe */
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    $response = curl_exec( $ch );
    curl_close( $ch );
  
    /* Return the values in the $response variable. */
    return json_decode($response);
  
  }


  function getUserIP()
  {
      $client  = @$_SERVER['HTTP_CLIENT_IP'];
      $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
      $remote  = $_SERVER['REMOTE_ADDR'];

      if(filter_var($client, FILTER_VALIDATE_IP))
      {
          $ip = $client;
      }
      elseif(filter_var($forward, FILTER_VALIDATE_IP))
      {
          $ip = $forward;
      }
      else
      {
          $ip = $remote;
      }

      return $ip;
  }

?>