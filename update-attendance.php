<?php
// filepath: e:\xampp\htdocs\BukSU-Events\update-attendance.php

session_start();
include 'db.php';

/// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: php-forms/admin-sign-in.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['attendee_id']) && is_numeric($_POST['attendee_id'])) {
        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            // Prepare and execute the delete query
            $stmtDelete = $pdo->prepare("DELETE FROM attendees WHERE attendee_id = ?");
            $stmtDelete->execute([$_POST['attendee_id']]);

            // Check if the deletion was successful (optional)
            if ($stmtDelete->rowCount() > 0) {
                $deleteSuccess = true;
            } else {
                $deleteError = "Error deleting attendee.";
            }
        } elseif (isset($_POST['attendance_status']) && in_array($_POST['attendance_status'], ['present', 'absent'])) {
            $status = $_POST['attendance_status'];
            $stmtUpdate = $pdo->prepare("UPDATE attendees SET attendance_status = ? WHERE attendee_id = ?");
            $stmtUpdate->execute([$status, $_POST['attendee_id']]);
        } else {
            die("Error: Invalid parameters (no valid action or status).");
        }

        // Redirect back to the event attendees page, keeping the selected event
        $redirectUrl = 'event-attendees.php';
        if (isset($_POST['event_id_redirect']) && is_numeric($_POST['event_id_redirect'])) {
            $redirectUrl .= '?event_id=' . $_POST['event_id_redirect'];
        }
        header("Location: " . $redirectUrl);
        exit();
    } else {
        die("Error: Invalid parameters (attendee_id missing or invalid).");
    }
} else {
    die("Error: This script only accepts POST requests.");
}
?>