<?php
session_start();
include 'db.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: php-forms/admin-sign-in.php");
    exit();
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
        SELECT a.*, u.firstname, u.lastname, u.email, u.contact_no, a.attendance_status, u.roles, a.attendance_date
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
    <link rel="stylesheet" href="../BukSU-Events/css-style/event-attendees.css">
</head>
<body>
    <aside class="sidebar">
        <figure class="sidebar-header mt-4">
            <img src="../BukSU-Events/images/admin1.png" alt="admin-picture" style="border: none; box-shadow: none; border-radius: 0;">
            <figcaption>Welcome Admin!</figcaption>
        </figure>
        <nav class="nav flex-column">
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search attendees...">
            </div>
            <a href="admin-dashboard.php" class="nav-link"><i class="fas fa-dashboard"></i> Dashboard</a>
            <a href="event_attendees.php" class="nav-link active"><i class="fas fa-users"></i> Attendees</a>
            <a href="land-page.php" class="nav-link"><i class="fas fa-sign-out"></i>Sign out</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="container mt-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="fw-bold text-primary mb-0" style="letter-spacing:1px;">
                    <i class="fas fa-users me-2"></i>Event Attendees
                </h2>
                <img src="../BukSU-Events/images/black.png" alt="BukSU Logo" style="height: 50px;">
            </div>

            <form method="get" class="mb-5">
                <div class="row align-items-end">
                    <div class="col-md-6">
                        <label for="event_id" class="form-label fw-semibold">Select Event:</label>
                        <select class="form-select shadow-sm" id="event_id" name="event_id" onchange="this.form.submit()">
                            <option value="">-- Select an Event --</option>
                            <?php foreach ($events as $event): ?>
                                <option value="<?php echo $event['event_id']; ?>" <?php if (isset($_GET['event_id']) && $_GET['event_id'] == $event['event_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($event['event_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>

            <?php if ($selectedEventName): ?>
                <?php
                    // Calculate statistics
                    $totalAttendees = count($attendees);
                    $presentCount = 0;
                    $absentCount = 0;
                    foreach ($attendees as $a) {
                        if ($a['attendance_status'] === 'present') $presentCount++;
                        elseif ($a['attendance_status'] === 'absent') $absentCount++;
                    }
                ?>
                <!-- Statistic Cards -->
                <div class="row mb-5 justify-content-center g-4">
                    <div class="col-md-3 col-6">
                        <div class="card text-center border-primary border-2" style="box-shadow: 0 4px 16px rgba(0,0,0,0.08);">
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="fas fa-users fa-2x text-primary"></i>
                                </div>
                                <h5 class="card-title fw-bold">Total Registered</h5>
                                <span class="display-6 fw-bold text-primary"><?php echo $totalAttendees; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card text-center border-success border-2" style="box-shadow: 0 4px 16px rgba(0,0,0,0.08);">
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="fas fa-user-check fa-2x text-success"></i>
                                </div>
                                <h5 class="card-title fw-bold">Present</h5>
                                <span class="display-6 fw-bold text-success"><?php echo $presentCount; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card text-center border-danger border-2" style="box-shadow: 0 4px 16px rgba(0,0,0,0.08);">
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="fas fa-user-times fa-2x text-danger"></i>
                                </div>
                                <h5 class="card-title fw-bold">Absent</h5>
                                <span class="display-6 fw-bold text-danger"><?php echo $absentCount; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($selectedEventName): ?>
                <h3>Attendees for: <?php echo htmlspecialchars($selectedEventName); ?></h3>
                <div class="table-responsive">
                    <table class="event-attendees-table table table-bordered">
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
                                                <button type="submit" class="btn btn-success btn-sm" title="Mark as Present">
                                                    <i class="fas fa-check"></i> Present
                                                </button>
                                            </form>
                                            <form method="post" action="update-attendance.php" class="d-inline">
                                                <input type="hidden" name="attendee_id" value="<?php echo $attendee['attendee_id']; ?>">
                                                <input type="hidden" name="attendance_status" value="absent">
                                                <input type="hidden" name="event_id_redirect" value="<?php echo isset($_GET['event_id']) ? $_GET['event_id'] : ''; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" title="Mark as Absent">
                                                    <i class="fas fa-times"></i> Absent
                                                </button>
                                            </form>
                                            <form method="post" action="update-attendance.php" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this attendee?');">
                                                <input type="hidden" name="attendee_id" value="<?php echo $attendee['attendee_id']; ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="event_id_redirect" value="<?php echo isset($_GET['event_id']) ? $_GET['event_id'] : ''; ?>">
                                                <button type="submit" class="btn btn-warning btn-sm" title="Delete Attendee">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
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
                <a href="print-attendance.php?event_id=<?php echo $_GET['event_id']; ?>" class="btn btn-primary" target="_blank">Print Attendance</a>
            <?php elseif (!empty($events)): ?>
                <p>Select an event from the dropdown to view its attendees and the print option.</p>
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
                $('.event-attendees-table tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>