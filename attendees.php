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
    SELECT a.*, u.firstname, u.lastname, u.email, u.gender, u.contact_no, e.event_name 
    FROM attendees a
    JOIN users u ON a.user_id = u.user_id
    JOIN events e ON a.event_id = e.event_id
    ORDER BY e.event_name ASC, u.lastname ASC
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
            <a href="attendance.php" class="nav-link"><i class="fas fa-check-circle"></i> Attendance</a>
            <a href="users.php" class="nav-link"><i class="fas fa-user"></i> Users</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <span class="navbar-brand">Event Registration and Attendance System</span>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Admin Admin
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Attendees Table -->
        <div class="container mt-4">
            <h2>Attendees</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Event Name</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Contact No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($attendees) > 0): ?>
                            <?php foreach ($attendees as $index => $attendee): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($attendee['event_name']); ?></td>
                                    <td><?php echo htmlspecialchars($attendee['firstname']); ?></td>
                                    <td><?php echo htmlspecialchars($attendee['lastname']); ?></td>
                                    <td><?php echo htmlspecialchars($attendee['email']); ?></td>
                                    <td><?php echo htmlspecialchars($attendee['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($attendee['contact_no']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No attendees found.</td>
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