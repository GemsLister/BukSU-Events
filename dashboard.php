<?php
// filepath: e:\xampp\htdocs\BukSU-Events\dashboard.php

session_start();
include 'db.php'; // Include the database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Error: Admin is not logged in. Please log in to access the dashboard.");
}

// Fetch event requests from the database
$stmt = $pdo->prepare("SELECT e.*, u.email FROM events e JOIN users u ON e.user_id = u.user_id ORDER BY e.event_date_time DESC");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Event ID</th>
                    <th>User Email</th>
                    <th>Event Name</th>
                    <th>Date & Time</th>
                    <th>Type</th>
                    <th>Audience</th>
                    <th>Venue</th>
                    <th>Mode</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?php echo $event['event_id']; ?></td>
                        <td><?php echo $event['email']; ?></td>
                        <td><?php echo $event['event_name']; ?></td>
                        <td><?php echo $event['event_date_time']; ?></td>
                        <td><?php echo $event['event_type']; ?></td>
                        <td><?php echo $event['target_audience']; ?></td>
                        <td><?php echo $event['venue']; ?></td>
                        <td><?php echo $event['mode']; ?></td>
                        <td><?php echo $event['capacity']; ?></td>
                        <td><?php echo ucfirst($event['status']); ?></td>
                        <td>
                            <a href="approve-event.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                            <a href="reject-event.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="../BukSU-Events/bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>