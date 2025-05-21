<?php
// filepath: e:\xampp\htdocs\BukSU-Events\admin-dashboard.php

session_start();
include 'db.php'; // Include the database connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: php-forms/admin-sign-in.php");
    exit();
}

// Fetch approved events from the database (add image_path)
$stmtApproved = $pdo->prepare("SELECT * FROM events WHERE status = 'approved' ORDER BY event_date_time ASC");
$stmtApproved->execute();
$approvedEvents = $stmtApproved->fetchAll(PDO::FETCH_ASSOC);

// Fetch rejected events for admin (all users)
$stmtRejected = $pdo->prepare("SELECT * FROM events WHERE status = 'rejected' ORDER BY event_date_time ASC");
$stmtRejected->execute();
$rejectedEvents = $stmtRejected->fetchAll(PDO::FETCH_ASSOC);

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

// Handle event update (edit form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];
    $eventName = $_POST['event_name'];
    $venue = $_POST['venue'];
    $eventDateTime = $_POST['event_date_time'];
    $eventType = $_POST['event_type'];
    $targetAudience = $_POST['target_audience'];
    $capacity = $_POST['capacity'];
    $imagePath = null;

    // Handle image upload if a new file is provided
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileTmpPath = $_FILES['image_path']['tmp_name'];
        $fileName = basename($_FILES['image_path']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExt, $allowedExts)) {
            $newFileName = uniqid('event_', true) . '.' . $fileExt;
            $destPath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $imagePath = $destPath;
            }
        }
    }

    // If no new image uploaded, keep the old image
    if (!$imagePath && isset($_POST['current_image'])) {
        $imagePath = $_POST['current_image'];
    }

    // Update event in the database
    $stmtUpdate = $pdo->prepare("UPDATE events SET event_name = ?, venue = ?, event_date_time = ?, event_type = ?, target_audience = ?, capacity = ?, image_path = ? WHERE event_id = ?");
    $stmtUpdate->execute([
        $eventName,
        $venue,
        $eventDateTime,
        $eventType,
        $targetAudience,
        $capacity,
        $imagePath,
        $eventId
    ]);
    $_SESSION['success'] = "Event updated successfully!";
    header("Location: admin-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/faculty-dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <figure class="sidebar-header mt-4">
            <img src="../BukSU-Events/images/admin1.png" alt="admin-picture">
            <figcaption>Welcome Admin!</figcaption>
        </figure>
        <nav class="nav flex-column">
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search events...">
            </div>
            <a href="admin-dashboard.php" class="nav-link active"><i class="fas fa-dashboard"></i>Dashboard</a>
            <a href="event-attendees.php" class="nav-link"><i class="fas fa-users"></i> Attendees</a>
            <a href="land-page.php" class="nav-link"><i class="fas fa-sign-out"></i>Sign out</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

    <!-- Statistics Dashboard -->
    <div class="container mt-4">
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-sm-4 mb-2">
                <div class="card text-center shadow-lg border-0">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-1"><i class="fa-solid fa-calendar-check text-info me-2"></i>Event Request</h6>
                        <span class="display-6 fw-bold text-info">
                            <?php
                                $presentCount = 0;
                                foreach ($requestedEvents as $event) {
                                    if ($event['status'] === 'pending') $presentCount++;
                                }
                                echo $presentCount;
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 mb-2">
                <div class="card text-center shadow-lg border-0">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-1"><i class="fa-solid fa-check text-success me-2"></i>Approved Events</h6>
                        <span class="display-6 fw-bold text-success">
                            <?php
                                $presentCount = 0;
                                foreach ($approvedEvents as $event) {
                                    if ($event['status'] === 'approved') $presentCount++;
                                }
                                echo $presentCount;
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 mb-2">
                <div class="card text-center shadow-lg border-0">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-1"><i class="fa-solid fa-xmark text-danger me-2"></i>Rejected Events</h6>
                        <span class="display-6 fw-bold text-danger">
                            <?php
                                $absentCount = 0;
                                foreach ($rejectedEvents as $event) {
                                    if ($event['status'] === 'rejected') $absentCount++;
                                }
                                echo $absentCount;
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requested Events Table -->
        <div class="dashboard-section requested-events shadow-lg container">
        <h2>
            <i class="fa-regular fa-clock me-1 text-info"></i> Event Request
            <span class="badge badge-requested ms-2"><?php echo count($requestedEvents); ?></span>
        </h2>
        <div class="table-responsive">
            <table class="table dashboard-table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Event Name</th>
                            <th>Venue</th>
                            <th>Date & Time</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($requestedEvents) > 0): ?>
                            <?php foreach ($requestedEvents as $index => $event): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($event['email']); ?></td>
                                    <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                                    <td><?php echo htmlspecialchars($event['venue']); ?></td>
                                    <td><?php echo htmlspecialchars($event['event_date_time']); ?></td>
                                    <td><?php echo htmlspecialchars($event['event_type']); ?></td>
                                    <td><?php echo htmlspecialchars($event['capacity']); ?></td>
                                    <td>
                                        <?php if (!empty($event['image_path'])): ?>
                                            <a href="<?php echo htmlspecialchars($event['image_path']); ?>" target="_blank">
                                                <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" style="max-width: 80px; max-height: 80px;">
                                            </a>
                                        <?php else: ?>
                                            <span>No image</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="d-flex gap-3">
                                            <a href="approve-event.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                                            <button 
                                                class="btn btn-danger btn-sm reject-btn" 
                                                data-event-id="<?php echo $event['event_id']; ?>"
                                                data-event-name="<?php echo htmlspecialchars($event['event_name']); ?>"
                                                type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal"
                                            >Reject</button>
                                        </td>
                                </tr>
                                    <!-- Rejection Reason Modal -->
                                    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form id="rejectForm" method="POST" action="reject-event.php">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel">Reject Event</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                            <input type="hidden" name="event_id" id="modalEventId">
                                            <div class="mb-3">
                                                <label for="rejectionReason" class="form-label">Reason for Rejection</label>
                                                <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3" required></textarea>
                                            </div>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject Event</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                    </div>
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

        <!-- Approved Events Table -->
        <div class="dashboard-section approved-events shadow-lg container">
        <h2>
            <i class="fa-solid fa-check me-1 text-success"></i> Approved Events
            <span class="badge badge-approved ms-2"><?php echo count($approvedEvents); ?></span>
        </h2>
        <div class="table-responsive">
            <table class="table dashboard-table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Event Name</th>
                            <th>Venue</th>
                            <th>Date & Time</th>
                            <th>Type</th>
                            <th>Image</th>
                            <th>Actions</th>
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
                                    <td><?php echo htmlspecialchars($event['capacity']); ?></td>
                                    <td>
                                        <?php if (!empty($event['image_path'])): ?>
                                            <a href="<?php echo htmlspecialchars($event['image_path']); ?>" target="_blank">
                                                <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" style="max-width: 80px; max-height: 80px;">
                                            </a>
                                        <?php else: ?>
                                            <span>No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-primary btn-sm edit-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editEventModal"
                                            data-id="<?php echo $event['event_id']; ?>"
                                            data-name="<?php echo htmlspecialchars($event['event_name'], ENT_QUOTES); ?>"
                                            data-venue="<?php echo htmlspecialchars($event['venue'], ENT_QUOTES); ?>"
                                            data-datetime="<?php echo htmlspecialchars($event['event_date_time'], ENT_QUOTES); ?>"
                                            data-type="<?php echo htmlspecialchars($event['event_type'], ENT_QUOTES); ?>"
                                            data-audience="<?php echo htmlspecialchars($event['target_audience'], ENT_QUOTES); ?>"
                                            data-capacity="<?php echo htmlspecialchars($event['capacity'], ENT_QUOTES); ?>"
                                            data-image="<?php echo htmlspecialchars($event['image_path'], ENT_QUOTES); ?>"
                                        >Edit</button>
                                        <button type="button"
                                        class="btn btn-warning btn-sm postpone-btn"
                                        data-event-id="<?php echo $event['event_id']; ?>"
                                        data-event-name="<?php echo htmlspecialchars($event['event_name']); ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#postponeModal"
                                    >
                                        Postpone
                                    </button>
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
                                <td colspan="9" class="text-center">No approved events found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Event Modal -->
        <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <form method="post" action="admin-dashboard.php" id="editEventForm" enctype="multipart/form-data">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="event_id" id="editEventId">
            <div class="mb-3">
            <label for="editEventName" class="form-label">Event Name</label>
            <input type="text" class="form-control" id="editEventName" name="event_name" maxlength="50" required>
            </div>
            <div class="mb-3">
            <label for="editVenue" class="form-label">Venue</label>
            <input type="text" class="form-control" id="editVenue" name="venue" required>
            </div>
            <div class="mb-3">
            <label for="editDateTime" class="form-label">Date & Time</label>
            <input type="datetime-local" class="form-control" id="editDateTime" name="event_date_time" required>
            </div>
            <div class="mb-3">
            <label for="editType" class="form-label">Event Type</label>
            <input type="text" class="form-control" id="editType" name="event_type" required>
            </div>
            <div class="mb-3">
            <label for="editAudience" class="form-label">Target Audience</label>
            <input type="text" class="form-control" id="editAudience" name="target_audience" required>
            </div>
            <div class="mb-3">
            <label for="editCapacity" class="form-label">Capacity</label>
            <input type="number" class="form-control" id="editCapacity" name="capacity" required>
            </div>
            <div class="mb-3">
            <label for="editImage" class="form-label">Event Image</label>
            <input type="file" class="form-control" id="editImage" name="image_path" accept="image/*">
            <input type="hidden" name="current_image" id="currentImageInput">
            <div id="currentImagePreview" class="mt-2">
            <a href="#" id="currentImageLink" target="_blank" style="display:none;">
                <img id="currentImage" src="" alt="Current Event Image" style="max-width: 100px; max-height: 100px; display: none;">
            </a>
            </div>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
        </div>
        </form>
        </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
            document.getElementById('editEventId').value = button.getAttribute('data-id');
            document.getElementById('editEventName').value = button.getAttribute('data-name');
            document.getElementById('editVenue').value = button.getAttribute('data-venue');
            // Convert to local datetime format for input
            var dt = button.getAttribute('data-datetime');
            if (dt) {
            // If dt is in "YYYY-MM-DD HH:MM:SS", convert to "YYYY-MM-DDTHH:MM"
            var localDT = dt.replace(' ', 'T').slice(0, 16);
            document.getElementById('editDateTime').value = localDT;
            } else {
            document.getElementById('editDateTime').value = '';
            }
            document.getElementById('editType').value = button.getAttribute('data-type');
            document.getElementById('editAudience').value = button.getAttribute('data-audience');
            document.getElementById('editCapacity').value = button.getAttribute('data-capacity');
            // Set image preview and hidden input
            var imagePath = button.getAttribute('data-image');
            var currentImage = document.getElementById('currentImage');
            var currentImageInput = document.getElementById('currentImageInput');
            var currentImageLink = document.getElementById('currentImageLink');
            if (imagePath) {
                currentImage.src = imagePath;
                currentImage.style.display = 'block';
                currentImageInput.value = imagePath;
                currentImageLink.href = imagePath;
                currentImageLink.style.display = 'inline-block';
            } else {
                currentImage.src = '';
                currentImage.style.display = 'none';
                currentImageInput.value = '';
                currentImageLink.href = '#';
                currentImageLink.style.display = 'none';
            }
            });
            });
        });
        </script>

                <!-- Postpone Reason Modal -->
                <div class="modal fade" id="postponeModal" tabindex="-1" aria-labelledby="postponeModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="postponeForm" method="POST" action="postpone-event.php">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="postponeModalLabel">Postpone Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <input type="hidden" name="event_id" id="modalPostponeEventId">
                        <div class="mb-3">
                            <label for="postponeReason" class="form-label">Reason for Postponement</label>
                            <textarea class="form-control" id="postponeReason" name="postpone_reason" rows="3" required></textarea>
                        </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Postpone Event</button>
                        </div>
                    </div>
                    </form>
                </div>
                </div>

