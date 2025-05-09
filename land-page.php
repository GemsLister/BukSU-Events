<?php
// filepath: e:\xampp\htdocs\BukSU-Events\land-page.php

session_start();
include 'db.php'; // Include the database connection file

// Fetch approved events
$stmt = $pdo->prepare("SELECT * FROM events WHERE status = 'approved' ORDER BY event_date_time ASC");
$stmt->execute();
$approvedEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukSU Events - Landing Page</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/land-page.css">
</head>
<body>
    <!-- Header Section -->
    <header class="text-white py-4">
        <div class="container d-flex justify-content-between align-items-center">
            <img src="../BukSU-Events/images/buksu_events_logo.png" alt="BukSU Events Logo" class="logo">
            
            <!-- Mobile Menu Icon -->
            <div class="menu-icon d-flex d-lg-none">
                <i class="fas fa-bars fa-2x" id="mobile-menu-icon"></i>
            </div>

            <!-- Navigation Menu -->
            <nav class="d-none d-lg-flex">
                <ul class="nav">
                    <li class="nav-item"><a href="#" class="nav-link text-white">Home</a></li>
                    <li class="nav-item"><a href="#upcoming-events" class="nav-link text-white">Events</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">About Us</a></li>
                    <div class="sign-in-buttons d-flex align-items-center gap-3">
                    <a href="faculty-sign-in.php" class="btn btn-outline-light d-none d-lg-flex">Faculty Dashboard</a>
                    <a href="admin-sign-in.php" class="btn btn-outline-light d-none d-lg-flex">Admin Sign-in</a>
                </ul>
            </nav>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div class="mobile-menu bg-primary text-white d-none" id="mobile-menu">
            <ul class="nav flex-column text-center">
                <li class="nav-item"><a href="#" class="nav-link text-white">Home</a></li>
                <li class="nav-item"><a href="#upcoming-events" class="nav-link text-white">Events</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white">About Us</a></li>
            </ul>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero text-center py-5 d-flex">
            <div class="carousel-container container d-flex flex-lg-row">
                <!-- Carousel -->
                <div class="carousel-large mb-4 mb-lg-0 me-lg-4">
                    <div id="carousel-outer" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <figure class="carousel-item active">
                                <img src="../BukSU-Events/images/connect.png" class="d-block w-100" alt="...">
                            </figure>
                            <figure class="carousel-item">
                                <img src="../BukSU-Events/images/enjoy.png" class="d-block w-100" alt="...">
                            </figure>
                            <figure class="carousel-item">
                                <img src="../BukSU-Events/images/innovate.png" class="d-block w-100" alt="...">
                            </figure>
                        </div>
                    </div>
                </div>

                <!-- BukSU Events Tagline -->
                <div class="tagline text-center text-lg-start">
                    <h1 class="display-4">Welcome to BukSU Events</h1>
                    <p class="lead">Connect, Enjoy, and Innovate in Exciting Events at BukSU!</p>
                    <a href="sign-in.php" class="explore-events btn btn-primary btn-lg">Book a Demo (Faculty)</a>
                    <a href="sign-up.php" class="explore-events btn btn-primary btn-lg">Sign up</a>
                </div>
            </div>
        </section>

        <!-- Upcoming Events Section -->
        <section id="upcoming-events" class="upcoming-events py-5">
            <div class="upcoming-events-container container">
                <h2 class="text-center mb-4">Events</h2>
                <div class="row">
                    <?php if (count($approvedEvents) > 0): ?>
                        <?php foreach ($approvedEvents as $event): ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                <a class="card h-100" href="attendance-form.php?event_id=<?php echo $event['event_id']; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($event['event_name']); ?></h5>
                                        <p class="card-text">
                                            <strong>Date:</strong> <?php echo date('M d, Y', strtotime($event['event_date_time'])); ?><br>
                                            <strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?><br>
                                            <strong>Type:</strong> <?php echo htmlspecialchars($event['event_type']); ?><br>
                                            <strong>Audience:</strong> <?php echo htmlspecialchars($event['target_audience']); ?><br>
                                            <strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?>
                                        </p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No approved events available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer class="text-white py-4">
        <div class="container text-center">
            <img src="../BukSU-Events/images/buksu_events_logo.png" alt="BukSU Events Logo" class="mb-3">
            <p>&copy; <?php echo date('Y'); ?> BukSU Events. All rights reserved.</p>
            <div class="social-icons">
                <a href="#" class="text-white mx-2"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </footer>

    <script src="../BukSU-Events/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Mobile Menu
        const menuIcon = document.getElementById('mobile-menu-icon');
        const mobileMenu = document.getElementById('mobile-menu');

        menuIcon.addEventListener('click', () => {
            mobileMenu.classList.toggle('d-none');
        });
    </script>
</body>
</html>