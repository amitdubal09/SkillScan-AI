<?php
require('connection.php');
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

    $username = $_SESSION['username'];
    $query = "select * from `extracted_information` where `username` ='$username'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);
            $skills = $data['skills'];
            $experience = $data['experience'];
            $education = $data['education'];
            $projects = $data['project'];
            $ats = $data['ats'];
            $contactinfo = $data['contactinfo'];
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SkillScan Dashboard</title>
        <link rel="stylesheet" href="css/dashboard.css">
    </head>

    <body>
        <header>
            <div class="nav">
                <div class="logo"><b><a href="index.php">SkillScan</a></b></div>
                <?php
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                    echo "
                        <div class='user'>
                            <span>Hiii</span>
                            $_SESSION[username] <a href='logout.php'>logout</a>
                        </div>
                    ";
                } else {
                    echo "
                      <div class='nav-list'>
                            <ul>
                                <li class='login-btn'><a href='auth.php'>SignUp</a></li>
                            </ul>
                      </div>
                     ";
                }
                ?>
            </div>
        </header>
        <strong class="welcome">
            <p>Welcome <?php echo "$_SESSION[username]"; ?></p>
        </strong>
        <div class="container">
            <div class="section1">
                <div class="content">
                    <div class="user-info">
                        <strong></strong>
                    </div>
                    <div class="skills">
                        <p><strong>Skills:</strong><?php echo $skills ?></p>
                    </div>
                    <div class="experience">
                        <p><strong>experience:</strong><?php echo $experience ?></p>
                    </div>
                    <div class="education">
                        <p><strong>education:</strong><?php echo $education ?></p>
                    </div>
                    <div class="projects">
                        <p><strong>projects:</strong><?php echo $projects ?></p>
                    </div>
                    <div class="ats">
                        <p><strong>ats:</strong><?php echo $ats ?></p>
                    </div>
                </div>
                <p class="btn"><a href="resumeanalysis.php">Analyze Resume</a></p>
            </div>
            <div class="section2">
                <p class="btn"><a href="test.php">Take Test</a></p>
            </div>
        </div>

        <script src="javascript/dashboard.js"></script>
        <script src="javascript/script.js"></script>
    </body>

    </html>
    <?php

} else {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header("location:./auth.php");
    exit;
} ?>