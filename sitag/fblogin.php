<?php
session_start();
require_once( 'Facebook/autoload.php' );
ini_set('max_execution_time', 86400);
if (!session_id()) {
    session_start();
}

$fb = new Facebook\Facebook([
  'app_id' => '146665105881348',
  'app_secret' => 'f9372680d1c1bc99855e8b823579dda8',
  'default_graph_version' => 'v2.9',
]); 

$helper = $fb->getRedirectLoginHelper();

$loginUrl = $helper->getLoginUrl('http://127.0.0.1/sitag/callback2.php');

header("location: ".$loginUrl);

?>