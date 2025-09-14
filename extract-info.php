<?php
require 'connection.php';
session_start();

// Get raw POST data
$json = file_get_contents("php://input");
$data = json_decode($json, true); // true = associative array

if (isset($_SESSION['username']) && isset($data['saveResumeData'])) {
    $username = $_SESSION['username'];

    // Extract values
    $name = $data['name'] ?? '';
    $contactinfo = json_encode($data['contactinfo'] ?? []); 
    $skills = json_encode($data['skills'] ?? []);
    $projects = json_encode($data['projects'] ?? []);
    $education = json_encode($data['education'] ?? []);
    $experience = json_encode($data['experience'] ?? []);
    $ats = (int)($data['atsscore'] ?? 0);

    // Escape values
    $name = mysqli_real_escape_string($conn, $name);
    $contactinfo = mysqli_real_escape_string($conn, $contactinfo);
    $skills = mysqli_real_escape_string($conn, $skills);
    $projects = mysqli_real_escape_string($conn, $projects);
    $education = mysqli_real_escape_string($conn, $education);
    $experience = mysqli_real_escape_string($conn, $experience);
    $username = mysqli_real_escape_string($conn, $username);

    // Check if user already exists
    $query = "SELECT * FROM `extracted_information` WHERE `username` = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // ✅ Update existing record
        echo "UPDATE";
        $sql = "UPDATE `extracted_information` SET 
            name='$name', 
            skills='$skills', 
            project='$projects', 
            education='$education', 
            experience='$experience', 
            ats='$ats', 
            contactinfo='$contactinfo'
            WHERE username='$username'";
    } else {
        // ✅ Insert new record
        echo "INSERT";
        $sql = "INSERT INTO `extracted_information` 
            (name, skills, project, education, experience, ats, contactinfo, username) 
            VALUES 
            ('$name', '$skills', '$projects', '$education', '$experience', '$ats', '$contactinfo', '$username')";
    }

    // Run query
    if (mysqli_query($conn, $sql)) {
        echo "Data stored successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
