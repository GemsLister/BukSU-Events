<?php
session_start();
include 'db.php'; // Make sure this initializes a PDO instance in $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: ../BukSU-Events/admin-sign-in.php");
        exit();
    }

    if (empty($password)) {
        $_SESSION['error'] = "Password cannot be empty.";
        header("Location: ../BukSU-Events/admin-sign-in.php");
        exit();
    }

    try {
        // Get admin by email only — do not check password here
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // No admin found
        if (!$admin) {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: ../BukSU-Events/admin-sign-in.php");
            exit();
        }

        // Verify password using password_verify
        if (!password_verify($password, $admin['password'])) {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: ../BukSU-Events/admin-sign-in.php");
            exit();
        }

        // Login successful — set session
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['success'] = "Sign in successful!";
        header("Location: ../BukSU-Events/dashboard.php");
        exit();

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage()); // You can hide this in production
    }
} else {
    // Not POST request
    header("Location: ../BukSU-Events/admin-sign-in.php");
    exit();
}
