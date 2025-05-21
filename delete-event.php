<?php
include 'db.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $stmt = $pdo->prepare("DELETE FROM events WHERE event_id = ?");
    $stmt->execute([$event_id]);
}

header("Location: events.php");
exit();
?>