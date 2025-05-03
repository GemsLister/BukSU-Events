<?php
include 'db.php';

$event_id = $_GET['event_id'];
$stmt = $pdo->prepare("UPDATE events SET status = 'approved' WHERE event_id = ?");
$stmt->execute([$event_id]);

header("Location: dashboard.php");
exit();
?>