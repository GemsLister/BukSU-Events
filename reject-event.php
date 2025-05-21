<?php
include 'db.php';

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'] ?? null;
    $rejection_reason = $_POST['rejection_reason'] ?? '';

    if ($event_id && $rejection_reason) {
        $stmt = $pdo->prepare("UPDATE events SET status = 'rejected', rejection_reason = ? WHERE event_id = ?");
        $stmt->execute([$rejection_reason, $event_id]);
    }
} else if (isset($_GET['event_id'])) {
    // Fallback for old GET requests (optional)
    $event_id = $_GET['event_id'];
    $stmt = $pdo->prepare("UPDATE events SET status = 'rejected' WHERE event_id = ?");
    $stmt->execute([$event_id]);
}

header("Location: admin-dashboard.php");
exit();
?>