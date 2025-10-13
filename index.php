<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillScan - Resume</title>
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <header>
        <div class="nav">
            <div class="logo"><b>SkillScan</b></div>
            <div class="combine">
                <div class="dashboard">
                    <ul>
                        <li class='login-btn'><a href='dashboard.php'>dashboard</a></li>
                    </ul>
                </div>
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
        </div>
    </header>


    <section class="hero">
        <div class="intro">
            <h1>Welcome to SkillScan</h1>
            <p>Your personal resume analyzer that extracts key details, evaluates ATS score, and gives you smart
                recommendations to improve your resume.</p>

            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <!-- If logged in -->
                <a href="resumeanalysis.php" class="btn">Analyse Resume</a>
                <a href="test.php?feature=test" class="btn">Take Test</a>
            <?php else: ?>
                <!-- If not logged in -->
                <a href="auth.php" class="btn">Get Start</a>
            <?php endif; ?>
        </div>
    </section>


    <!-- Features Section -->
    <section class="features">
        <h2>What SkillScan Can Do</h2>
        <div class="feature-grid">
            <div class="feature">
                <h3>Resume Analysis</h3>
                <p>Extracts skills, experience, education, and other vital details directly from your resume.</p>
            </div>
            <div class="feature">
                <h3>ATS Score</h3>
                <p>Calculates your Applicant Tracking System (ATS) compatibility score instantly.</p>
            </div>
            <div class="feature">
                <h3>Smart Suggestions</h3>
                <p>Gives recommendations for missing skills, keywords, and formatting improvements.</p>
            </div>
            <div class="feature">
                <h3>Mock Test</h3>
                <p>Generates AI-based personalized questions based on your resume for interview practice.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <h2>How It Works</h2>
        <div class="steps">
            <div class="step">
                <h3>Step 1</h3>
                <p>Access your account to begin the process</p>
            </div>
            <div class="step">
                <h3>Step 2</h3>
                <p>Upload your resume in PDF or DOCX format.</p>
            </div>
            <div class="step">
                <h3>Step 3</h3>
                <p>AI extracts your skills, experiences, and education instantly.</p>
            </div>
            <div class="step">
                <h3>Step 4</h3>
                <p>Get your ATS score and detailed recommendations.</p>
            </div>
            <div class="step">
                <h3>Step 5</h3>
                <p>Practice with personalized AI-generated mock test questions.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 SkillScan | Built to help you land your dream job</p>
        <p><a href="privacy&policy.html">Privacy Policy</a></p>
    </footer>

    <div class="chatbot-icon">
        <a href="assistant/home.php">
            <img src="img/robot-assistant.png" alt="">
        </a>
    </div>

    <!-- JS Scripts -->
    <script src="./javascript/api.js" defer></script>
    <script src="./javascript/script.js" defer></script>

</body>

</html>