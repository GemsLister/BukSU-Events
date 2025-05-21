<?php
session_start();
include 'db.php'; // Include the database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Error: Admin is not logged in.");
}

// Handle POST request from the postpone modal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'] ?? null;
    $postpone_reason = $_POST['postpone_reason'] ?? '';

    if ($event_id && $postpone_reason) {
        // Update the event status to 'rejected' and save the reason
        $stmt = $pdo->prepare("UPDATE events SET status = 'rejected', rejection_reason = ? WHERE event_id = ?");
        if ($stmt->execute([$postpone_reason, $event_id])) {
            $_SESSION['success'] = "Event postponed and moved to rejected events!";
        } else {
            $_SESSION['error'] = "Failed to postpone the event.";
        }
    }
}

// Redirect back to the events page
header("Location: admin-dashboard.php");
exit();