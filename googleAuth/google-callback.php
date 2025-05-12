<?php
require_once '../vendor/autoload.php';
session_start();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT']);

if(isset($_GET['code'])){
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if(!isset($token['error'])){
            $client->setAccessToken($token['access_token']); // Correct key for access token

            $oauth2 = new Google\Service\Oauth2($client);
            $userInfo = $oauth2->userinfo->get();

            $_SESSION['user_type'] = 'google';
            $_SESSION['user_name'] = $userInfo->name;
            $_SESSION['user_email'] = $userInfo->email;
            $_SESSION['user_image'] = $userInfo->picture;

            $_SESSION['success'] = 'Login with Google successful!';
            header('Location: /BukSU-Events/land-page.php');
            exit();

        } else {
            $_SESSION['error'] = 'Google sign-in failed: ' . $token['error'];
            header('Location: sign-in.php'); // Correct header format
            exit();
        }
    } catch (\Google\Exception $e) {
        $_SESSION['error'] = 'Google sign-in error: ' . $e->getMessage();
        header('Location: sign-in.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Invalid Google sign-in request.';
    header('Location: sign-in.php');
    exit();
}
?>