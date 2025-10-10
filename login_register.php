<?php
require('connection.php');

session_start();

#for registretion
if (isset($_POST['register'])) {
    
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Basic empty validation
    if ($username === '' || $email === '' || $password === '') {
        echo "<script>alert('All fields are required'); window.location.href='auth.php';</script>";
        exit;
    }

    // Email validation (regex, case-insensitive)
    if (!preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i", $email)) {
        echo "<script>alert('Invalid email format. Please use a valid email address.'); window.location.href='auth.php';</script>";
        exit;
    }


    // Check if username OR email already exists
    $user_exists_query = "
        SELECT * FROM `registered_users` 
        WHERE `username` = '{$_POST['username']}' 
        OR `email` = '{$_POST['email']}'
    ";

    $result = mysqli_query($conn, $user_exists_query);

    if ($result) {

        if (mysqli_num_rows($result) > 0) {
            // Username or Email already registered
            $result_fetch = mysqli_fetch_assoc($result);

            if ($result_fetch['username'] == $_POST['username']) {
                echo "
                    <script>
                        alert('{$result_fetch['username']} - username already registered');
                        window.location.href='auth.php';
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('{$result_fetch['email']} - email already registered');
                        window.location.href='auth.php';
                    </script>
                ";
            }

        } else {
            // Insert new user
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $query = "
                INSERT INTO `registered_users`(`username`, `email`, `password`) 
                VALUES ('{$_POST['username']}', '{$_POST['email']}','{$password}')
            ";

            if (mysqli_query($conn, $query)) {
                // echo " 
                //     <script>
                //         alert('Registration successful');
                //         window.location.href='dashboard.php';
                //     </script>
                // ";
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $_POST['username'];
                $redirect = $_SESSION['redirect_to'] ?? 'index.php';
                unset($_SESSION['redirect_to']);
                header("location: $redirect");
            } else {
                echo " 
                    <script>
                        alert('Cannot run insert query');
                        window.location.href='auth.php';
                    </script>
                ";
            }
        }

    } else {
        echo "
            <script>
                alert('Cannot run select query');
                window.location.href='auth.php';
            </script>
        ";
    }
}

#for login
# ---------------- LOGIN ----------------
if (isset($_POST['login'])) {
    $email_username = $_POST['email_username'];
    $password = $_POST['password'];

    // Query to check user by email OR username
    $query = "
        SELECT * FROM `registered_users` 
        WHERE `email` = '$email_username' 
        OR `username` = '$email_username'
    ";

    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $result_fetch = mysqli_fetch_assoc($result);

            // Verify password using password_verify()
            if (password_verify($password, $result_fetch['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $result_fetch['username'];
                $redirect = $_SESSION['redirect_to'] ?? 'index.php';
                unset($_SESSION['redirect_to']); // clear redirect once used
                header("location: $redirect");
            } else {
                echo "
                    <script>
                        alert('invalid crendentials');
                        window.location.href='auth.php';
                    </script>
                ";
            }

        } else {
            echo "
                <script>
                    alert('❌ Email or Username not registered');
                    window.location.href='auth.php';
                </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('❌ Cannot run SELECT query: " . mysqli_error($conn) . "');
                window.location.href='auth.php';
            </script>
        ";
    }
}
?>