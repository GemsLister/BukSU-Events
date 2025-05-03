<?php
// filepath: e:\xampp\htdocs\BukSU-Events\submit-event-request.php

// Include the database connection
require_once 'db.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("Error: User is not logged in. Please log in to submit an event request.");
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $user_id = $_SESSION['user_id']; // Ensure this is set during login
    $event_name = $_POST['event_name'];
    $event_date_time = str_replace('T', ' ', $_POST['event_date_time']); // Convert 'YYYY-MM-DDTHH:MM' to 'YYYY-MM-DD HH:MM:SS'
    $event_type = $_POST['event_type'];
    $target_audience = $_POST['target_audience'];
    $event_venue = $_POST['event_venue'];
    $event_mode = $_POST['event_mode'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];

    // Insert the event request into the database
    $sql = "INSERT INTO events (user_id, event_name, event_type, target_audience, venue, mode, capacity, description, event_date_time)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared successfully
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    // Bind parameters to the SQL query
    $stmt->bind_param('issssssss', $user_id, $event_name, $event_type, $target_audience, $event_venue, $event_mode, $capacity, $description, $event_date_time);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to the form with a success message
        $_SESSION['success'] = "Event request submitted successfully!";
        header('Location: user-booking.php');
    } else {
        // Redirect back with an error message
        $_SESSION['error'] = "Failed to submit the event request. SQL Error: " . $stmt->error;
        header('Location: user-booking.php');
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect back if the request method is not POST
    header('Location: user-booking.php');
}
?>