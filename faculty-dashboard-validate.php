<?php
    session_start();
    include 'db.php'; // Include the database connection file

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare and execute the SQL statement to check for faculty credentials
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND roles = 'faculty'");
        $stmt->execute([$email]);
        $faculty_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($faculty_user && password_verify($password, $faculty_user['password'])) {
            $_SESSION['user_id'] = $faculty_user['user_id']; // Use the correct column name for user ID
            $_SESSION['roles'] = $faculty_user['roles']; // Store the role in the session
            $_SESSION['success'] = "Faculty sign in successful!";
            header("Location: ../BukSU-Events/land-page.php"); // Redirect to faculty dashboard
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password or you are not a faculty member.";
            header("Location: ../BukSU-Events/php-forms/faculty-dashboard-sign-in.php");
            exit();
        }
    }
?>