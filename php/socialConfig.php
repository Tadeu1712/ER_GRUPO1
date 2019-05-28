<?php
if (!session_id()) {
  session_start();
}
require_once('../vendor/autoload.php');

$FB = new Facebook\ Facebook([
  'app_id' => '339160973469433',
  'app_secret' => '3c958d0e986fa55c2642814af66a09d5',
  'default_graph_version' => 'v3.2',
  ]);

$helper= $FB->getRedirectLoginHelper();
?>