<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <!-- <link rel="stylesheet" href="../BukSU-Events//bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
     -->
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/sign-in.css">
</head>
<body>
    <!-- header section -->
    <header class="container-fluid d-flex">
    </header>

    <!-- main content -->
    <main class="container-fluid d-flex h-100">
        <!-- sign-in form -->
        <form name="sign-in-form" action="sign-in-validate.php" method="POST" class="d-flex login-form">
            <!-- sign-in title (large size) -->
            <div class="container-fluid sign-in-large d-none d-lg-flex d-xl-flex d-xxl-flex">
                <div class="form-wordlines">
                    <h1>Sign in</h1>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eos ea accusantium neque libero earum </p>
                </div>
                <p>Don't have an account? <a href="../BukSU-Events/sign-up.php">Sign up</a></p>
                <!-- success and error message  -->
                <?php if(isset($_SESSION['success'])): ?>
                    <h5 class="success-message">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </h5>
                <?php elseif (isset($_SESSION['error'])): ?>
                    <h5 class="warning-message">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <!-- registration inputs -->
            <div class="container-fluid inputs d-flex d-lg-flex d-xxl-flex">
                <!-- sign-in title (small size) --> 
                <div class="container-fluid sign-in-small d-flex d-lg-none d-xl-none d-xxl-none">
                    <h1>Sign in</h1>
                    <p>Don't have an account? <a href="../BukSU-Events/sign-up.php">Sign up</a></p>
                    <!-- success and error message  -->
                    <?php if(isset($_SESSION['success'])): ?>
                        <h5 class="success-message">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </h5>
                    <?php elseif (isset($_SESSION['error'])): ?>
                        <h5 class="warning-message">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </h5>
                    <?php endif; ?>
                </div>
                <!-- for email-address -->
                <div class="input-group">
                    <input type="email" class="form-control" placeholder="Email address" name="email" aria-label="Email address" aria-describedby="email-addon1" required>
                </div>
                <!-- for password -->
                <div class="input-group">
                    <input type="password" class="form-control" placeholder="Password" name="password" aria-label="Password" aria-describedby="password-addon1" required>
                </div>
                <a href="../BukSU-Events/forgot-password.php">Forgot password</a>
                <button type="submit" method="POST" class="btn btn-primary w-100 mt-3">Sign in</button>
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
    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>