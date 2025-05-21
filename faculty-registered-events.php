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
    <title>faculty Dashboard</title>
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
            <input type="password" class="form-control" id="editPassword" name="edit_password" placeholder="Leave blank to keep current password">
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
            <a href="faculty-dashboard.php" class="nav-link"><i class="fa-solid fa-dashboard"></i>Dashboard</a>
            <a href="faculty-registered-events.php" class="nav-link active"><i class="fa-solid fa-check"></i>Registered Events</a>
            <a href="php-forms/inside-user-booking.php" class="nav-link"><i class="fas fa-calendar-check"></i>Book an Event</a>
            <a href="land-page.php" class="nav-link"><i class="fa fa-arrow-left"></i>Back to Home</a>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out"></i>Sign out</a>
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
                        <h6 class="card-title text-muted mb-1"><i class="fa-solid fa-calendar-check text-primary me-2"></i>Total Registered Events</h6>
                        <span class="display-6 fw-bold text-primary">
                            <?php echo count($registeredEvents); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 mb-2">
                <div class="card text-center shadow-lg border-0">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-1"><i class="fa-solid fa-user-check text-success me-2"></i>Presents</h6>
                        <span class="display-6 fw-bold text-success">
                            <?php
                                $presentCount = 0;
                                foreach ($registeredEvents as $event) {
                                    if ($event['attendance_status'] === 'present') $presentCount++;
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
                        <h6 class="card-title text-muted mb-1"><i class="fa-solid fa-user-xmark text-danger me-2"></i>Absents</h6>
                        <span class="display-6 fw-bold text-danger">
                            <?php
                                $absentCount = 0;
                                foreach ($registeredEvents as $event) {
                                    if ($event['attendance_status'] === 'absent') $absentCount++;
                                }
                                echo $absentCount;
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registered Events Table -->
    <div class="registered-events container mt-5">
        <h2 class="text-center mb-4 fw-bold text-primary">My Registered Events</h2>
        <div class="row justify-content-center">
            <?php if (count($registeredEvents) > 0): ?>
                <?php foreach ($registeredEvents as $event): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex align-items-stretch">
                        <div class="card shadow-lg h-100 border-0 event-card-hover" style="transition: transform 0.2s, box-shadow 0.2s;">
                            <?php if (!empty($event['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($event['image_path']); ?>" class="card-img-top rounded-top" alt="<?php echo htmlspecialchars($event['event_name']); ?>" style="height:180px;object-fit:cover;">
                            <?php else: ?>
                                <img src="../BukSU-Events/images/default-event.png" class="card-img-top rounded-top" alt="Default Event" style="height:180px;object-fit:cover;">
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-2 text-primary">
                                    <?php echo htmlspecialchars($event['event_name']); ?>
                                </h5>
                                <ul class="list-unstyled mb-3 small">
                                    <li><i class="fa-solid fa-calendar-days me-2 text-secondary"></i><strong>Date:</strong> <?php echo date('M d, Y', strtotime($event['event_date_time'])); ?></li>
                                    <li><i class="fa-solid fa-location-dot me-2 text-secondary"></i><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></li>
                                    <li><i class="fa-solid fa-tag me-2 text-secondary"></i><strong>Type:</strong> <?php echo htmlspecialchars($event['event_type']); ?></li>
                                    <li><i class="fa-solid fa-users me-2 text-secondary"></i><strong>Audience:</strong> <?php echo htmlspecialchars($event['target_audience']); ?></li>
                                    <li><i class="fa-solid fa-clock me-2 text-secondary"></i><strong>Date Registered:</strong> <?php echo htmlspecialchars($event['attendance_date']); ?></li>
                                </ul>
                                <div class="mt-auto">
                                    <span class="fw-semibold">Status:</span>
                                    <?php
                                        if ($event['attendance_status'] === 'present') {
                                            echo '<span class="badge bg-success ms-1">Present</span>';
                                        } elseif ($event['attendance_status'] === 'absent') {
                                            echo '<span class="badge bg-danger ms-1">Absent</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary ms-1">' . htmlspecialchars($event['attendance_status']) . '</span>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center mt-4">
                        You have not registered for any events yet.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <style>
        .event-card-hover:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 8px 24px rgba(0,0,0,0.18), 0 1.5px 4px rgba(0,0,0,0.10);
            z-index: 2;
        }
    </style>
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