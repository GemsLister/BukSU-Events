<?php
session_start(); 

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_code_verified']) || !$_SESSION['reset_code_verified']) {
    header("Location: enter-code.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'db.php';
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($new_password === $confirm_password) {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT); // hash the new password 

        // update the password in the database
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $_SESSION['reset_email']]); // call the database to update the password

        // unset the session variables
        unset($_SESSION['reset_email']); // clear the email session variable
        unset($_SESSION['reset_code_verified']); // clear the reset code verification flag
        $_SESSION['success'] = "Password reset successfully.";
        
        // go back to sign-in page
        header("Location: sign-in.php");
        exit();
    }
    else{
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: reset-password.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/reset-password.css">
</head>
<body>
    <!-- header section -->
    <header class="container-fluid d-flex">
    </header>

    <!-- main content -->
    <main class="container-fluid d-flex h-100">
        <!-- forgot password form -->
        <form name="sign-in-form" action="reset-password.php" method="POST" class="d-flex login-form">
            <!-- forgot password title (large size) -->
            <div class="container-fluid reset-password-large d-none d-lg-flex d-xl-flex d-xxl-flex">
                <div class="form-wordlines">
                    <h1>Reset Password</h1>
                    <p>Create a new password.</p>
                </div>
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
                <div class="container-fluid reset-password-small d-flex d-lg-none d-xl-none d-xxl-none">
                    <h1 class="text-center">Reset Password</h1>
                    <p>Create a new password.</p>
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
                <!-- for new password -->
                <div class="input-group">
                    <input type="password" class="form-control" placeholder="Enter new password" name="new_password" aria-label="Password" aria-describedby="password-addon1" minlength="8" maxlength="15" required>
                </div>
                <!-- confirm password -->
                <div class="input-group">
                    <input type="password" class="form-control" placeholder="Confirm password" name="confirm_password" aria-label="Confirm Password" aria-describedby="confirm-password-addon1" required minlength="8" maxlength="15">
                </div>
                <button type="submit" method="POST" class="btn btn-primary w-100 mt-2">Submit</button>
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
    <script src="../BukSU-Events/jquery3.7.1.js"></script>
    <script src="../BukSU-Events/script.js"></script>
    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>