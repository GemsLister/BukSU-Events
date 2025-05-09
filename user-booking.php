<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/user-booking.css">
</head>
<body>
    <!-- header section -->
    <header class="container-fluid d-flex">
    </header>

    <!-- main content -->
    <main class="container-fluid d-flex h-100">
        <!-- booking form -->
        <form name="booking-form" action="submit-event-request.php" method="POST" class="d-flex login-form">
            <div class="container-fluid booking-large d-flex">
                <div class="form-wordlines">
                    <h1>Request an Event</h1>
                    <p>Plan your event effortlessly—submit your request now!</p>
                    <!-- Success message -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <h5 class="success-msg text-success">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </h5>
                    <?php endif; ?>
                    <!-- Error message -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <h5 class="error-msg text-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </h5>
                    <?php endif; ?>
                </div>
                <a href="land-page.php" class="nav-link">Return to Landing Page</a>
            </div>
            <!-- booking inputs -->
            <div class="container-fluid inputs d-flex d-lg-flex d-xxl-flex">
                <!-- event name -->
                <div class="event-name d-flex gap-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Event name" name="event_name" required>
                    </div>
                </div>
                <!-- event date and time -->
                <div class="event-date d-flex gap-3">
                    <div class="input-group">
                        <input type="datetime-local" class="form-control" name="event_date_time" 
                               min="<?php echo date('Y'); ?>-01-01T00:00" 
                               max="<?php echo date('Y'); ?>-12-31T23:59" 
                               required>
                    </div>
                </div>
                <!-- event type -->
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="e.g., Seminar, Workshop" name="event_type" required>
                </div>
                <!-- target audience -->
                <div class="input-group">
                    <select class="form-select" id="target_audience" name="target_audience" required>
                        <option value="All Faculty and Students">All Faculty and Students</option>
                        <option value="Faculty Only">Faculty Only</option>
                        <option value="Students Only">Students Only</option>
                    </select>
                </div>
                <!-- event venue -->
                <div class="input-group">
                    <select class="form-select" id="event_venue" name="event_venue" required>
                        <option value="Auditorium">Auditorium</option>
                        <option value="Gymnasium">Gymnasium</option>
                        <option value="Museum">Museum</option>
                        <option value="Theatre">Theatre</option>
                    </select>
                </div>
                <!-- event mode -->
                <div class="input-group">
                    <select class="form-select" id="event_mode" name="event_mode" required>
                        <option value="In-Person">In-Person</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Virtual">Virtual</option>
                    </select>
                </div>
                <!-- capacity -->
                <div class="input-group mb-2">
                    <input type="number" class="form-control" placeholder="e.g., 100" id="capacity" name="capacity" min="1" required>
                </div>
                <!-- event description -->
                <div class="input-group mb-2">
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Provide a brief description of the event..." required></textarea>
                </div>
                <button type="submit" method="POST" class="btn btn-primary w-100">Submit Request</button>
            </div>
        </form>
    </main>

    <!-- footer section -->
    <footer class="container-fluid d-flex w-100">
        <!-- copyrights -->
        <div class="copyrights">
            <p>Copyright &copy; 2025 Balolong Inc.</p>
        </div>
    </footer>
    <script src="../BukSU-Events/bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>