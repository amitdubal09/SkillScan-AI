<?php
require('connection.php');
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header("location: ./auth.php");
    exit;
}

$username = $_SESSION['username'];
$query = "SELECT * FROM `extracted_information` WHERE `username` = '$username'";
$result = mysqli_query($conn, $query);

$skills = $experience = $education = $projects = $ats = $contactinfo = '';

if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    $skills = $data['skills'];
    $experience = $data['experience'];
    $education = $data['education'];
    $projects = $data['project'];
    $ats = $data['ats'];
    $contactinfo = $data['contactinfo'];
}

// Helper function to safely decode JSON and print as bullet list
function printJsonAsList($json)
{
    $arr = json_decode($json, true);
    if (is_array($arr)) {
        $output = '<ul>';
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $output .= '<li>' . implode(", ", $val) . '</li>';
            } else {
                if (is_numeric($key)) {
                    $output .= "<li>$val</li>";
                } else {
                    $output .= "<li><strong>$key:</strong> $val</li>";
                }
            }
        }
        $output .= '</ul>';
        return $output;
    }
    return '<p>' . nl2br(htmlspecialchars($json)) . '</p>';
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
    <div class="for-adjust">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <a href="index.php">SkillScan</a>
            </div>
            <ul class="menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="resumeanalysis.php">Resume Analysis</a></li>
                <li><a href="test.php">Take Test</a></li>
                <li><a href="report.php"> Reports</a></li>
            </ul>
            <div class="logout">
                <a href="logout.php">Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="topbar">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 🎉</h2>
            </header>

            <div class="cards">
                <div class="card">
                    <h3>Skills</h3>
                    <p>
                        <?php
                        $skillsArr = json_decode($skills, true);
                        if (is_array($skillsArr)) {
                            foreach ($skillsArr as $skill) {
                                echo '<span class="skill-badge">' . htmlspecialchars($skill) . '</span> ';
                            }
                        } else {
                            echo htmlspecialchars($skills);
                        }
                        ?>
                    </p>
                </div>

                <div class="card">
                    <h3>Experience</h3>
                    <?php echo printJsonAsList($experience); ?>
                </div>

                <div class="card">
                    <h3>Education</h3>
                    <?php echo printJsonAsList($education); ?>
                </div>

                <div class="card">
                    <h3>Projects</h3>
                    <?php echo printJsonAsList($projects); ?>
                </div>

                <div class="card">
                    <h3>Contact Info</h3>
                    <p><?php echo printJsonAsList($contactinfo); ?></p>
                </div>

                <div class="card highlight">
                    <h3>ATS Score</h3>
                    <p><?php echo htmlspecialchars($ats); ?>%</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Chatbot Icon -->
    <div class="chatbot-icon" id="chatbotIcon">
        <img src="img/robot-assistant.png" alt="Chatbot Icon">
    </div>

    <!-- Chat Popup -->
    <div class="chatbot-popup" id="chatbotPopup">
        <div class="chat-header">
            <span>SkillScan Assistant</span>
            <button id="closeChat"><img src="img/wrong.png" alt=""></button>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="bot-msg">👋 Hi! I'm SkillScan AI. How can I help you today?</div>
        </div>
        <div class="chat-input">
            <input type="text" id="userInput" placeholder="Type a message..." />
            <button id="sendBtn">➤</button>
        </div>
    </div>

    <script src="./javascript/dashboard.js"></script>
    <script>
        const chatbotIcon = document.getElementById("chatbotIcon");
        const chatbotPopup = document.getElementById("chatbotPopup");
        const closeChat = document.getElementById("closeChat");
        const sendBtn = document.getElementById("sendBtn");
        const userInput = document.getElementById("userInput");
        const chatBody = document.getElementById("chatBody");

        // Toggle Chat (open/close)
        chatbotIcon.addEventListener("click", () => {
            if (chatbotPopup.style.display === "flex") {
                chatbotPopup.style.display = "none";
            } else {
                chatbotPopup.style.display = "flex";
            }
        });


        // Close Chat
        closeChat.addEventListener("click", () => {
            chatbotPopup.style.display = "none";
        });

        // Send Message
        sendBtn.addEventListener("click", sendMessage);
        userInput.addEventListener("keypress", e => {
            if (e.key === "Enter") sendMessage();
        });

        function sendMessage() {
            const text = userInput.value.trim();
            if (!text) return;

            const userMsg = document.createElement("div");
            userMsg.classList.add("user-msg");
            userMsg.textContent = text;
            chatBody.appendChild(userMsg);
            userInput.value = "";

            chatBody.scrollTop = chatBody.scrollHeight;

            // Simulate bot reply (you can connect this to your PHP or Gemini AI API)
            setTimeout(() => {
                const botMsg = document.createElement("div");
                botMsg.classList.add("bot-msg");
                botMsg.textContent = "🤖 Thinking...";
                chatBody.appendChild(botMsg);

                fetch("api.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ message: text })
                })
                    .then(res => res.json())
                    .then(data => {
                        botMsg.textContent = data.reply || "⚠️ No response from AI.";
                        chatBody.scrollTop = chatBody.scrollHeight;
                    })
                    .catch(err => {
                        botMsg.textContent = "❌ Error connecting to AI.";
                        console.error(err);
                    });
            }, 500);
        }
    </script>
</body>

</html>