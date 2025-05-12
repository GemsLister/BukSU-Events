<?php
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']); // Access the Client ID
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']); // Access the Client Secret
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT']);
$client->addScope('email');
$client->addScope('profile');

header('Location: ' . $client->createAuthUrl());

exit();
?>