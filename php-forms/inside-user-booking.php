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
        .form-label {
            margin-top: 1rem;
            display: block;
        }
    </style>
</head>
<body>
    <main>
        <form action="../inside-submit-event-request.php" method="POST" class="d-lg-none" enctype="multipart/form-data">
            <div class="title">
                <h1>Request Event</h1>
            </div>
            <div class="inputs mt-4">
                <input type="text" class="form-control" placeholder="Event name" name="event_name" maxlength="50" required>

                <input type="datetime-local" class="form-control" name="event_date_time"
                        min="<?php echo date('Y-m-d\TH:i'); ?>"
                        max="<?php echo date('Y'); ?>-12-31T23:59"
                        required>

                <!-- Event Type Select -->
                <select class="form-select mb-2" id="event_type_select" name="event_type_select" onchange="toggleEventTypeInput(this.value)" required>
                    <option value="">Select Event Type</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Conference">Conference</option>
                    <option value="Sports Event">Sports Event</option>
                    <option value="Cultural Event">Cultural Event</option>
                    <option value="Training">Training</option>
                    <option value="Meeting">Meeting</option>
                    <option value="Others">Others (Specify)</option>
                </select>
                <input type="text" class="form-control mb-2" id="event_type_other" name="event_type" placeholder="Specify event type" style="display:none;">

                <select class="form-select mb-2" id="target_audience" name="target_audience" required>
                    <option value="All Faculty and Students">All Faculty and Students</option>
                    <option value="Faculty Only">Faculty Only</option>
                    <option value="Students Only">Students Only</option>
                </select>

                <!-- Venue Select -->
                <select class="form-select mb-2" id="event_venue_select" name="event_venue_select" onchange="toggleVenueInput(this.value)" required>
                    <option value="">Select Venue</option>
                    <option value="Gymnasium">Gymnasium (2500 capacity)</option>
                    <option value="Auditorium">Auditorium (400 capacity)</option>
                    <option value="Museum">Museum (50 capacity)</option>
                    <option value="ESL Stage">ESL Stage (200 capacity)</option>
                    <option value="Mini Theatre">Mini Theatre (200 capacity)</option>
                    <option value="COB Quadrangle">COB Quadrangle (200 capacity)</option>
                    <option value="Learning Commons">Learning Commons (50 capacity)</option>
                    <option value="Others">Others (Specify)</option>
                </select>
                <input type="text" class="form-control mb-2" id="event_venue_other" name="event_venue" placeholder="Specify venue" style="display:none;">

                <input type="number" class="form-control" placeholder="Enter expected number of attendees" id="capacity" name="capacity" min="1" required>

                <textarea class="form-control" id="description" name="description" rows="4" maxlength="500" placeholder="Provide a brief description of the event (max 500 characters)..." required></textarea>

                <label for="event_image" class="form-label">Event Image (Optional)</label>
                <input type="file" class="form-control" id="event_image" name="event_image" accept="image/*">
                <small class="text-muted">Supported formats: JPG, JPEG, PNG, GIF.</small>

                <button type="submit" id="submitBtn" method="POST" class="btn btn-primary w-100 mt-3">Submit Request</button>
            </div>
        </form>

        <figure class="d-none d-lg-flex flex-column align-items-center">
            <img src="../images/form_logo.png" class="form-logo mb-3" alt="logo">
            <div class="copyrights">
                <p>Copyright &copy; 2025 Balolong Inc.</p>
            </div>
        </figure>
        <!-- Back Button  -->
        <div class="d-none d-lg-flex justify-content-center mb-3">
            <a href="../faculty-dashboard.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </main>

    <aside>
        <form action="../inside-submit-event-request.php" method="POST" class="d-none d-lg-flex" enctype="multipart/form-data">
            <div class="title">
                <h1>Request Event</h1>
                <?php if (isset($_SESSION['success'])): ?>
                    <h5 class="success-msg text-success">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </h5>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <h5 class="error-msg text-danger">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <div class="inputs mt-4">
                <input type="text" class="form-control" placeholder="Event name" name="event_name" maxlength="50" required>

                <input type="datetime-local" class="form-control" name="event_date_time"
                        min="<?php echo date('Y-m-d\TH:i'); ?>"
                        max="<?php echo date('Y'); ?>-12-31T23:59"
                        required>

                <!-- Event Type Select -->
                <select class="form-select mb-2" id="event_type_select_desktop" name="event_type_select" onchange="toggleEventTypeInputDesktop(this.value)" required>
                    <option value="">Select Event Type</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Conference">Conference</option>
                    <option value="Sports Event">Sports Event</option>
                    <option value="Cultural Event">Cultural Event</option>
                    <option value="Training">Training</option>
                    <option value="Meeting">Meeting</option>
                    <option value="Others">Others (Specify)</option>
                </select>
                <input type="text" class="form-control mb-2" id="event_type_other_desktop" name="event_type" placeholder="Specify event type" style="display:none;">

                <select class="form-select mb-2" id="target_audience_desktop" name="target_audience" required>
                    <option value="All Faculty and Students">All Faculty and Students</option>
                    <option value="Faculty Only">Faculty Only</option>
                    <option value="Students Only">Students Only</option>
                </select>

                <!-- Venue Select -->
                <select class="form-select mb-2" id="event_venue_select_desktop" name="event_venue_select" onchange="toggleVenueInputDesktop(this.value)" required>
                    <option value="">Select Venue</option>
                    <option value="Gymnasium">Gymnasium (2500 capacity)</option>
                    <option value="Auditorium">Auditorium (400 capacity)</option>
                    <option value="Museum">Museum (50 capacity)</option>
                    <option value="ESL Stage">ESL Stage (200 capacity)</option>
                    <option value="Mini Theatre">Mini Theatre (200 capacity)</option>
                    <option value="COB Quadrangle">COB Quadrangle (200 capacity)</option>
                    <option value="Learning Commons">Learning Commons (50 capacity)</option>
                    <option value="Others">Others (Specify)</option>
                </select>
                <input type="text" class="form-control mb-2" id="event_venue_other_desktop" name="event_venue" placeholder="Specify venue" style="display:none;">

                <input type="number" class="form-control" placeholder="Enter expected number of attendees" id="capacity_desktop" name="capacity" min="1" required>

                <textarea class="form-control" id="description_desktop" name="description" rows="4" maxlength="500" placeholder="Provide a brief description of the event (max 500 characters)..." required></textarea>

                <label for="event_image" class="form-label">Event Image (Optional)</label>
                <input type="file" class="form-control" id="event_image_desktop" name="event_image" accept="image/*">
                <small class="text-muted">Supported formats: JPG, JPEG, PNG, GIF.</small>

                <button type="submit" id="submitBtnDesktop" method="POST" class="btn btn-primary w-100 mt-3">Submit Request</button>
            </div>
        </form>
        
    </aside>

    <footer class="d-lg-none">
        <p>Copyright &copy; 2025 Balolong Inc.</p>
    </footer>

    <script>
        // Event Type toggle for mobile
        function toggleEventTypeInput(value) {
            var otherInput = document.getElementById('event_type_other');
            if (value === 'Others') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
                otherInput.value = value;
            }
        }
        // Event Type toggle for desktop
        function toggleEventTypeInputDesktop(value) {
            var otherInput = document.getElementById('event_type_other_desktop');
            if (value === 'Others') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
                otherInput.value = value;
            }
        }
        // Venue toggle for mobile
        function toggleVenueInput(value) {
            var otherInput = document.getElementById('event_venue_other');
            if (value === 'Others') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
                otherInput.value = value;
            }
        }
        // Venue toggle for desktop
        function toggleVenueInputDesktop(value) {
            var otherInput = document.getElementById('event_venue_other_desktop');
            if (value === 'Others') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
                otherInput.value = value;
            }
        }

        // Set min datetime for all event_date_time inputs
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