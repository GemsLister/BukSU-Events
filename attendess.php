<?php
// filepath: e:\xampp\htdocs\BukSU-Events\attendees.php

session_start();
include 'db.php'; // Include the database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Error: Admin is not logged in. Please log in to access the attendees page.");
}

// Fetch attendees from the database
$stmt = $pdo->prepare("
    SELECT a.*, e.event_name 
    FROM attendees a 
    JOIN events e ON a.event_id = e.event_id 
    ORDER BY e.event_date_time ASC
");
$stmt->execute();
$attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendees</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <figure class="sidebar-header mt-4">
            <img src="../BukSU-Events/images/admin.png" alt="admin-picture">
            <figcaption>Welcome Admin!</figcaption>
        </figure>
        <nav class="nav flex-column">
            <a href="dashboard.php" class="nav-link"><i class="fas fa-home"></i> Dashboard</a>
            <a href="events.php" class="nav-link"><i class="fas fa-calendar-alt"></i> Events</a>
            <a href="attendees.php" class="nav-link active"><i class="fas fa-users"></i> Attendees</a>
            <a href="land-page.php" class="nav-link"><i class="fas fa-sign-out"></i> Sign out</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container mt-4">
            <h2>Attendees</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Contact No</th>
                            <th>Event Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($attendees) > 0): ?>
                            <?php foreach ($attendees as $index => $attendee): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($attendee['firstname']); ?></td>
                                    <td><?php echo htmlspecialchars($attendee['lastname']); ?></td>
                                    <td><?php echo htmlspecialchars($attendee['email']); ?></td>
                                    <td><?php echo htmlspecialchars($attendee['contact_no']); ?></td>
                                    <td><?php echo htmlspecialchars($attendee['event_name']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No attendees found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="../BukSU-Events/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>