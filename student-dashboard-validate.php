<?php
    session_start();
    include 'db.php'; // Include the database connection file

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare and execute the SQL statement to check for student credentials
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND roles = 'student'");
        $stmt->execute([$email]);
        $student_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student_user && password_verify($password, $student_user['password'])) {
            $_SESSION['user_id'] = $student_user['user_id']; // Use the correct column name for user ID
            $_SESSION['roles'] = $student_user['roles']; // Store the role in the session
            $_SESSION['success'] = "Student sign in successful!";
            header("Location: ../BukSU-Events/land-page.php"); // Redirect to student dashboard
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password or you are not a student member.";
            header("Location: ../BukSU-Events/php-forms/student-dashboard-sign-in.php");
            exit();
        }
    }
?>