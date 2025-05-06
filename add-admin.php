<?php
// filepath: e:\xampp\htdocs\BukSU-Events\add-admin.php

// Include the database connection
include 'db.php';

// Admin details
$admin_email = 'buksuevent123@event.com';
$admin_password_plain = 'buksueventsadmin';
$admin_name = 'BukSU Events Admin';
$admin_role = 'admin'; // Set the role to 'admin'

// Validate email format
if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
    die("Error: Invalid email format.");
}

// Generate hashed password
// $admin_password = password_hash($admin_password_plain, PASSWORD_DEFAULT);
// echo "Generated Hash: " . $admin_password . "<br>";

try {
    // Insert or update the admin account
    $stmt = $pdo->prepare("
        INSERT INTO admin (email, password, name, role)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE password = VALUES(password), name = VALUES(name), role = VALUES(role)
    ");
    $stmt->execute([$admin_email, $admin_password, $admin_name, $admin_role]);

    if ($stmt->rowCount() > 0) {
        echo "Query executed successfully. Rows affected: " . $stmt->rowCount() . "<br>";
    } else {
        echo "Query did not affect any rows.<br>";
    }

    echo "Admin account created or updated successfully!<br>";
    echo "Admin Password (hashed): " . $admin_password . "<br>";
} catch (PDOException $e) {
    error_log("Error creating or updating admin account: " . $e->getMessage());
    echo "Error: " . $e->getMessage();
}
?>