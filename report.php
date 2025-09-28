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

// Helper to show JSON as bullet points
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
    <title>SkillScan Reports</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        /* ---------- GENERAL ---------- */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #0a0a0a;
            color: #fff;
        }

        /* ---------- SIDEBAR ---------- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid rgba(0, 255, 255, 0.5);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
        }

        .sidebar .logo {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 40px;
            text-align: center;
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
        }

        .sidebar .logo a {
            color: inherit;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .sidebar ul.menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul.menu li {
            margin-bottom: 20px;
        }

        .sidebar ul.menu li a {
            color: #fff;
            display: block;
            padding: 8px 12px;
            border-radius: 10px;
            transition: 0.3s;
            border: 1px solid rgba(0, 255, 255, 0.3);
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.2);
        }

        .sidebar ul.menu li a:hover,
        .sidebar ul.menu li a.active {
            color: #0ff;
            border: 1px solid #0ff;
            box-shadow: 0 0 10px #0ff, 0 0 20px #0ff;
        }

        /* Logout button */
        .sidebar .logout a {
            display: block;
            text-align: center;
            padding: 10px;
            border-radius: 10px;
            background: rgba(0, 255, 255, 0.1);
            color: #0ff;
            border: 1px solid rgba(0, 255, 255, 0.5);
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
            transition: 0.3s;
        }

        .sidebar .logout a:hover {
            background: rgba(0, 255, 255, 0.2);
            box-shadow: 0 0 15px #0ff, 0 0 30px #0ff;
        }

        /* ---------- MAIN CONTENT ---------- */
        .main-content {
            margin-left: 240px;
            padding: 30px;
        }

        /* Topbar */
        .topbar h2 {
            color: #0ff;
            text-shadow: 0 0 10px #0ff, 0 0 20px #0ff;
        }

        /* Report container */
        .report-container {
            padding: 20px;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 15px;
            border: 1px solid rgba(0, 255, 255, 0.4);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.3);
        }

        /* Section titles */
        .report-section h3 {
            color: #0ff;
            text-shadow: 0 0 6px #0ff, 0 0 12px rgba(0, 255, 255, 0.5);
            border-bottom: 1px solid rgba(0, 255, 255, 0.4);
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        /* Lists */
        .report-section ul {
            padding-left: 20px;
            list-style-type: "✔ ";
            color: #fff;
        }

        .report-section ul li {
            margin-bottom: 6px;
        }

        /* ATS Score */
        .ats-score {
            font-size: 24px;
            font-weight: bold;
            color: #0ff;
            text-shadow: 0 0 8px #0ff, 0 0 15px rgba(0, 255, 255, 0.7);
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                flex-direction: row;
                justify-content: space-around;
            }

            .sidebar ul.menu {
                flex-direction: row;
                gap: 10px;
            }

            .report-container {
                margin: 10px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <a href="index.php">SkillScan</a>
        </div>
        <ul class="menu">
            <li><a href="dashboard.php">🏠 Dashboard</a></li>
            <li><a href="resumeanalysis.php">📄 Resume Analysis</a></li>
            <li><a href="test.php">📝 Take Test</a></li>
            <li><a href="#" class="active">📊 Reports</a></li>
        </ul>
        <div class="logout">
            <a href="logout.php">🚪 Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <h2>📊 Report for <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        </header>

        <div class="report-container">
            <div class="report-section">
                <h3>Skills</h3>
                <p>
                    <?php
                    $skillsArr = json_decode($skills, true);
                    if (is_array($skillsArr)) {
                        echo '<ul>';
                        foreach ($skillsArr as $skill) {
                            echo '<li>' . htmlspecialchars($skill) . '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo htmlspecialchars($skills);
                    }
                    ?>
                </p>
            </div>

            <div class="report-section">
                <h3>Experience</h3>
                <?php echo printJsonAsList($experience); ?>
            </div>

            <div class="report-section">
                <h3>Education</h3>
                <?php echo printJsonAsList($education); ?>
            </div>

            <div class="report-section">
                <h3>Projects</h3>
                <?php echo printJsonAsList($projects); ?>
            </div>

            <div class="report-section">
                <h3>Contact Info</h3>
                <?php echo printJsonAsList($contactinfo); ?>
            </div>

            <div class="report-section">
                <h3>ATS Score</h3>
                <p class="ats-score"><?php echo htmlspecialchars($ats); ?>%</p>
            </div>
        </div>
    </main>
</body>

</html>