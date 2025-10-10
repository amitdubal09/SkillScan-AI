<?php
session_start();
require('connection.php');

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$step = 1; // default step 1: enter email

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Step 1: Send OTP
    if (isset($_POST['send_otp'])) {
        $email = trim($_POST['email']);

        // Check if email exists
        $stmt = $conn->prepare("SELECT username FROM registered_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $otp = rand(100000, 999999); // 6-digit OTP
            $_SESSION['reset_email'] = $email;
            $_SESSION['otp'] = $otp;

            // Send OTP via PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'amitdubal2005@gmail.com';       // Your Gmail
                $mail->Password = 'vxtx yhiw vyja qfnu';         // Gmail App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('no-reply@skillscan.com', 'SkillScan');
                $mail->addAddress($email);
                $mail->Subject = 'SkillScan - Password Reset OTP';
                $mail->Body = "Hello {$row['username']},\n\nYour OTP for password reset is: {$otp}\nDo not share this with anyone.\n\nRegards,\nSkillScan Team";

                $mail->send();
                echo "<script>alert('OTP sent successfully to your email');</script>";
                $step = 2; // Move to OTP verification

            } catch (Exception $e) {
                echo "<script>alert('Failed to send OTP. Mailer Error: {$mail->ErrorInfo}');</script>";
            }

        } else {
            echo "<script>alert('Email not found. Please enter a registered email.');</script>";
        }
    }

    // Step 2: Verify OTP and update password
    if (isset($_POST['verify_otp'])) {
        $entered_otp = trim($_POST['otp']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (!isset($_SESSION['otp']) || !isset($_SESSION['reset_email'])) {
            echo "<script>alert('Session expired. Please restart the process.'); window.location.href='for_pass.php';</script>";
            exit;
        }

        if ($entered_otp == $_SESSION['otp']) {
            if ($new_password === $confirm_password) {
                $hashed = password_hash($new_password, PASSWORD_BCRYPT);
                $email = $_SESSION['reset_email'];

                $stmt = $conn->prepare("UPDATE registered_users SET password = ? WHERE email = ?");
                $stmt->bind_param("ss", $hashed, $email);

                if ($stmt->execute()) {
                    unset($_SESSION['otp']);
                    unset($_SESSION['reset_email']);
                    echo "<script>alert('Password updated successfully! You can now login.'); window.location.href='auth.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Failed to update password. Try again later.');</script>";
                    $step = 2;
                }
            } else {
                echo "<script>alert('Passwords do not match.');</script>";
                $step = 2;
            }
        } else {
            echo "<script>alert('Invalid OTP. Please check your email and try again.');</script>";
            $step = 2;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillScan - Forgot Password</title>
    <link rel="stylesheet" href="css/for_pass.css">
</head>

<body>
    <a href="auth.php" id="back-img"><img src="img/left-arrow.png" alt="Back"></a>
    <div class="body">
        <div class="inline">
            <section class="wrapper">

                <?php if ($step == 1): ?>
                    <!-- Step 1: Enter Email -->
                    <div class="form">
                        <header>Forgot Password</header>
                        <form method="POST">
                            <input class="moving-input" type="email" name="email" placeholder="Enter registered email"
                                required />
                            <input class="submit" type="submit" name="send_otp" value="Send OTP" />
                        </form>
                    </div>

                <?php elseif ($step == 2): ?>
                    <!-- Step 2: Enter OTP + New Password -->
                    <div class="form">
                        <header>Reset Password</header>
                        <form method="POST">
                            <input class="moving-input" type="text" name="otp" placeholder="Enter OTP" required />
                            <input class="moving-input" type="password" name="new_password" placeholder="New Password"
                                required />
                            <input class="moving-input" type="password" name="confirm_password"
                                placeholder="Confirm Password" required />
                            <input class="submit" type="submit" name="verify_otp" value="Update Password" />
                        </form>
                    </div>
                <?php endif; ?>

            </section>
        </div>
    </div>
</body>

</html>