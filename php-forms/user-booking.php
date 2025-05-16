<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Event</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="forms-styles/form-styles.css">
    <style>
        /* Optional: Adjust styling for the file input */
        .form-label {
            margin-top: 1rem;
            display: block;
        }
    </style>
</head>
<body>
    <main>
        <form action="../submit-event-request.php" method="POST" class="d-lg-none" enctype="multipart/form-data">
            <div class="title">
                <h1>Request Event</h1>
            </div>
            <div class="inputs mt-4">
                <input type="text" class="form-control" placeholder="Event name" name="event_name" required>

                <input type="datetime-local" class="form-control" name="event_date_time"
                        min="<?php echo date('Y-m-d\TH:i'); ?>"
                        max="<?php echo date('Y'); ?>-12-31T23:59"
                        required>

                <input type="text" class="form-control" placeholder="e.g., Seminar, Workshop" name="event_type" required>

                <select class="form-select" id="target_audience" name="target_audience" required>
                        <option value="All Faculty and Students">All Faculty and Students</option>
                        <option value="Faculty Only">Faculty Only</option>
                        <option value="Students Only">Students Only</option>
                    </select>

                <input class="form-control" id="event_venue" name="event_venue" placeholder="Enter venue" required>

                <select class="form-select" id="event_mode" name="mode" required>
                    <option value="In-Person">In-Person</option>
                    <option value="Hybrid">Hybrid</option>
                    <option value="Virtual">Virtual</option>
                </select>

                <input type="number" class="form-control" placeholder="e.g., 100" id="capacity" name="capacity" min="1" required>

                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Provide a brief description of the event..." required></textarea>

                <label for="event_image" class="form-label">Event Image (Optional)</label>
                <input type="file" class="form-control" id="event_image" name="event_image" accept="image/*">
                <small class="text-muted">Supported formats: JPG, JPEG, PNG, GIF.</small>

                <button type="submit" id="submitBtn" method="POST" class="btn btn-primary w-100 mt-3">Submit Request</button>
            </div>
        </form>

        <figure class="d-none d-lg-flex">
            <img src="../images/form_logo.png" class="form-logo" alt="logo">
            <div class="copyrights" class="d-none d-lg-flex">
                <p>Copyright &copy; 2025 Balolong Inc.</p>
            </div>
        </figure>
    </main>

    <aside>
        <form action="../submit-event-request.php" method="POST" class="d-none d-lg-flex" enctype="multipart/form-data">
            <div class="title">
                <h1>Request Event</h1>
                <?php if (isset($_SESSION['success'])): ?>
                    <h5 class="success-msg text-success">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </h5>
                    <a href="../land-page.php">Back to landing page</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <h5 class="error-msg text-danger">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <div class="inputs mt-4">
                <input type="text" class="form-control" placeholder="Event name" name="event_name" required>

                <input type="datetime-local" class="form-control" name="event_date_time"
                        min="<?php echo date('Y-m-d\TH:i'); ?>"
                        max="<?php echo date('Y'); ?>-12-31T23:59"
                        required>

                <input type="text" class="form-control" placeholder="e.g., Seminar, Workshop" name="event_type" required>

                <select class="form-select" id="target_audience" name="target_audience" required>
                        <option value="All Faculty and Students">All Faculty and Students</option>
                        <option value="Faculty Only">Faculty Only</option>
                        <option value="Students Only">Students Only</option>
                    </select>

                <input class="form-control" id="event_venue" name="event_venue" placeholder="Enter venue" required>

                <select class="form-select" id="event_mode" name="mode" required>
                    <option value="In-Person">In-Person</option>
                    <option value="Hybrid">Hybrid</option>
                    <option value="Virtual">Virtual</option>
                </select>

                <input type="number" class="form-control" placeholder="e.g., 100" id="capacity" name="capacity" min="1" required>

                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Provide a brief description of the event..." required></textarea>

                <label for="event_image" class="form-label">Event Image (Optional)</label>
                <input type="file" class="form-control" id="event_image" name="event_image" accept="image/*">
                <small class="text-muted">Supported formats: JPG, JPEG, PNG, GIF.</small>

                <button type="submit" id="submitBtn" method="POST" class="btn btn-primary w-100 mt-3">Submit Request</button>
            </div>
        </form>
    </aside>

    <footer class="d-lg-none">
        <p>Copyright &copy; 2025 Balolong Inc.</p>
    </footer>

    <script>
        const dateTimeInputs = document.querySelectorAll('input[name="event_date_time"]');
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

        dateTimeInputs.forEach(input => {
            input.min = minDateTime;
        });
    </script>
</body>
</html>