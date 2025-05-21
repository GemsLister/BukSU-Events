<?php
session_start();
include '../db.php';

if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    die("Error: Invalid event ID.");
}

$event_id = $_GET['event_id'];

// Fetch event
$stmtEvent = $pdo->prepare("SELECT * FROM events WHERE event_id = ? AND status = 'approved'");
$stmtEvent->execute([$event_id]);
$event = $stmtEvent->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Error: Event not found or not approved.");
}

$registrationMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmtUser = $pdo->prepare("SELECT user_id, password, roles FROM users WHERE email = ?");
    $stmtUser->execute([$email]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $user_id = $user['user_id'];
        $userRoleLower = strtolower(trim($user['roles']));
        $eventAudienceLower = strtolower(trim($event['target_audience']));

        $allowed = false;
        if (strpos($eventAudienceLower, 'all') !== false) {
            $allowed = true;
        } elseif (strpos($eventAudienceLower, 'faculty') !== false && $userRoleLower === 'faculty') {
            $allowed = true;
        } elseif (strpos($eventAudienceLower, 'student') !== false && $userRoleLower === 'student') {
            $allowed = true;
        }

        if ($allowed) {
            $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM attendees WHERE event_id = ? AND user_id = ?");
            $stmtCheck->execute([$event_id, $user_id]);
            $alreadyRegistered = $stmtCheck->fetchColumn();

            if ($alreadyRegistered > 0) {
                $registrationMessage = "<div class='alert alert-warning'>You are already marked as attending this event.</div>";
            } else {
                $stmtInsert = $pdo->prepare("INSERT INTO attendees (event_id, user_id, attendance_date, roles) VALUES (?, ?, NOW(), ?)");
                $stmtInsert->execute([$event_id, $user_id, $user['roles']]);

                if ($stmtInsert->rowCount() > 0) {
                    $registrationMessage = "<div class='alert alert-success'>Your attendance for <strong>" . htmlspecialchars($event['event_name']) . "</strong> has been recorded.</div>";
                } else {
                    $registrationMessage = "<div class='alert alert-danger'>Error recording your attendance. Please try again.</div>";
                }
            }
        } else {
            $registrationMessage = "<div class='alert alert-danger'>This event is not for your audience group.</div>";
        }
    } else {
        $registrationMessage = "<div class='alert alert-danger'>Invalid email or password. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="forms-styles/attendance-form.css">
</head>
<body>
    <header class="d-none d-lg-flex">
    </header>

    <main class="container">
        <!-- Mobile Form -->
        <form action="attendance-form.php?event_id=<?php echo $event_id; ?>" method="POST" class="d-lg-none mb-4">
            <div class="title mb-3">
                <h1>Attendance</h1>
                <?php echo $registrationMessage; ?>
            </div>
            <div class="inputs mb-3">    
                <input type="email" placeholder="Email address" class="form-control mb-2" name="email" required>
                <input type="password" placeholder="Password" class="form-control mb-2" name="password" required>
                <button type="submit" id="submitBtn" class="btn btn-primary w-100">Mark Attendance</button>
            </div>
            <a href="land-page.php" id="submitBtn" class="btn btn-secondary w-100">Back to Events</a>
        </form>

        <figure class="d-none d-lg-flex">
            <img src="../images/form_logo.png" class="form-logo" alt="logo">
        </figure>
        <!-- Event Info Card -->
        <div class="sideposter d-flex">
            <div class="event-img">
                <?php
                $imagePath = !empty($event['image_path']) ? '../' . htmlspecialchars($event['image_path']) : '../BukSU-Events/images/default-event.jpg';
                ?>
                <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($event['event_name']); ?>">
            </div>
            <div class="card mb-4 mx-auto">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($event['event_name']); ?></h5>
                    <p class="card-text">
                        <strong>Date and Time:</strong> <?php echo date('M d, Y h:i A', strtotime($event['event_date_time'])); ?><br>
                        <strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?><br>
                        <strong>Type:</strong> <?php echo htmlspecialchars($event['event_type']); ?><br>
                        <strong>Audience:</strong> <?php echo htmlspecialchars($event['target_audience']); ?><br>
                        <strong>Description:</strong> <?php echo htmlspecialchars(substr($event['description'], 0, 1000)); ?>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <aside>
        <form action="attendance-form.php?event_id=<?php echo $event_id; ?>" method="POST" class="d-none d-lg-flex flex-column align-items-center">
            <div class="title mb-3">
                <h1>Registration Form</h1>
                <?php echo $registrationMessage; ?>
            </div>
            <div class="inputs">    
                <input type="email" placeholder="Email address" class="form-control mb-2" name="email" required>
                <input type="password" placeholder="Password" class="form-control mb-2" name="password" required>
                <button type="submit" id="submitBtn" class="btn btn-primary w-100 mb-2">Mark Attendance</button>
                <a href="../land-page.php" id="submitBtn" class="btn btn-secondary w-100">Back to Events</a>
            </div>
        </form>
    </aside>

    <footer class="d-lg-none mt-5 text-center">
        <p>&copy; 2025 Balolong Inc.</p>
    </footer>
</body>
</html>
