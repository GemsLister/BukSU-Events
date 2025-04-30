<?php
session_start();
include 'db.php'; // Include the database connection file

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password !== $confirm_password){
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: sign-up.php");
        exit();
    }

    $stmt = $pdo -> prepare("SELECT * FROM sign_up WHERE fullname = ? ");
    $stmt -> execute([$fullname]);

    if($stmt -> rowCount() > 0){
        $_SESSION['error'] = "User already exists.";
        header("Location: sign-up.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo -> prepare("INSERT INTO sign_up (fullname, email, password) VALUES (?, ?, ?)");
    $stmt -> execute([$full_name, $email, $hashed_password]);

    $_SESSION['success'] = "Sign up successful!";
    header("Location: sign-in.php");
    exit();
}
