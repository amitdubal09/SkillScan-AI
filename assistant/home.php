<?php
session_start();
require '../connection.php'; // Your database connection file

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header("location: ../auth.php");
    exit;
}

// Get username from session
$session_username = $_SESSION['username'] ?? null;
$username = "User";

if ($session_username) {
    $stmt = $conn->prepare("SELECT username FROM registered_users WHERE username = ?");
    $stmt->bind_param("s", $session_username);
    $stmt->execute();
    $stmt->bind_result($db_username);
    if ($stmt->fetch()) {
        $username = htmlspecialchars($db_username); // prevent XSS
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillScan AI Chatbot</title>
    <link rel="stylesheet" href="home.css">
</head>

<body>
    <div class="main-container">
        <!-- Left Sidebar -->
        <div class="left-sidebar">
            <!-- Sidebar Title -->
            <div class="sidebar-title">
                <h2><a href="../index.php">SkillScan</a></h2>
            </div>

            <!-- Spacer to push user info to bottom -->
            <div style="flex:1;"></div>

            <!-- User Info -->
            <div class="user-info">
                <p id="username">Hello, <?php echo $username; ?></p>
            </div>
        </div>


        <!-- Right Chat Area -->
        <div class="chat-wrapper">
            <!-- Header -->
            <div class="chat-header">
                <div class="header-title">
                    <h1>SkillScan Assistant</h1>
                    <p>Your personal resume assistant</p>
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="chat-box" class="chat-box">
                <div class="message bot-msg">
                    Hi! I am SkillScan. Ask me about your resume, skills, or career guidance.
                </div>
            </div>

            <!-- Input Area -->
            <div class="chat-input-area">
                <input id="userInput" type="text" placeholder="Type your question here..." />
                <button id="sendBtn">Send</button>
            </div>
        </div>
    </div>

    <script src="home.js"></script>
</body>

</html>