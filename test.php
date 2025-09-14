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
    }
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
    <div class="main">
      <h2>MCQ Test</h2>
      <p>Your Skills : <?php echo $skills ?></p>
    </div>
    <div id="waitmseg">
      <span>Wait few seconds, Your Test will ready in few time.</span>
    </div>

    <div class="container">
      <!-- Quiz Section -->
      <div id="quiz"></div>
      <button id="submit">Submit Test</button>
      <div id="result" class="result"></div>

      <button class="back"><a href="index.php">Cancel Test</a></button>
    </div>

    <!-- Footer -->
    <footer class="footer">
      <p class="footer-p">© 2025 SkillScan | Built to help you land your dream job</p>
    </footer>

    <!-- JS Files -->
    <script src="javascript/api.js"></script>
    <script>
      let footer = document.querySelector(".footer");
      let mockdata = {};
      async function fetchTestData() {
        mockdata = await window.MakeMockTest(<?php echo $skills ?>);
        renderQuiz(mockdata);
      }

      // Function to render MCQs inside #quiz div
      function renderQuiz(mcqs) {
        footer.style.display = "block";
        let waitmseg = document.getElementById("waitmseg").style.display = "none";
        const quizContainer = document.getElementById("quiz");
        quizContainer.innerHTML = ""; // clear previous content
        document.querySelector(".container").style.display = "block";
        mcqs.forEach((q, index) => {
          const questionDiv = document.createElement("div");
          questionDiv.classList.add("question");

          // Question text
          const questionTitle = document.createElement("h3");
          questionTitle.textContent = `${index + 1}. ${q.question}`;
          questionDiv.appendChild(questionTitle);

          // Options
          const optionsDiv = document.createElement("div");
          optionsDiv.classList.add("options");

          q.options.forEach((opt, i) => {
            const optionLabel = String.fromCharCode(65 + i); // A, B, C, D
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

      // Function to calculate score
      function submitTest() {
        let score = 0;

        mockdata.forEach((q, index) => {
          const selected = document.querySelector(`input[name="q_${index}"]:checked`);
          const options = document.querySelectorAll(`input[name="q_${index}"]`);

          options.forEach(opt => {
            const label = opt.parentElement;
            label.classList.remove("correct", "wrong");

            if (opt.value === q.answer) {
              label.classList.add("correct"); // mark correct answer
            }
          });

          if (selected) {
            if (selected.value === q.answer) {
              score++;
            } else {
              selected.parentElement.classList.add("wrong"); // mark wrong answer
            }
          }
        });
        //disable all radio btns
        document.querySelectorAll('input[type="radio"]').forEach(r => r.disabled = true);

        const resultDiv = document.getElementById("result");
        resultDiv.textContent = `You scored ${score} / ${mockdata.length}`;
      }

      // Event listener for submit button
      document.getElementById("submit").addEventListener("click", submitTest);

      // Fetch MCQs on page load
      fetchTestData();


    </script>
  </body>

  </html>
  <?php
} else {
  header("location:./auth.php");
}
?>