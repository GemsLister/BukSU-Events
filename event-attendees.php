<?php
// filepath: e:\xampp\htdocs\BukSU-Events\event_attendees.php

session_start();
include 'db.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Error: Admin is not logged in. Please log in.");
}

// Fetch all approved events for the dropdown list
$stmtEvents = $pdo->prepare("SELECT event_id, event_name FROM events WHERE status = 'approved' ORDER BY event_name ASC");
$stmtEvents->execute();
$events = $stmtEvents->fetchAll(PDO::FETCH_ASSOC);

$attendees = [];
$selectedEventName = '';

if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
    $selectedEventId = $_GET['event_id'];

    // Fetch attendees for the selected event with user details AND attendance status
    $stmtAttendees = $pdo->prepare("
        SELECT a.*, u.firstname, u.lastname, u.email, u.contact_no, a.attendance_status
        FROM attendees a
        JOIN users u ON a.user_id = u.user_id
        WHERE a.event_id = ?
        ORDER BY u.lastname ASC, u.firstname ASC
    ");
    $stmtAttendees->execute([$selectedEventId]);
    $attendees = $stmtAttendees->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the name of the selected event
    $stmtEventName = $pdo->prepare("SELECT event_name FROM events WHERE event_id = ?");
    $stmtEventName->execute([$selectedEventId]);
    $eventResult = $stmtEventName->fetch(PDO::FETCH_ASSOC);
    if ($eventResult) {
        $selectedEventName = $eventResult['event_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Attendees - Admin Dashboard</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/dashboard.css">
    <style>
        .attendance-buttons button {
            margin-right: 5px;
        }
        .present {
            background-color: #28a745;
            color: white;
            border: none;
        }
        .absent {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        .delete {
            background-color: #ffc107; /* Yellow/Amber for delete */
            color: black;
            border: none;
        }
        .status-present {
            color: #28a745;
            font-weight: bold;
        }
        .status-absent {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <figure class="sidebar-header mt-4">
            <img src="../BukSU-Events/images/admin.png" alt="admin-picture">
            <figcaption>Welcome Admin!</figcaption>
        </figure>
        <nav class="nav flex-column">
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search attendees...">
            </div>
            <a href="admin-dashboard.php" class="nav-link"><i class="fas fa-home"></i> Dashboard</a>
            <a href="events.php" class="nav-link"><i class="fas fa-calendar-alt"></i> Events</a>
            <a href="event_attendees.php" class="nav-link active"><i class="fas fa-users"></i> Attendees</a>
            <a  href="land-page.php" class="nav-link"><i class="fas fa-sign-out"></i>Sign out</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="container mt-4">
            <h2>Event Attendees</h2>

            <form method="get" class="mb-3">
                <div class="mb-3">
                    <label for="event_id" class="form-label">Select Event:</label>
                    <select class="form-select" id="event_id" name="event_id" onchange="this.form.submit()">
                        <option value="">-- Select an Event --</option>
                        <?php foreach ($events as $event): ?>
                            <option value="<?php echo $event['event_id']; ?>" <?php if (isset($_GET['event_id']) && $_GET['event_id'] == $event['event_id']) echo 'selected'; ?>><?php echo htmlspecialchars($event['event_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if ($selectedEventName): ?>
                <h3>Attendees for: <?php echo htmlspecialchars($selectedEventName); ?></h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Contact No</th>
                                <th>Attendance Date & Time</th>
                                <th>Status</th>
                                <th>Action</th>
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
                                        <td><?php echo htmlspecialchars($attendee['roles']); ?></td>
                                        <td><?php echo htmlspecialchars($attendee['contact_no']); ?></td>
                                        <td><?php echo htmlspecialchars($attendee['attendance_date']); ?></td>
                                        <td>
                                            <span id="status_<?php echo $attendee['attendee_id']; ?>" class="<?php echo ($attendee['attendance_status'] === 'present') ? 'status-present' : 'status-absent'; ?>">
                                                <?php echo htmlspecialchars(ucfirst($attendee['attendance_status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="attendance-buttons">
                                                <form method="post" action="update-attendance.php" class="d-inline">
                                                    <input type="hidden" name="attendee_id" value="<?php echo $attendee['attendee_id']; ?>">
                                                    <input type="hidden" name="attendance_status" value="present">
                                                    <input type="hidden" name="event_id_redirect" value="<?php echo isset($_GET['event_id']) ? $_GET['event_id'] : ''; ?>">
                                                    <button type="submit" class="btn btn-sm present" title="Mark as Present">Present</button>
                                                </form>
                                                <form method="post" action="update-attendance.php" class="d-inline">
                                                    <input type="hidden" name="attendee_id" value="<?php echo $attendee['attendee_id']; ?>">
                                                    <input type="hidden" name="attendance_status" value="absent">
                                                    <input type="hidden" name="event_id_redirect" value="<?php echo isset($_GET['event_id']) ? $_GET['event_id'] : ''; ?>">
                                                    <button type="submit" class="btn btn-sm absent" title="Mark as Absent">Absent</button>
                                                </form>
                                                <form method="post" action="update-attendance.php" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this attendee?');">
                                                    <input type="hidden" name="attendee_id" value="<?php echo $attendee['attendee_id']; ?>">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="event_id_redirect" value="<?php echo isset($_GET['event_id']) ? $_GET['event_id'] : ''; ?>">
                                                    <button type="submit" class="btn btn-sm delete" title="Delete Attendee"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="10" class="text-center">No attendees recorded for this event.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif (!empty($events)): ?>
                <p>Select an event from the dropdown to view its attendees.</p>
            <?php else: ?>
                <p>No approved events available to show attendees.</p>
            <?php endif; ?>
        </div>
    </main>

    <script src="../BukSU-Events/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../BukSU-Events/jquery3.7.1.js"></script>
    <script>
        $(document).ready(function () {
            $('#searchInput').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $('table tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>