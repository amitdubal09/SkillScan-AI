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

        <a href="index.php"><img src="./img/back.png" alt=""></a>
        <div class="body">
            <div class="inline">
                <img class="bg-gif" src="./img/20943463.jpg" alt="">
                <section class="wrapper">

                    <div class="form signup">
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
                        </form>
                    </div>

                    <div class="form login">
                        <header>Login</header>
                        <form method="POST" action="login_register.php">
                            <input class="moving-input" type="text" name="email_username"
                                placeholder="Username / Email address" />
                            <input class="moving-input" type="password" name="password" placeholder="Password" required />
                            <a href="#">Forgot password?</a>
                            <input class="submit" type="submit" name="login" value="Login" />
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
} ?>