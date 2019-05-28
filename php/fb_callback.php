<?php
if (!session_id()) {
  session_start();
}
require_once('socialConfig.php');

try
{
  $accessToken = $helper->getAccessToken();
  // $token will be null if the user denied the request
if (! $accessToken) {
  // User denied the request
} else {
  // set valid $token as default access token
  $FB->setDefaultAccessToken($accessToken);
}
}
catch(\Facebook\Exceptions\FacebookResponseException $e)
{
  echo "Response Exception: " . $e->getMessage();
  exit();
}
catch(\Facebook\Exceptions\FacebookSDKException $e)
{
  echo "SDK Exception: " . $e->getMessage();
  exit();
}

if(!$accessToken)
{
  header('Loacation: login.php');
  exit();
}

$oAuth2Client = $FB->getOAuth2Client();
if(!$accessToken->isLongLived())
{
  $accessToken = $oAuth2Client->getLongLivedAccessToken();
}
  $response = $FB->get("/me?fields=id, first_name, last_name, email, picture.type(large)");
  $userData = $response->getGraphNode()->asArray();
  $_SESSION['userData']=$userData;
  $_SESSION['accessToken']=(string) $accessToken;
  header('Location: home.php');
  exit();

?>