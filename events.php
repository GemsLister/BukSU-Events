<?php
// filepath: e:\xampp\htdocs\BukSU-Events\events.php

session_start();
include 'db.php'; // Include the database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Error: Admin is not logged in.");
}

// Fetch approved events from the database
$stmtApproved = $pdo->prepare("SELECT * FROM events WHERE status = 'approved' ORDER BY event_date_time ASC");
$stmtApproved->execute();
$approvedEvents = $stmtApproved->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
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
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search events...">
            </div>
            <a href="dashboard.php" class="nav-link"><i class="fas fa-home"></i> Dashboard</a>
            <a href="events.php" class="nav-link active"><i class="fas fa-calendar-alt"></i> Events</a>
            <a href="attendees.php" class="nav-link"><i class="fas fa-users"></i> Attendees</a>
            <a  href="land-page.php" class="nav-link"><i class="fas fa-sign-out"></i>Sign out</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Approved Events Table -->
        <div class="approved-events container mt-4">
            <h2>Approved Events</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="text-centered">
                        <tr>
                            <th>#</th>
                            <th>Event Name</th>
                            <th>Venue</th>
                            <th>Date & Time</th>
                            <th>Type</th>
                            <th>Audience</th>
                            <th>Capacity</th>
                            <th>Postpone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($approvedEvents) > 0): ?>
                            <?php foreach ($approvedEvents as $index => $event): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                                    <td><?php echo htmlspecialchars($event['venue']); ?></td>
                                    <td><?php echo htmlspecialchars($event['event_date_time']); ?></td>
                                    <td><?php echo htmlspecialchars($event['event_type']); ?></td>
                                    <td><?php echo htmlspecialchars($event['target_audience']); ?></td>
                                    <td><?php echo htmlspecialchars($event['capacity']); ?></td>
                                    <td>
                                        <a href="postpone-event.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to delete this event?');">Postpone</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success">
                                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No approved events found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script src="../BukSU-Events/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../BukSU-Events/jquery3.7.1.js"></script>
    <!-- Searching events -->
    <script>
        $(document).ready(function () {
            $('#searchInput').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $('.approved-events tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>