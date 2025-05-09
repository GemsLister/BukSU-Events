<?php
// filepath: e:\xampp\htdocs\BukSU-Events\approve-event.php

session_start();
include 'db.php'; // Include the database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Error: Admin is not logged in.");
}

// Check if the event ID is provided
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Update the event status to 'approved'
    $stmt = $pdo->prepare("UPDATE events SET status = 'approved' WHERE event_id = ?");
    if ($stmt->execute([$event_id])) {
        $_SESSION['success'] = "Event approved successfully!";
    } else {
        $_SESSION['error'] = "Failed to approve the event.";
    }
}

// Redirect back to the dashboard
header("Location: admin-dashboard.php");
exit();