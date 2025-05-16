<?php
session_start();
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
        <form action="../sign-up-validate.php" method="POST" class="d-lg-none">
            <div class="title">
                <h1>Sign up</h1>
                <p>Already have an account? <strong><a href="sign-in.php">Sign in</a></strong></p>
                <!-- message if email address already exists -->
                <?php if(isset($_SESSION['error'])): ?>
                    <h5 class="text-light">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <!-- inputs container -->
            <div class="inputs">
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
            
                <input type="tel" class="form-control" placeholder="Contact number" name="contact_no" aria-label="Contact number" aria-describedby="contact-addon1" required>
                
                <!-- for roles -->
                <div class="roles d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input w-25" type="radio" value="student" id="student_role" name="roles">
                        <label class="form-check-label" for="flexCheckDefault">
                            Student
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input w-25" type="radio" value="faculty" id="faculty_role" name="roles">
                        <label class="form-check-label" for="flexCheckChecked">
                            Faculty
                        </label>
                    </div>
                </div>
                <!-- for email-address -->
                <input type="email" class="form-control" placeholder="Email address" name="email" aria-label="Email address" aria-describedby="email-addon1" required>

                <!-- for password -->
                <input type="password" class="form-control" placeholder="Password" name="password" aria-label="Password" aria-describedby="password-addon1" minlength="8" maxlength="15" required>
            
                <!-- confirm password -->
                <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" minlength="8" maxlength="15" required>
                <button type="submit" method="POST" id=submitBtn class="btn btn-primary w-100 mt-3">Sign up</button>
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
                <h1>Sign up</h1>
                <p>Already have an account? <strong><a href="sign-in.php">Sign in</a></strong></p>
                <!-- message if email address already exists -->
                <?php if(isset($_SESSION['error'])): ?>
                    <h5 class="text-light">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </h5>
                <?php endif; ?>
            </div>
            <!-- inputs container -->
            <div class="inputs">
                <!-- First name and Last name -->
                <div class="firstname-lastname d-flex gap-3 w-100">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="First name" name="firstname" aria-label="First name" aria-describedby="fname-addon1" required>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Last name" name="lastname" aria-label="Last name" aria-describedby="lname-addon1" required>
                    </div>
                </div>
                <!-- for contact no. -->
            
                <input type="tel" class="form-control" placeholder="Contact number" name="contact_no" aria-label="Contact number" aria-describedby="contact-addon1" required>
                
                <!-- for roles -->
                <div class="roles d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input w-25" type="radio" value="student" id="student_role" name="roles">
                        <label class="form-check-label" for="flexCheckDefault">
                            Student
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input w-25" type="radio" value="faculty" id="faculty_role" name="roles">
                        <label class="form-check-label" for="flexCheckChecked">
                            Faculty
                        </label>
                    </div>
                </div>
                <!-- for email-address -->
                <input type="email" class="form-control" placeholder="Email address" name="email" aria-label="Email address" aria-describedby="email-addon1" required>

                <!-- for password -->
                <input type="password" class="form-control" placeholder="Password" name="password" aria-label="Password" aria-describedby="password-addon1" minlength="8" maxlength="15" required>
            
                <!-- confirm password -->
                <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" minlength="8" maxlength="15" required>
                <button type="submit" method="POST" id=submitBtn class="btn btn-primary w-100 mt-3">Sign up</button>
            </div>
        </form>
    </aside>

    <footer class="d-lg-none">
        <p>Copyright &copy; 2025 Balolong Inc.</p>
    </footer>
</body>
</html>