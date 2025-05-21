<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="forms-styles/form-styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <main>
        <form action="../admin-sign-in-validate.php" method="POST" class="d-lg-none">
            <div class="title">
                <h1>Admin Sign in</h1>
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
            <!-- inputs container -->
            <div class="inputs">
                <input type="email" class="form-control" placeholder="Email address" name="email" aria-label="Email address" aria-describedby="email-addon1" required>

                <input type="password" class="form-control" placeholder="Password" name="password" aria-label="Password" aria-describedby="password-addon1" required>

                <button type="submit" method="POST" id=submitBtn class="btn btn-primary w-100 mt-4 mb-2">Sign in</button>
                <a class="forgot-password mt-4" href="../BukSU-Events/forgot-password.php">Forgot password?</a>
            </div>
        </form>

        <figure class="d-none d-lg-flex">
            <img src="../images/form_logo.png" class="form-logo" alt="logo">
            <!-- copyrights container -->
            <div class="copyrights" class="d-none d-lg-flex">
                <p>Copyright &copy; 2025 Balolong Inc.</p>
            </div>
        </figure>
    </main>

    <aside>
        <form action="../admin-sign-in-validate.php" method="POST" class="d-none d-lg-flex">
            <div class="title">
                <h1>Admin Sign in</h1>
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
             <!-- inputs container -->
            <div class="inputs">
                <input type="email" class="form-control mt-3 mb-3" placeholder="Email address" name="email" aria-label="Email address" aria-describedby="email-addon1" required>

                <input type="password" class="form-control" placeholder="Password" name="password" aria-label="Password" aria-describedby="password-addon1" required>

                <div class="g-recaptcha w-100" data-sitekey="6LdAVjYrAAAAAGEr7Nx9_EObl4ebD0IAufdefy7c"></div>

                <button type="submit" method="POST" id=submitBtn class="btn btn-primary w-100 mt-4 mb-2">Sign in</button>
                <a href="../googleAuth/google-login.php" class="google-btn btn btn-outline-primary w-100">
                    <i class="fa-brands fa-google"></i>
                    Connect with Google
                </a>
                <a class="forgot-password mt-4" href="../php-forms/forgot-password.php">Forgot password?</a>
            </div>
        </form>
    </aside>

    <footer class="d-lg-none">
        <p>Copyright &copy; 2025 Balolong Inc.</p>
    </footer>
</body>
</html>