<?php
// filepath: e:\xampp\htdocs\BukSU-Events\quick_attendance_form.php

session_start();
include 'db.php';

if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    die("Error: Invalid event ID.");
}

$event_id = $_GET['event_id'];

// Fetch event details, including target audience
$stmtEvent = $pdo->prepare("SELECT event_name, target_audience FROM events WHERE event_id = ? AND status = 'approved'");
$stmtEvent->execute([$event_id]);
$event = $stmtEvent->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Error: Event not found or not approved.");
}

$registrationMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmtUser = $pdo->prepare("SELECT user_id, password, roles FROM users WHERE email = ?");
    $stmtUser->execute([$email]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $isPasswordCorrect = password_verify($password, $user['password']);

        if ($isPasswordCorrect) {
            // Authentication successful
            $user_id = $user['user_id'];
            $userRoleLower = strtolower(trim($user['roles'])); // Trim whitespace and convert to lowercase
            $eventAudienceLower = strtolower(trim($event['target_audience'])); // Trim whitespace and convert to lowercase

            $allowed = false;
            if (strpos($eventAudienceLower, 'all')) { // If 'all' is present, all are allowed
                $allowed = true;
            } elseif (strpos($eventAudienceLower, 'faculty') !== false && $userRoleLower === 'faculty') {
                $allowed = true;
            } elseif (strpos($eventAudienceLower, 'student') !== false && $userRoleLower === 'student') {
                $allowed = true;
            }

            if ($allowed) {
                // Check if the user is already registered for this event
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM attendees WHERE event_id = ? AND user_id = ?");
                $stmtCheck->execute([$event_id, $user_id]);
                $alreadyRegistered = $stmtCheck->fetchColumn();

                if ($alreadyRegistered > 0) {
                    $registrationMessage = "<div class='alert alert-warning'>You are already marked as attending this event.</div>";
                } else {
                    // Record the attendance, including the user's role
                    $stmtInsert = $pdo->prepare("INSERT INTO attendees (event_id, user_id, attendance_date, roles) VALUES (?, ?, NOW(), ?)");
                    $stmtInsert->execute([$event_id, $user_id, $user['roles']]); // Use the original case from the user data

                    if ($stmtInsert->rowCount() > 0) {
                        $registrationMessage = "<div class='alert alert-success'>Your attendance for <strong>" . htmlspecialchars($event['event_name']) . "</strong> has been recorded.</div>";
                    } else {
                        $registrationMessage = "<div class='alert alert-danger'>Error recording your attendance. Please try again.</div>";
                    }
                }
            } else {
                $registrationMessage = "<div class='alert alert-danger'>This event is not for your audience group.</div>";
            }
        } else {
            $registrationMessage = "<div class='alert alert-danger'>Invalid email or password. Please try again.</div>";
        }
    } else {
        $registrationMessage = "<div class='alert alert-danger'>Invalid email or password. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Attendance - <?php echo htmlspecialchars($event['event_name']); ?></title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/land-page.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Record Attendance for: <?php echo htmlspecialchars($event['event_name']); ?></h2>
        <p><strong>Audience:</strong> <?php echo htmlspecialchars($event['target_audience']); ?></p>
        <?php echo $registrationMessage; ?>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Mark Attendance</button>
        </form>
        <div class="mt-3">
            <a href="land-page.php" class="btn btn-secondary">Back to Events</a>
        </div>
    </div>

    <script src="../BukSU-Events/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>