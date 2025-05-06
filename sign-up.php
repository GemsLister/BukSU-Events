<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="../BukSU-Events//bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../BukSU-Events/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../BukSU-Events/css-style/sign-up.css">
</head>
<body>
    <!-- header section -->
    <header class="container-fluid d-flex">
    </header>

    <!-- main content -->
    <main class="container-fluid d-flex h-100">
        <!-- sign-up title (large size) -->
        <!-- sign-up form -->
        <form name="sign-up-form" action="sign-up-validate.php" method="POST" class="d-flex login-form">
            <div class="container-fluid sign-up-large d-none d-lg-flex d-xl-flex d-xxl-flex">
                <div class="form-wordlines">
                    <h1>Sign up</h1>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eos ea accusantium neque libero earum </p>
                </div>
                <p>Already have an account? <a href="../BukSU-Events/sign-in.php">Sign in</a></p>
                <!-- message if email address already exists -->
                <?php if(isset($_SESSION['error'])): ?>
                    <h5 class="text-light">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <!-- registration inputs -->
            <div class="container-fluid inputs d-flex d-lg-flex d-xxl-flex">
                <!-- sign-up title (small size) --> 
                <div class="container-fluid sign-up-small d-flex d-lg-none d-xl-none d-xxl-none">
                    <h1>Sign up</h1>
                    <p>Already have an account? <a href="../BukSU-Events/sign-in.php">Sign in</a></p>
                    <!-- message if email address already exists -->
                    <?php if(isset($_SESSION['error'])): ?>
                        <h6 class="warning-message">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </h6>
                    <?php endif; ?>
                </div>
                <!-- First name and Last name -->
                 <div class="firstname-lastname d-flex gap-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="First name" name="firstname" aria-label="First name" aria-describedby="fname-addon1" required>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Last name" name="lastname" aria-label="Last name" aria-describedby="lname-addon1" required>
                    </div>
                 </div>
                 <!-- for contact no. -->
                 <div class="input-group">
                    <input type="tel" class="form-control" placeholder="Contact number" name="contact_no" aria-label="Contact number" aria-describedby="contact-addon1" required>
                </div>
                <!-- for roles -->
                <!-- <div class="roles d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input w-25" type="radio" value="Student" id="student_role" name="role">
                        <label class="form-check-label" for="flexCheckDefault">
                            Student
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input w-25" type="radio" value="Faculty" id="faculty_role" name="role">
                        <label class="form-check-label" for="flexCheckChecked">
                            Faculty
                        </label>
                    </div>
                </div> -->
                <!-- for email-address -->
                <div class="input-group">
                    <input type="email" class="form-control" placeholder="Email address" name="email" aria-label="Email address" aria-describedby="email-addon1" required>
                </div>
                <!-- for password -->
                <div class="input-group">
                    <input type="password" class="form-control" placeholder="Password" name="password" aria-label="Password" aria-describedby="password-addon1" minlength="8" maxlength="15" required>
                </div>
                <!-- confirm password -->
                <div class="input-group mb-2">
                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" minlength="8" maxlength="15" required>
                </div>
                <button type="submit" method="POST" class="btn btn-primary w-100 mt-3">Sign up</button>
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