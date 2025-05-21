<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect with Google</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="forms-styles/form-styles.css">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
    <main>
        <form action="../sign-up-validate.php" method="POST" class="d-lg-none">
            <div class="title">
                <h1>Create a Profile</h1>
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
            <div class="inputs">
                <input type="email" class="form-control mt-3 mb-3" placeholder="Email address" name="email" aria-label="Email address" aria-describedby="email-addon1" required>
                <input type="password" class="form-control mb-3" placeholder="Password" name="password" aria-label="Password" aria-describedby="password-addon1" required>

                <div class="input-group">
                    <input type="tel" class="form-control" placeholder="Contact number" name="contact_no" aria-label="Contact number" aria-describedby="contact-addon1" required>
                </div>

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
                <button type="submit" method="POST" id="submitBtn" class="btn btn-primary w-100 mt-4 mb-2">Submit</button>
                <div id="g_id_onload"
                     data-client_id="YOUR_GOOGLE_CLIENT_ID" data-login_uri="google-login.php"
                     data-auto_prompt="false">
                </div>
                <div class="g_id_signin"
                     data-type="standard"
                     data-size="large"
                     data-theme="outline"
                     data-text="sign_in_with"
                     data-shape="rectangular"
                     data-logo_alignment="left">
                </div>
            </div>
        </form>

        <figure class="d-none d-lg-flex">
            <img src="../images/form_logo.png" class="form-logo" alt="logo">
            <div class="copyrights" class="d-none d-lg-flex">
                <p>Copyright &copy; 2025 Balolong Inc.</p>
            </div>
        </figure>
    </main>

    <aside>
        <form action="../sign-up-validate.php" method="POST" class="d-none d-lg-flex">
            <div class="title">
                <h1>Create a Profile</h1>
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
            <div class="inputs">
                <input type="text" class="form-control mt-3 mb-3" placeholder="First name" name="firstname" aria-label="First name" aria-describedby="fname-addon1" required>
                <input type="text" class="form-control mb-3" placeholder="Last name" name="lastname" aria-label="Last name" aria-describedby="lname-addon1" required>

                <div class="input-group mb-3">
                    <input type="tel" class="form-control" placeholder="Contact number" name="contact_no" aria-label="Contact number" aria-describedby="contact-addon1" required>
                </div>

                <div class="roles d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input w-25" type="radio" value="student" id="student_role_lg" name="roles">
                        <label class="form-check-label" for="flexCheckDefault_lg">
                            Student
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input w-25" type="radio" value="faculty" id="faculty_role_lg" name="roles">
                        <label class="form-check-label" for="flexCheckChecked_lg">
                            Faculty
                        </label>
                    </div>
                </div>
                <button type="submit" method="POST" id="submitBtn" class="btn btn-primary w-100 mt-4 mb-2">Submit</button>
            </div>
        </form>
    </aside>

    <footer class="d-lg-none">
        <p>Copyright &copy; 2025 Balolong Inc.</p>
    </footer>
</body>
</html>