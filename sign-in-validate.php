<?php
    session_start();
    include 'db.php'; // Include the database connection file

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $recaptchaSecret = '6LdAVjYrAAAAAP7DfS7D9PkOAEgPlRDOWUXT3Yja';
        $recaptchaResponse = $_POST['g-recaptcha-response'];

        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
        $captchaSuccess = json_decode($verify);

        if(!$captchaSuccess->success){
            $_SESSION['error'] = "Captcha verification failed.";
            header("Location: ../BukSU-Events/sign-in.php");
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
            $_SESSION['user_id'] = $faculty_user['user_id']; // Use the correct column name for user ID
            $_SESSION['roles'] = $faculty_user['roles']; // Store the role in the session
            $_SESSION['success'] = "Faculty sign in successful!";
            header("Location: ../BukSU-Events/user-booking.php"); // Redirect to user booking page
            exit();
        } elseif ($student_user && password_verify($password, $student_user['password'])) {
            $_SESSION['user_id'] = $student_user['user_id']; // Use the correct column name for user ID
            $_SESSION['roles'] = $student_user['roles']; // Store the role in the session
            $_SESSION['success'] = "Student sign in successful!"; // Corrected success message
            header("Location: ../BukSU-Events/land-page.php"); // Redirect to user booking page
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password or you are not a registered user."; // More general error
            header("Location: ../BukSU-Events/sign-in.php");
            exit();
        }
    }
?>