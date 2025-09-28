<?php
require('connection.php');
session_start();
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>SkillScan - Resume</title>
        <link rel="stylesheet" href="css/auth.css">
    </head>

    <body>

        <a href="index.php" id="back-img"><img src="img/left-arrow.png" alt="Back"></a>
        <div class="body">
            <div class="inline">
                <section class="wrapper">

                    <!-- Signup Form -->
                    <div class="form signup active">
                        <header>Signup</header>
                        <form method="POST" action="login_register.php">
                            <input class="moving-input" type="text" name="username" placeholder="Username" required />
                            <input class="moving-input" type="text" name="email" placeholder="Email address" required />
                            <input class="moving-input" type="password" name="password" placeholder="Password" required />
                            <div class="checkbox">
                                <input type="checkbox" id="signupCheck" />
                                <label for="signupCheck">I accept all terms & conditions</label>
                            </div>
                            <input class="submit" name="register" type="submit" value="Signup" />
                            <p class="toggle">Already have an account?
                                <a href="#" class="to-login">Login</a>
                            </p>
                        </form>
                    </div>

                    <!-- Login Form -->
                    <div class="form login">
                        <header>Login</header>
                        <form method="POST" action="login_register.php">
                            <input class="moving-input" type="text" name="email_username"
                                placeholder="Username / Email address" required />
                            <input class="moving-input" type="password" name="password" placeholder="Password" required />
                            <a href="#">Forgot password?</a>
                            <input class="submit" type="submit" name="login" value="Login" />
                            <p class="toggle">Don’t have an account?
                                <a href="#" class="to-signup">Signup</a>
                            </p>
                        </form>
                    </div>

                </section>
            </div>
        </div>

        <script src="javascript/auth.js"></script>
    </body>

    </html>
    <?php
} else {
    header("location:./index.php");
}
?>