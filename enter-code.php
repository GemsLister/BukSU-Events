<?php
session_start();  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'db.php';
    $entered_code = $_POST['code'];
    $email = $_SESSION['email']; // Assuming you set the email in the session when sending the code

    if(!isset($_SESSION['email'])){
        $_SESSION['error'] = "No email session found";
        header("Location: ../BukSU-Events/forgot-password.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT reset_code FROM users WHERE email = ?");
    $stmt->execute([$email]); // call the database to check if the email address exists
    $user = $stmt->fetch(PDO::FETCH_ASSOC); // storing the result on a variable to be used later

    if($user){
        if($entered_code == $user['reset_code']){
            $_SESSION['reset_email'] = $email; // store the email in session for later use
            $_SESSION['reset_code_verified'] = true; // set a flag to indicate the code is verified
            header("Location: ../BukSU-Events/reset-password.php"); 
        }
        else{
            $_SESSION['error'] = "Invalid code. Please try again.";
            header("Location: ../BukSU-Events/enter-code.php");
            exit();
        }
    }
    else{
        $_SESSION['error'] = "Email address not found.";
        header("Location: ../BukSU-Events/forgot-password.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Code</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/enter-code.css">
</head>
<body>
    <!-- header section -->
    <header class="container-fluid d-flex">
    </header>

    <!-- main content -->
    <main class="container-fluid d-flex h-100">
        <!-- forgot password form -->
        <form name="enter-code-form" action="enter-code.php" method="POST" class="d-flex login-form">
            <!-- forgot password title (large size) -->
            <div class="container-fluid enter-code-large d-none d-lg-flex d-xl-flex d-xxl-flex">
                <div class="form-wordlines">
                    <h1>Enter Code</h1>
                    <p>Enter the verification code</p>
                </div>
                <a href="../BukSU-Events/sign-in.php">Back to sign in</a>
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
                <div class="container-fluid enter-code-small d-flex d-lg-none d-xl-none d-xxl-none">
                    <h1 class="text-center">Enter Code</h1>
                    <p>Enter the verification code</p>
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
                <!-- for code -->
                <div class="input-group">
                    <input type="number" class="form-control" placeholder="Enter code" name="code" aria-label="Enter code" aria-describedby="code-addon1" required>
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