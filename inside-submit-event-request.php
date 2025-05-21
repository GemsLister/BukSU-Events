<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_date_time = $_POST['event_date_time'];
    $event_type = $_POST['event_type'];
    $target_audience = $_POST['target_audience'];
    $event_venue = $_POST['event_venue'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];

    // Check if the user is logged in and their ID is in the session
    if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $image_path = null; // Initialize image path

        // Handle image upload if a file was selected
        if (isset($_FILES['event_image'])) {
            if ($_FILES['event_image']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($_FILES['event_image']['type'], $allowed_types)) {
                    $upload_dir = 'uploads/'; // Ensure this path is correct
                    // Sanitize filename for security
                    $filename = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['event_image']['name']);
                    $destination = $upload_dir . $filename;

                    if (move_uploaded_file($_FILES['event_image']['tmp_name'], $destination)) {
                        $image_path = $destination;
                    } else {
                        $_SESSION['error'] = 'Error moving uploaded file.';
                        header('Location: php-forms/inside-user-booking.php');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.';
                    header('Location: php-forms/inside-user-booking.php');
                    exit();
                }
            } elseif ($_FILES['event_image']['error'] != 4) { // Error other than no file uploaded
                $_SESSION['error'] = 'Error uploading image. Error code: ' . $_FILES['event_image']['error'];
                header('Location: php-forms/inside-user-booking.php');
                exit();
            }
            // If $_FILES['event_image']['error'] == 4, it means no file was uploaded, so $image_path remains null, which is fine.
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO events (user_id, event_name, event_date_time, event_type, target_audience, venue, capacity, description, image_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $event_name, $event_date_time, $event_type, $target_audience, $event_venue, $capacity, $description, $image_path, 'pending']);

            $_SESSION['success'] = 'Event request submitted successfully!';
            header('Location: php-forms/inside-user-booking.php');
            exit();

        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error submitting event request: ' . $e->getMessage();
            header('Location: php-forms/inside-user-booking.php');
            exit();
        }

    } else {
        $_SESSION['error'] = 'You must be logged in to submit an event request.';
        header('Location: ../sign-in.php'); // Redirect to login page
        exit();
    }

} else {
    header('Location: php-forms/inside-user-booking.php');
    exit();
}
?>