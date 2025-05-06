<?php
// filepath: e:\xampp\htdocs\BukSU-Events\dashboard.php

session_start();
include 'db.php'; // Include the database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Error: Admin is not logged in. Please log in to access the events page.");
}

// Fetch approved events with user details
$stmtApproved = $pdo->prepare("
    SELECT e.*, u.firstname, u.lastname, u.contact_no, u.email 
    FROM events e 
    JOIN users u ON e.user_id = u.user_id 
    WHERE e.status = 'approved' 
    ORDER BY e.event_date_time ASC
");
$stmtApproved->execute();
$approvedEvents = $stmtApproved->fetchAll(PDO::FETCH_ASSOC);

// Fetch requested events (pending approval) with user details
$stmtRequested = $pdo->prepare("
    SELECT e.*, u.firstname, u.lastname, u.contact_no, u.email 
    FROM events e 
    JOIN users u ON e.user_id = u.user_id 
    WHERE e.status = 'pending' 
    ORDER BY e.event_date_time ASC
");
$stmtRequested->execute();
$requestedEvents = $stmtRequested->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            <a href="dashboard.php" class="nav-link active"><i class="fas fa-home"></i> Dashboard</a>
            <a href="events.php" class="nav-link"><i class="fas fa-calendar-alt"></i> Events</a>
            <a href="attendees.php" class="nav-link"><i class="fas fa-users"></i> Attendees</a>
            <a  href="land-page.php" class="nav-link"><i class="fas fa-sign-out"></i>Sign out</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Requested Events Table -->
        <div class="request-table container mt-4">
            <h2>Requested Events</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Contact No</th>
                            <th>Email</th>
                            <th>Event Name</th>
                            <th>Venue</th>
                            <th>Date & Time</th>
                            <th>Type</th>
                            <th>Audience</th>
                            <th>Capacity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($requestedEvents) > 0): ?>
                            <?php foreach ($requestedEvents as $index => $event): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($event['firstname']); ?></td>
                                    <td><?php echo htmlspecialchars($event['lastname']); ?></td>
                                    <td><?php echo htmlspecialchars($event['contact_no']); ?></td>
                                    <td><?php echo htmlspecialchars($event['email']); ?></td>
                                    <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                                    <td><?php echo htmlspecialchars($event['venue']); ?></td>
                                    <td><?php echo htmlspecialchars($event['event_date_time']); ?></td>
                                    <td><?php echo htmlspecialchars($event['event_type']); ?></td>
                                    <td><?php echo htmlspecialchars($event['target_audience']); ?></td>
                                    <td><?php echo htmlspecialchars($event['capacity']); ?></td>
                                    
                                    <td class="d-flex gap-3">
                                        <a href="approve-event.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                                        <a href="reject-event.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="13" class="text-center">No requested events found.</td>
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
                $('.request-table tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>