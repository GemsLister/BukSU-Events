<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = "buksu_events";

$conn = mysqli_connect($servername, $username, $password, $dbname);

try{
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
}
catch(PDOException $e){
    die ('Database connection failed: ' . $e->getMessage());
}
echo ('database connected successfully');
?>