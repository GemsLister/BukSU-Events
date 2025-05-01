<?php
session_start(); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load Composer's autoloader
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'db.php'; 
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]); 
    $user = $stmt->fetch(PDO::FETCH_ASSOC); // storing the result on a variable to be used later

    if($user){
        $reset_code = rand(100000, 999999); // generate a random 6-digit code

        $update = $pdo->prepare("UPDATE users SET reset_code = ? WHERE email = ?");
        $update->execute([$reset_code, $email]); // call the database to update the reset code

        $_SESSION['email'] = $email; // store the email in session for later use
        $mail = new PHPMailer(true); // Create a new PHPMailer instance

        try{
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'jameslesterlopez@gmail.com'; // SMTP username
            $mail->Password = 'anat fwop adku qrpq'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587; // TCP port to connect to
            
            $mail->setFrom('jameslesterlopez@gmail.com', 'BukSU Events'); // Sender's email and name
            $mail->addAddress($email); // Add a recipient

            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Password Reset Code'; // Subject of the email

            $mail->Body    = "Your password reset code is: <strong>$reset_code</strong>"; // Email body content
            $mail->AltBody = "Your password reset code is: $reset_code"; // Plain text body for non-HTML email clients
            $mail->send(); // Send the email
            $_SESSION['success'] = "A reset code has been sent to your email address.";
            header("Location: enter-code.php");
            exit();
        }
        catch (Exception $e){
            $_SESSION['error'] = "Message could not be sent.";
            header("Location: forgot-password.php");
            exit();   
        }

        $_SESSION['success'] = "A reset code has been sent to your email address.";
        header("Location: enter-code.php"); 
        exit();
    }
    else{
        $_SESSION['error'] = "Email address not found.";
        header("Location: forgot-password.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../BukSU-Events/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/forgot-password.css">
</head>
<body>
    <!-- header section -->
    <header class="container-fluid d-flex">
    </header>

    <!-- main content -->
    <main class="container-fluid d-flex h-100">
        <!-- forgot password form -->
        <form name="forgot-password-form" action="forgot-password.php" method="POST" class="d-flex login-form">
            <!-- forgot password title (large size) -->
            <div class="container-fluid forgot-password-large d-none d-lg-flex d-xl-flex d-xxl-flex">
                <div class="form-wordlines">
                    <h1>Forgot Password</h1>
                    <p>Enter your email address and we will send you a code to reset your password.</p>
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
                <div class="container-fluid forgot-password-small d-flex d-lg-none d-xl-none d-xxl-none">
                    <h1 class="text-center">Forgot Password</h1>
                    <p class="text-center">Enter your email address and we will send you a code to reset your password.</p>
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