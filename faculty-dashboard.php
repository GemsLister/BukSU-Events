<?php
// filepath: e:\xampp\htdocs\BukSU-Events\events.php

session_start();
include 'db.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User is not logged in.");
}

// Fetch user details
$stmtUser = $pdo->prepare("SELECT firstname, lastname FROM users WHERE user_id = ? AND roles = 'faculty'");
$stmtUser->execute([$_SESSION['user_id']]);
$faculty_user = $stmtUser->fetch(PDO::FETCH_ASSOC);

if (!$faculty_user) {
    die("Error: Could not retrieve user information.");
}

$userId = $_SESSION['user_id'];

// Fetch approved events created by the logged-in user
$stmtApproved = $pdo->prepare("SELECT * FROM events WHERE status = 'approved' AND user_id = ? ORDER BY event_date_time ASC");
$stmtApproved->execute([$userId]);
$approvedEvents = $stmtApproved->fetchAll(PDO::FETCH_ASSOC);

// Fetch rejected events created by the logged-in user
$stmtRejected = $pdo->prepare("SELECT * FROM events WHERE status = 'rejected' AND user_id = ? ORDER BY event_date_time ASC");
$stmtRejected->execute([$userId]);
$rejectedEvents = $stmtRejected->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/dashboard.css">
</head>
<body>
    <aside class="sidebar">
        <figure class="sidebar-header mt-4">
            <img src="../BukSU-Events/images/user.png" alt="user-picture">
            <figcaption>Welcome
                <?php echo htmlspecialchars($faculty_user['firstname']) . ' ' . htmlspecialchars($faculty_user['lastname']); ?>
            </figcaption>
        </figure>
        <nav class="nav flex-column">
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search events...">
            </div>
            <a href="events.php" class="nav-link active"><i class="fa-solid fa-check"></i>Requested Events</a>
            <a href="land-page.php" class="nav-link"><i class="fas fa-sign-out"></i>Sign out</a>
        </nav>
    </aside>

    <main class="main-content">
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

        <div class="rejected-events container mt-4">
            <h2>Rejected Events</h2>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($rejectedEvents) > 0): ?>
                            <?php foreach ($rejectedEvents as $index => $event): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                                    <td><?php echo htmlspecialchars($event['venue']); ?></td>
                                    <td><?php echo htmlspecialchars($event['event_date_time']); ?></td>
                                    <td><?php echo htmlspecialchars($event['event_type']); ?></td>
                                    <td><?php echo htmlspecialchars($event['target_audience']); ?></td>
                                    <td><?php echo htmlspecialchars($event['capacity']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No rejected events found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script src="../BukSU-Events/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../BukSU-Events/jquery3.7.1.js"></script>
    <script>
        $(document).ready(function () {
            $('#searchInput').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $('.approved-events tbody tr, .rejected-events tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>