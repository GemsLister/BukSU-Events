<?php
// filepath: e:\xampp\htdocs\BukSU-Events\delete-event.php

session_start();
include 'db.php'; // Include the database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Error: Admin is not logged in.");
}

// Check if the event ID is provided
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    try {
        // Start a transaction to ensure atomicity
        $pdo->beginTransaction();

        // Delete related records from the attendees table
        $stmt_delete_attendees = $pdo->prepare("DELETE FROM attendees WHERE event_id = ?");
        $stmt_delete_attendees->execute([$event_id]);

        // Delete the event from the events table
        $stmt_delete_event = $pdo->prepare("DELETE FROM events WHERE event_id = ?");
        if ($stmt_delete_event->execute([$event_id])) {
            $_SESSION['success'] = "Event and associated attendees deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete the event.";
            $pdo->rollBack(); // Rollback the transaction if event deletion fails
        }

        // Commit the transaction
        $pdo->commit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting event: " . $e->getMessage();
        $pdo->rollBack(); // Rollback on any error
    }
}

// Redirect back to the events page
header("Location: ../BukSU-Events/events.php");
exit();
?>