<!-- Rejected Events Table -->
<div class="dashboard-section rejected-events shadow-lg container">
        <h2>
            <i class="fa-solid fa-xmark me-1 text-danger"></i> Rejected Events
            <span class="badge badge-rejected ms-2"><?php echo count($rejectedEvents); ?></span>
        </h2>
        <div class="table-responsive">
            <table class="table dashboard-table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event Name</th>
                    <th>Venue</th>
                    <th>Date & Time</th>
                    <th>Type</th>
                    <th>Audience</th>
                    <th>Capacity</th>
                    <th>Image</th>
                    <th>Rejection Reason</th>
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
                            <td>
                                <?php if (!empty($event['image_path'])): ?>
                                    <a href="<?php echo htmlspecialchars($event['image_path']); ?>" target="_blank">
                                        <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" style="max-width: 80px; max-height: 80px;">
                                    </a>
                                <?php else: ?>
                                    <span>No image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo !empty($event['rejection_reason']) ? htmlspecialchars($event['rejection_reason']) : '<span class="text-muted">No reason provided</span>'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No rejected events found.</td>
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
                // Filter all event tables
                $('.dashboard-section tbody tr').each(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Handle postpone button click
            $('.postpone-btn').on('click', function () {
                var eventId = $(this).data('event-id');
                $('#modalPostponeEventId').val(eventId);
                $('#postponeReason').val('');
            });
        });
</script>
</body>
</html>