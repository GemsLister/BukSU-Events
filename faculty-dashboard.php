<?php
// filepath: e:\xampp\htdocs\BukSU-Events\events.php

session_start();
include 'db.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: php-forms/faculty-dashboard-sign-in.php");
    exit();
}

// Fetch user details
$stmtUser = $pdo->prepare("SELECT firstname, lastname, contact_no FROM users WHERE user_id = ? AND roles = 'faculty'");
$stmtUser->execute([$_SESSION['user_id']]);
$faculty_user = $stmtUser->fetch(PDO::FETCH_ASSOC);

if (!$faculty_user) {
    die("Error: Could not retrieve user information.");
}

$userId = $_SESSION['user_id'];

// Fetch pending events created by the logged-in user
$stmtPending = $pdo->prepare("SELECT * FROM events WHERE status = 'pending' AND user_id = ? ORDER BY event_date_time ASC");
$stmtPending->execute([$userId]);
$pendingEvents = $stmtPending->fetchAll(PDO::FETCH_ASSOC);

// Fetch approved events created by the logged-in user
$stmtApproved = $pdo->prepare("SELECT * FROM events WHERE status = 'approved' AND user_id = ? ORDER BY event_date_time ASC");
$stmtApproved->execute([$userId]);
$approvedEvents = $stmtApproved->fetchAll(PDO::FETCH_ASSOC);

// Fetch rejected events created by the logged-in user
$stmtRejected = $pdo->prepare("SELECT * FROM events WHERE status = 'rejected' AND user_id = ? ORDER BY event_date_time ASC");
$stmtRejected->execute([$userId]);
$rejectedEvents = $stmtRejected->fetchAll(PDO::FETCH_ASSOC);

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
    $stmtUpdate = $pdo->prepare("UPDATE events SET event_name = ?, venue = ?, event_date_time = ?, event_type = ?, target_audience = ?, capacity = ?, image_path = ? WHERE event_id = ? AND user_id = ?");
    $stmtUpdate->execute([
        $eventName,
        $venue,
        $eventDateTime,
        $eventType,
        $targetAudience,
        $capacity,
        $imagePath,
        $eventId,
        $userId
    ]);
    $_SESSION['success'] = "Event updated successfully!";
    header("Location: faculty-dashboard.php");
    exit();
}

