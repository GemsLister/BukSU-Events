<?php
session_start(); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Load Composer's autoloader
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'db.php'; 
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]); 
    $faculty_user = $stmt->fetch(PDO::FETCH_ASSOC); // storing the result on a variable to be used later

    if($faculty_user){
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
            header("Location: php-forms/enter-code.php");
            exit();
        }
        catch (Exception $e){
            $_SESSION['error'] = "Message could not be sent.";
            header("Location: php-forms/forgot-password.php");
            exit();   
        }

        $_SESSION['success'] = "A reset code has been sent to your email address.";
        header("Location: php-forms/enter-code.php"); 
        exit();
    }
    else{
        $_SESSION['error'] = "Email address not found.";
        header("Location: php-forms/forgot-password.php");
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
        <form action="../forgot-password.php" method="POST" class="d-lg-none">
            <div class="title">
                <h1>Enter Email</h1>
                <!-- message if email address already exists -->
                <?php if(isset($_SESSION['error'])): ?>
                    <h5 class="text-light">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <!-- inputs container -->
            <div class="inputs mt-4 w-100">
                <input type="email" class="form-control" placeholder="Email address" name="email" aria-label="Email address" aria-describedby="email-addon1" required>

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
        <form action="../forgot-password.php" method="POST" class="d-none d-lg-flex">
            <div class="title">
                <h1>Enter Email</h1>
                <!-- message if email address already exists -->
                <?php if(isset($_SESSION['error'])): ?>
                    <h5 class="text-light">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <!-- inputs container -->
            <div class="inputs mt-4">
                <input type="email" class="form-control" placeholder="Email address" name="email" aria-label="Email address" aria-describedby="email-addon1" required>

                <button type="submit" id="submitBtn" method="POST" class="btn btn-primary w-100 mt-2">Submit</button>
            </div>
        </form>
    </aside>

    <footer class="d-lg-none">
        <p>Copyright &copy; 2025 Balolong Inc.</p>
    </footer>
</body>
</html>