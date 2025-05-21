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
            $client->setAccessToken($token['access_token']);

            $oauth2 = new Google\Service\Oauth2($client);
            $userInfo = $oauth2->userinfo->get();

            // Check if user exists in your users table
            require_once '../db.php';
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$userInfo->email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // User exists, log them in
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['roles'] = $user['roles'];
                $_SESSION['success'] = "Sign in successful!";
                // Redirect based on role
                if ($user['roles'] === 'faculty') {
                    header('Location: ../land-page.php');
                } else {
                    header('Location: ../land-page.php');
                }
                exit();
            } else {
                // User does not exist, go to registration
                $_SESSION['user_type'] = 'google';
                $_SESSION['user_name'] = $userInfo->name;
                $_SESSION['user_email'] = $userInfo->email;
                $_SESSION['user_image'] = $userInfo->picture;
                header('Location: ../php-forms/connect-with-google.php');
                exit();
            }

        } else {
            $_SESSION['error'] = 'Google sign-in failed: ' . $token['error'];
            header('Location: ../php-forms/sign-in.php');
            exit();
        }
    } catch (\Google\Exception $e) {
        $_SESSION['error'] = 'Google sign-in error: ' . $e->getMessage();
        header('Location: ../php-forms/sign-in.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Invalid Google sign-in request.';
    header('Location: ../php-forms/sign-in.php');
    exit();
}
?>