// Fetch registered (attended) events for the logged-in faculty
$stmtRegistered = $pdo->prepare("
    SELECT e.*, a.attendance_date, a.attendance_status
    FROM attendees a
    JOIN events e ON a.event_id = e.event_id
    WHERE a.user_id = ? AND a.roles = 'faculty'
    ORDER BY a.attendance_date DESC
");
$stmtRegistered->execute([$userId]);
$registeredEvents = $stmtRegistered->fetchAll(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newFirstname = $_POST['edit_firstname'];
    $newLastname = $_POST['edit_lastname'];
    $newContact = $_POST['edit_contact_no'];
    $newPassword = $_POST['edit_password'];
    $confirmPassword = $_POST['edit_confirm_password'];
    $currentPassword = $_POST['edit_current_password'];

    // If user wants to change password, require current password
    if (!empty($newPassword)) {
        // Password length check
        if (strlen($newPassword) < 8) {
            $_SESSION['error'] = "Password must be at least 8 characters.";
            header("Location: faculty-dashboard.php");
            exit();
        }

        // Fetch the current hashed password from DB
        $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || empty($currentPassword) || !password_verify($currentPassword, $user['password'])) {
            $_SESSION['error'] = "Current password is incorrect.";
            header("Location: faculty-dashboard.php");
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "Passwords do not match.";
            header("Location: faculty-dashboard.php");
            exit();
        }
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, contact_no = ?, password = ? WHERE user_id = ?");
        $stmt->execute([$newFirstname, $newLastname, $newContact, $hashedPassword, $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, contact_no = ? WHERE user_id = ?");
        $stmt->execute([$newFirstname, $newLastname, $newContact, $userId]);
    }
    $_SESSION['success'] = "Profile updated successfully!";
    header("Location: faculty-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/faculty-dashboard.css">
</head>
<body>
    <!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="faculty-dashboard.php" id="editProfileForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="editFirstname" class="form-label">First Name</label>
            <input type="text" class="form-control" id="editFirstname" name="edit_firstname" value="<?php echo htmlspecialchars($faculty_user['firstname']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="editLastname" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="editLastname" name="edit_lastname" value="<?php echo htmlspecialchars($faculty_user['lastname']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="editContact" class="form-label">Contact No</label>
            <input type="text" class="form-control" id="editContact" name="edit_contact_no" value="<?php echo isset($faculty_user['contact_no']) ? htmlspecialchars($faculty_user['contact_no']) : ''; ?>" required>
          </div>
          <div class="mb-3">
            <label for="editCurrentPassword" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="editCurrentPassword" name="edit_current_password" placeholder="Enter current password to change password">
        </div>
          <div class="mb-3">
            <label for="editPassword" class="form-label">New Password</label>
            <input 
              type="password" 
              class="form-control" 
              id="editPassword" 
              name="edit_password" 
              placeholder="Leave blank to keep current password"
              pattern="^(?=.*[!@#$%^&*(),.?&quot;:{}|&lt;&gt;]).{8,}$"
              title="Password must be at least 8 characters and contain at least one special character.">
          </div>
          <div class="mb-3">
            <label for="editConfirmPassword" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="editConfirmPassword" name="edit_confirm_password" placeholder="Leave blank to keep current password">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" name="update_profile">Save Changes</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    var password = document.getElementById('editPassword').value;
    if (password.length > 0) {
        // Require at least 8 characters and at least one special character
        var specialCharRegex = /[!@#$%^&*(),.?":{}|<>]/;
        if (password.length < 8 || !specialCharRegex.test(password)) {
            alert('Password must be at least 8 characters and contain at least one special character.');
            e.preventDefault();
        }
    }
});
</script>
    <aside class="sidebar">
        <figure class="sidebar-header mt-4">
            <img src="../BukSU-Events/images/user.png" alt="user-picture">
            <figcaption>
                Welcome <?php echo htmlspecialchars($faculty_user['firstname']) . ' ' . htmlspecialchars($faculty_user['lastname']); ?>
            </figcaption>
            <button class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="fas fa-user-edit"></i> Edit Profile
            </button>
        </figure>
        <nav class="nav flex-column">
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search events...">
            </div>
            <a href="faculty-dashboard.php" class="nav-link active"><i class="fa-solid fa-dashboard"></i>Dashboard</a>
            <a href="faculty-registered-events.php" class="nav-link"><i class="fa-solid fa-check"></i>Registered Events</a>
            <a href="php-forms/inside-user-booking.php" class="nav-link"><i class="fas fa-calendar-check"></i>Book an Event</a>
            <a href="land-page.php" class="nav-link"><i class="fa fa-arrow-left"></i>Back to Home</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out"></i>Sign Out</a>
        </nav>
    </aside>

    <main class="main-content">
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Dashboard -->
    <div class="container mt-4">
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-sm-4 mb-2">
                <div class="card text-center shadow-lg border-0">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-1"><i class="fa-solid fa-calendar-check text-info me-2"></i>Pending Events</h6>
                        <span class="display-6 fw-bold text-info">
                            <?php
                                $presentCount = 0;
                                foreach ($pendingEvents as $event) {
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

    <!-- Pending Events -->
    <div class="dashboard-section pending-events shadow-lg container">
        <h2>
            <i class="fa-regular fa-clock me-1 text-info"></i> Pending Events
            <span class="badge badge-pending ms-2"><?php echo count($pendingEvents); ?></span>
        </h2>
        <div class="table-responsive">
            <table class="table dashboard-table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Venue</th>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Audience</th>
                        <th>Capacity</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pendingEvents) > 0): ?>
                        <?php foreach ($pendingEvents as $index => $event): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><strong><?php echo htmlspecialchars($event['event_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($event['venue']); ?></td>
                                <td>
                                    <span class="text-info">
                                        <?php echo date('M d, Y H:i', strtotime($event['event_date_time'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($event['event_type']); ?></td>
                                <td><?php echo htmlspecialchars($event['target_audience']); ?></td>
                                <td><?php echo htmlspecialchars($event['capacity']); ?></td>
                                <td>
                                    <?php if (!empty($event['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" style="max-width: 60px; max-height: 60px;">
                                    <?php else: ?>
                                        <span class="text-muted">No image</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No pending events found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Approved Events -->
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
                        <th>Event</th>
                        <th>Venue</th>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Image</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($approvedEvents) > 0): ?>
                        <?php foreach ($approvedEvents as $index => $event): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><strong><?php echo htmlspecialchars($event['event_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($event['venue']); ?></td>
                                <td>
                                    <span class="text-success">
                                        <?php echo date('M d, Y H:i', strtotime($event['event_date_time'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($event['event_type']); ?></td>
                                <td><?php echo htmlspecialchars($event['capacity']); ?></td>
                                <td>
                                    <?php if (!empty($event['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" style="max-width: 60px; max-height: 60px;">
                                    <?php else: ?>
                                        <span class="text-muted">No image</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button 
                                        class="btn btn-outline-primary btn-sm edit-btn"
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
                                    >
                                        <i class="fa fa-pen"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">No approved events found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="faculty-dashboard.php" id="editEventForm" enctype="multipart/form-data">
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
                            <input type="text" class="form-control" id="editVenue" name="venue" required readonly style="background-color: #f7f7f7;">
                        </div>
                        <div class="mb-3">
                            <label for="editDateTime" class="form-label">Date & Time</label>
                            <input type="datetime-local" class="form-control" id="editDateTime" name="event_date_time" required readonly style="background-color: #f7f7f7;">
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
                            <input type="number" class="form-control" id="editCapacity" name="capacity" required readonly style="background-color: #f7f7f7;">
                        </div>
                        <div class="mb-3">
                            <label for="editImage" class="form-label">Event Image</label>
                            <input type="file" class="form-control" id="editImage" name="image_path" accept="image/*">
                            <input type="hidden" name="current_image" id="currentImageInput">
                            <div id="currentImagePreview" class="mt-2">
                                <img id="currentImage" src="" alt="Current Event Image" style="max-width: 80px; max-height: 80px; display: none;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
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
                var dt = button.getAttribute('data-datetime');
                if (dt) {
                    var localDT = dt.replace(' ', 'T').slice(0, 16);
                    document.getElementById('editDateTime').value = localDT;
                } else {
                    document.getElementById('editDateTime').value = '';
                }
                document.getElementById('editType').value = button.getAttribute('data-type');
                document.getElementById('editAudience').value = button.getAttribute('data-audience');
                document.getElementById('editCapacity').value = button.getAttribute('data-capacity');
                var imagePath = button.getAttribute('data-image');
                var currentImage = document.getElementById('currentImage');
                var currentImageInput = document.getElementById('currentImageInput');
                if (imagePath) {
                    currentImage.src = imagePath;
                    currentImage.style.display = 'block';
                    currentImageInput.value = imagePath;
                } else {
                    currentImage.src = '';
                    currentImage.style.display = 'none';
                    currentImageInput.value = '';
                }
            });
        });
    });
    </script>

    <!-- Rejected Events -->
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
                        <th>Event</th>
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
                                <td><strong><?php echo htmlspecialchars($event['event_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($event['venue']); ?></td>
                                <td>
                                    <span class="text-danger">
                                        <?php echo date('M d, Y H:i', strtotime($event['event_date_time'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($event['event_type']); ?></td>
                                <td><?php echo htmlspecialchars($event['target_audience']); ?></td>
                                <td><?php echo htmlspecialchars($event['capacity']); ?></td>
                                <td>
                                    <?php if (!empty($event['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" style="max-width: 60px; max-height: 60px;">
                                    <?php else: ?>
                                        <span class="text-muted">No image</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo !empty($event['rejection_reason']) ? htmlspecialchars($event['rejection_reason']) : '<span class="text-muted">No reason provided</span>'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">No rejected events found.</td>
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