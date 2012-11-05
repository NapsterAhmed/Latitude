<?php

session_start();

require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_LatitudeService.php';

$client = new Google_Client();
$client->setAccessType('online'); // default: offline
$client->setClientId('1053470326306-i3650puohr9rq8msiovb35its8fmhtcm.apps.googleusercontent.com');
$client->setClientSecret('rASPNEwKQZfY81HfVm0ZbwAo');
$client->setRedirectUri("http://www.vedohost.com/gtest/lat.php");
$client->setDeveloperKey('AIzaSyA7WlDoFDmfKkN0XEBWaRO0aJloDDM68jY'); // API key
$client->setApplicationName("Latitude_Example_App");
$service = new Google_LatitudeService($client);


if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
}

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
} else {
  $authUrl = $client->createAuthUrl();
}

if ($client->getAccessToken()) {
  // Start to make API requests.
  //$location = $service->location->listLocation();
  $currentLocation = $service->currentLocation->get();
  $_SESSION['access_token'] = $client->getAccessToken();
}

var_dump($service->location->listLocation());
?>
<!doctype html>
<html>
<head><link rel='stylesheet' href='style.css' /></head>
<body>
<header><h1>Google Latitude Sample App</h1></header>
<div class="box">
  <?php if(isset($currentLocation)): ?>
    <div class="currentLocation">
      <pre><?php var_dump($currentLocation); ?></pre>
    </div>
  <?php endif ?>

  <?php if (isset($location)): ?>
    <div class="location">
      <pre><?php var_dump($location); ?></pre>
    </div>
  <?php endif ?>

  <?php
    if(isset($authUrl)) {
      print "<a class='login' href='$authUrl'>Connect Me!</a>";
    } else {
     print "<a class='logout' href='?logout'>Logout</a>";
    }
  ?>
</div>
</body></html>