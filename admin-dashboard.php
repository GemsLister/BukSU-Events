<?php
// filepath: e:\xampp\htdocs\BukSU-Events\scratches\admin-dashboard.php

// Include the database connection file
require_once '../db.php'; // Adjust the path based on your file structure

// Fetch pending events from the events table
$sql = "SELECT event_id, event_name, event_date, status, venue FROM events WHERE status = 'pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/admin-dashboard.css">
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <a href="#" class="close-btn" onclick="closeSidebar()">&times;</a>
        <a href="#">Dashboard</a>
        <a href="#">Events</a>
        <a href="#">Logout</a>
    </aside>
    <header class="container-fluid">
        <figure class="d-flex">
            <img src="../BukSU-Events/images/buksu_events_logo.png" alt="web-logo" class="img-fluid">            
        </figure>
        <!-- for small screens -->
        <figure class="d-flex">
            <a href="#" onclick="openSidebar()"><i class="fas fa-bars"></i></a>
        </figure>
    </header>
    
    <main class="container-fluid">
        <section class="pending-events">
            <h2>Pending Events</h2>
            <div id="events-container">
                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='event-item'>";
                            echo "<h3>" . htmlspecialchars($row['event_name']) . "</h3>";
                            echo "<p>Date: " . htmlspecialchars($row['event_date']) . "</p>";
                            echo "<p>Venue: " . htmlspecialchars($row['venue']) . "</p>";
                            echo "<p>Status: " . htmlspecialchars($row['status']) . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No pending events.</p>";
                    }
                ?>
            </div>
        </section>
    </main>
    <script src="../BukSU-Events/jquery3.7.1.js"></script>
    <script src="../BukSU-Events/script.js"></script>
</body>
</html>