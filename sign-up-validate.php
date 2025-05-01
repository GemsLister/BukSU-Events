<?php
session_start();
include 'db.php'; // Include the database connection file

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $role = $_POST['role'];
    $contact_no = $_POST['contact_no'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password !== $confirm_password){
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: sign-up.php");
        exit();
    }

    $stmt = $pdo -> prepare("SELECT * FROM users WHERE email = ?");
    $stmt -> execute([$email]);

    if($stmt -> rowCount() > 0){
        $_SESSION['error'] = "Email already exists.";
        header("Location: sign-up.php");
        exit();
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo -> prepare("INSERT INTO users (firstname, lastname, contact_no, role, email, password) VALUES (?, ?, ?,?, ?, ?)");
    

    if($stmt -> execute([$first_name, $last_name, $contact_no, $role, $email, $hashed_password])){
        $_SESSION['success'] = "Sign up successful!";
        header("Location: sign-in.php");
        exit();    
    }
    else{
        echo ('There was an error in the query. Please try again later.');
        exit();
    }
 }
 ?>
