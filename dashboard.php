<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/dashboard.css">
</head>
<body class="d-grid">
    <!-- header section -->
    <header class="container-fluid d-flex">
        <figure>
            <!-- website lgo -->
            <img src="../BukSU-Events/images/buksu_events_logo.png" alt="buksu_events_logo" class="img-fluid">
        </figure>
        <figure>
            <!-- menu icon -->
            <a href="#" id="menu-icon"><i class="fas fa-bars"></i></a>
        </figure>
        <!-- for larger screens -->
        <nav class="d-none d-lg-flex d-xl-flex d-xxl-flex">
            <ul class="nav-list d-lg-flex d-xl-flex d-xxl-flex">
                <li><a href="">Event History</a></li>
                <li><a href="">Saved Events</a></li>
                <li><a href="../BukSU-Events/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- for small screens sidebar -->
     <aside id="small-sidebar" class="small-nav">
        <a href="#" class="close-btn" id="close-sidebar"><i class="fa fa-times"></i></a>
        <nav>
            <ul class="d-flex flex-column gap-2">
                <li><a href="">Event History</a></li>
                <li><a href="">Saved Events</a></li>
                <li><a href="../BukSU-Events/sign-in.php">Logout</a></li>
            </ul>
        </nav>
     </aside>

     

     <!-- main content -->
     <main>

     </main>

     <!-- footer section -->
      <footer class="d-flex">
        <div class="copyrights">
            <p>&copy; 2025 BukSU Events. All rights reserved.</p>
        </div>
      </footer>
     <script src="../BukSU-Events/jquery3.7.1.js"></script>
     <script src="../BukSU-Events/script.js"></script>
</body>
</html>