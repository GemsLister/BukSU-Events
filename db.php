<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = "buksu_events";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for errors
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>