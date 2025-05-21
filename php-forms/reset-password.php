<?php
session_start(); 

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_code_verified']) || !$_SESSION['reset_code_verified']) {
    header("Location: ../php-forms/enter-code.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../db.php';
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password: at least 8 chars, at least 1 letter, at least 1 special character
    if (
        strlen($new_password) < 8 ||
        !preg_match('/[A-Za-z]/', $new_password) ||
        !preg_match('/[^A-Za-z0-9]/', $new_password)
    ) {
        $_SESSION['error'] = "Password must be at least 8 characters, contain at least one letter and one special character.";
        header("Location: ../php-forms/reset-password.php");
        exit();
    }

    if($new_password === $confirm_password) {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $_SESSION['reset_email']]);
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_code_verified']);
        $_SESSION['success'] = "Password reset successfully.";
        header("Location: ../php-forms/sign-in.php");
        exit();
    } else {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../php-forms/reset-password.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="forms-styles/form-styles.css">
</head>
<body>
    <main>
        <form action="../php-forms/reset-password.php" method="POST" class="d-lg-none">
            <div class="title">
                <h1>Enter new password</h1>
                <!-- message if email address already exists -->
                <?php if(isset($_SESSION['error'])): ?>
                    <h5 class="text-light">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <!-- inputs container -->
            <div class="inputs mt-4 w-100">
                <!-- for new password -->
                <input type="password" class="form-control w-100" placeholder="Enter new password" name="new_password" aria-label="Password" aria-describedby="password-addon1" minlength="8" maxlength="15" required>

                <!-- confirm password -->
                <input type="password" class="form-control w-100" placeholder="Confirm password" name="confirm_password" aria-label="Confirm Password" aria-describedby="confirm-password-addon1" required minlength="8" maxlength="15">

                <button type="submit" id="submitBtn" method="POST" class="btn btn-primary w-100 mt-2">Submit</button>
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
        <form action="../php-forms/reset-password.php" method="POST" class="d-none d-lg-flex">
            <div class="title">
                <h1>Enter new password</h1>
                <!-- message if email address already exists -->
                <?php if(isset($_SESSION['error'])): ?>
                    <h5 class="text-light">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <!-- inputs container -->
            <div class="inputs mt-4">
                <!-- for new password -->
                <input type="password" class="form-control" placeholder="Enter new password" name="new_password" aria-label="Password" aria-describedby="password-addon1" minlength="8" maxlength="15" required>
            
                <!-- confirm password -->
                <input type="password" class="form-control" placeholder="Confirm password" name="confirm_password" aria-label="Confirm Password" aria-describedby="confirm-password-addon1" required minlength="8" maxlength="15">
            
                <button type="submit" id="submitBtn" method="POST" class="btn btn-primary w-100 mt-2">Submit</button>
        </form>
    </aside>

    <footer class="d-lg-none">
        <p>Copyright &copy; 2025 Balolong Inc.</p>
    </footer>
</body>
</html>