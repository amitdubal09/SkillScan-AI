<?php
require('connection.php');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
  header("Location: auth.php");
  exit();
}

$username = $_SESSION['username'];
$query = "SELECT * FROM `extracted_information` WHERE `username` = '$username'";
$result = mysqli_query($conn, $query);
$skills = "";

if ($result && mysqli_num_rows($result) > 0) {
  $data = mysqli_fetch_assoc($result);
  $skills = $data['skills'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SkillScan Test</title>
  <link rel="stylesheet" href="css/test.css">
</head>

<body>
  <header>
    <div class="nav">
      <div class="logo"><a href="index.php">SkillScan</a></div>
      <div class="combine">
        <div class="dashboard">
          <ul>
            <li class="login-btn"><a href="dashboard.php">Dashboard</a></li>
          </ul>
        </div>
        <div class="user">
          <span>Hiii</span> <?= $_SESSION['username'] ?> <a href="logout.php">Logout</a>
        </div>
      </div>
    </div>
  </header>

  <div class="main">
    <h2>MCQ Test</h2>
    <p>Your Skills: <?= !empty($skills) ? htmlspecialchars($skills) : "No skills detected"; ?></p>
  </div>

  <div id="waitmseg">
    <?php if (empty($skills)): ?>
      <span>You haven't uploaded your resume. Please <a href="resumeanalysis.php">upload your resume</a> first.</span>
    <?php else: ?>
      <span>Wait a few seconds, your test will be ready shortly.</span>
    <?php endif; ?>
  </div>

  <div class="container">
    <div id="quiz"></div>
    <button id="submit">Submit Test</button>
    <div id="result" class="result"></div>
    <button class="back"><a href="index.php">Cancel Test</a></button>
  </div>

  <footer class="footer">
    <p>© 2025 SkillScan | Built to help you land your dream job</p>
  </footer>

  <script src="javascript/api.js"></script>
  <script>
    let footer = document.querySelector(".footer");
    let mockdata = [];

    // Only fetch test if skills exist
    async function fetchTestData() {
      <?php if (!empty($skills)): ?>
        mockdata = await window.MakeMockTest(<?= json_encode($skills) ?>);
        renderQuiz(mockdata);
      <?php endif; ?>
    }

    // Render quiz questions
    function renderQuiz(mcqs) {
      document.querySelector(".container").style.display = "block";
      document.getElementById("waitmseg").style.display = "none";
      footer.style.display = "block";

      const quizContainer = document.getElementById("quiz");
      quizContainer.innerHTML = "";

      mcqs.forEach((q, index) => {
        const questionDiv = document.createElement("div");
        questionDiv.classList.add("question");

        const questionTitle = document.createElement("h3");
        questionTitle.textContent = `${index + 1}. ${q.question}`;
        questionDiv.appendChild(questionTitle);

        const optionsDiv = document.createElement("div");
        optionsDiv.classList.add("options");

        q.options.forEach((opt, i) => {
          const optionLabel = String.fromCharCode(65 + i);
          const optionId = `q${index}_opt${i}`;
          const label = document.createElement("label");
          label.setAttribute("for", optionId);

          const input = document.createElement("input");
          input.type = "radio";
          input.name = `q_${index}`;
          input.value = opt;
          input.id = optionId;

          label.appendChild(input);
          label.append(` ${optionLabel}. ${opt}`);
          optionsDiv.appendChild(label);
        });

        questionDiv.appendChild(optionsDiv);
        quizContainer.appendChild(questionDiv);
      });
    }

    // Submit Test
    function submitTest() {
      let score = 0;

      mockdata.forEach((q, index) => {
        const options = document.querySelectorAll(`input[name="q_${index}"]`);
        const selected = document.querySelector(`input[name="q_${index}"]:checked`);

        options.forEach(opt => {
          const label = opt.closest("label");
          label.classList.remove("correct", "wrong");

          if (opt.value === q.answer) {
            label.classList.add("correct");
          }
        });

        if (selected && selected.value === q.answer) {
          score++;
        } else if (selected) {
          selected.closest("label").classList.add("wrong");
        }
      });

      document.querySelectorAll('input[type="radio"]').forEach(r => r.disabled = true);
      document.getElementById("result").textContent = `You scored ${score} / ${mockdata.length}`;
    }

    document.getElementById("submit").addEventListener("click", submitTest);

    // Fetch MCQs on page load
    fetchTestData();
  </script>
</body>

</html>