<?php
require('connection.php');
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $contactinfo = $_POST['contactinfo'];
        $skills = $_POST['skills'];
        $projects = $_POST['projects'];
        $education = $_POST['education'];
        $experience = $_POST['experience'];
        $ats = $_POST['atsscore'];

        $sql = "INSERT INTO extracted_information ( name, contactinfo, skills, projects, education, experience, atsscore) VALUES (?, ?, ?,)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $contactinfo, $skills, $projects, $education, $experience, $ats);

        if ($stmt->execute()) {
            echo "data stored successfully";
        } else {
            echo "error: " . $stmt->error;
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SkillScan - Resume</title>
        <link rel="stylesheet" href="./css/resumeanalysis.css">

    </head>

    <body>
        <div class="loading" id="loading-overlay">
            <div class="loader-wrapper">
                <div class="loader"></div>
                <div class="loader-letters">
                    <span class="loader-letter">L</span>
                    <span class="loader-letter">o</span>
                    <span class="loader-letter">a</span>
                    <span class="loader-letter">d</span>
                    <span class="loader-letter">i</span>
                    <span class="loader-letter">n</span>
                    <span class="loader-letter">g</span>
                    <span class="loader-letter">.</span>
                    <span class="loader-letter">.</span>
                    <span class="loader-letter">.</span>
                </div>
            </div>
        </div>


        </div>
        <header>
            <div class="nav">
                <div class="logo"><a href="index.php">SkillScan</a></div>

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
        <div class="head">
            <h1>SkillScan - Resume Analyzer & Mock Tester</h1>
        </div>
        <main>
            <div class="container">
                <div class="upload-container" id="dropArea">
                    <p><strong>Drag & drop</strong> your resume here</p>
                    <p>or <span class="browse">click to browse</span></p>
                    <input type="file" id="resumeInput" class="file-input" accept=".pdf,.doc,.docx,.jpg" />
                    <p id="fileName" class="file-name">No file selected</p>
                </div>
                <button id="start-analysis">Start Analysis</button>
                <div id="main">
                    <div class="atsmeter">
                        <div class="circle" id="atsCircle" style="--percent: 80">
                            <span id="atsValue"></span>
                        </div>
                    </div>
                    <h2>Resume Preview</h2>
                    <p>Uploaded file Name is <span id="fileNameUploaded">file.pdf</span></p>
                    <h3>Name</h3>
                    <p class="username" name="name">No Name extracted</p>
                    <h3>contact Info</h3>
                    <p class="contactinfo" name="contactinfo">No contact Info extracted</p>
                    <h3>Extracted Skills</h3>
                    <p id="SkillsExtracted" name="skills"></p>
                    <h3>Experience</h3>
                    <div id="experience" name="experience"></div>
                    <h3>Education</h3>
                    <p class="education" name="education"></p>
                    <h3>projects</h3>
                    <div class="projects" name="projects"></div>
                    <h3>ATS Score</h3>
                    <p class="atsscore" name="atsscore"></p>


                    <div class="main2">
                        <div class="recommendation">
                            <h2>Personalized recommendation</h2>
                            <div class="option-list">
                                <span class="format-improve suggestions-btn">Format improvement</span>
                                <span class="keywords suggestions-btn">Keywords</span>
                            </div>

                            <div class="formattoimprove">
                                <div class="suggestion"></div>
                                <div class="suggestion"></div>

                            </div>
                            <div class="keywordstosuggest">

                            </div>
                        </div>
                    </div>
                </div>
                <button class="test-btn"><a href="test.php">Take Test</a></button>
            </div>
            <div class="preview">
                <iframe src="resume.pdf#toolbar=0&navpanes=0&scrollbar=0" width="100%" height="600px"></iframe>
            </div>
        </main>
        <?php
        if (isset($_SESSION['logged_in'])) {
            echo "";
        }
        ?>

        <div class="chatbot-icon">
            <a href="assistant/home.php">
                <img src="img/robot-assistant.png" alt="">
            </a>
        </div>

        <script src="./javascript/api.js"></script>
        <script src="./javascript/script.js"></script>

    </body>

    </html>
    <?php

} else {
    header("location:./auth.php");
} ?>