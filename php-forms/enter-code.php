<?php
session_start();  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../db.php';
    $entered_code = $_POST['code'];
    $email = $_SESSION['email']; // Assuming you set the email in the session when sending the code

    if(!isset($_SESSION['email'])){
        $_SESSION['error'] = "No email session found";
        header("Location: ../php-forms/forgot-password.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT reset_code FROM users WHERE email = ?");
    $stmt->execute([$email]); // call the database to check if the email address exists
    $users = $stmt->fetch(PDO::FETCH_ASSOC); // storing the result on a variable to be used later

    if($users){
        if($entered_code == $users['reset_code']){
            $_SESSION['reset_email'] = $email; // store the email in session for later use
            $_SESSION['reset_code_verified'] = true; // set a flag to indicate the code is verified
            header("Location: reset-password.php"); 
        }
        else{
            $_SESSION['error'] = "Invalid code. Please try again.";
            header("Location: enter-code.php");
            exit();
        }
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
        <form action="enter-code.php" method="POST" class="d-lg-none">
            <div class="title">
                <h1>Enter code</h1>
                <!-- message if email address already exists -->
                <?php if(isset($_SESSION['error'])): ?>
                    <h5 class="text-light">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <!-- inputs container -->
            <div class="inputs mt-4 w-100">
                <input type="number" class="form-control" placeholder="Enter code" name="code" aria-label="Enter code" aria-describedby="code-addon1" required maxlength="6" minlength="6">

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
        <form action="../sign-up-validate.php" method="POST" class="d-none d-lg-flex">
            <div class="title">
                <h1>Enter code</h1>
                <!-- message if email address already exists -->
                <?php if(isset($_SESSION['error'])): ?>
                    <h5 class="text-light">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <!-- inputs container -->
            <div class="inputs mt-4">
                <input type="number" class="form-control" placeholder="Enter code" name="code" aria-label="Enter code" aria-describedby="code-addon1" required maxlength="6" minlength="6">

                <button type="submit" id="submitBtn" method="POST" class="btn btn-primary w-100 mt-2">Submit</button>
            </div>
        </form>
    </aside>

    <footer class="d-lg-none">
        <p>Copyright &copy; 2025 Balolong Inc.</p>
    </footer>
</body>
</html>