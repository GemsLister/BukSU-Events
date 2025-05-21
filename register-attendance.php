<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: php-forms/sign-in.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['roles'] ?? '';
$event_id = $_POST['event_id'] ?? null;

if ($event_id) {
    // Prevent duplicate registration
    $stmt = $pdo->prepare("SELECT * FROM attendees WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$user_id, $event_id]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = 'You are already registered for this event.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO attendees (event_id, user_id, roles, attendance_date, attendance_status) VALUES (?, ?, ?, NOW(), 'absent')");
        $stmt->execute([$event_id, $user_id, $user_role]);
        $_SESSION['success'] = 'Successfully registered for the event!';
    }
}
header('Location: land-page.php');
exit();
?>