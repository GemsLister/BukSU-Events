<?php
    session_start();
    include 'db.php'; // Include the database connection file

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare and execute the SQL statement to check for user credentials
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id']; // Use the correct column name for user ID
            $_SESSION['success'] = "Sign in successful!";
            header("Location: ../BukSU-Events/user-booking.php"); // Redirect to user booking page
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: ../BukSU-Events/sign-in.php");
            exit();
        }
    }
?>