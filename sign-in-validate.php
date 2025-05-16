<?php
session_start();
include 'db.php'; // Assuming db.php is in the same directory

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptchaSecret = '6LdAVjYrAAAAAP7DfS7D9PkOAEgPlRDOWUXT3Yja';
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $captchaSuccess = json_decode($verify);

    if(!$captchaSuccess->success){
        $_SESSION['error'] = "Captcha verification failed.";
        header("Location: php-forms/sign-in.php");
        exit();
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement to check for student credentials
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND roles = 'student'");
    $stmt->execute([$email]);
    $student_user = $stmt->fetch(PDO::FETCH_ASSOC);


    // Prepare and execute the SQL statement to check for faculty credentials
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND roles = 'faculty'");
    $stmt->execute([$email]);
    $faculty_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($faculty_user && password_verify($password, $faculty_user['password'])) {
        $_SESSION['user_id'] = $faculty_user['user_id'];
        $_SESSION['roles'] = $faculty_user['roles'];
        $_SESSION['success'] = "Faculty sign in successful!";
        header("Location: php-forms/user-booking.php"); // Adjust if user-booking.php is in a subdirectory
        exit();
    } elseif ($student_user && password_verify($password, $student_user['password'])) {
        $_SESSION['user_id'] = $student_user['user_id'];
        $_SESSION['roles'] = $student_user['roles'];
        $_SESSION['success'] = "Student sign in successful!";
        header("Location: land-page.php"); // Adjust if land-page.php is in a subdirectory
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password or you are not a registered user.";
        header("Location: php-forms/sign-in.php");
        exit();
    }
} else {
    header("Location: php-forms/sign-in.php");
    exit();
}
?>