<?php
require 'connection.php';
session_start();

// Get raw POST data
$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (isset($_SESSION['username']) && isset($data['saveResumeData'])) {
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);

    // Extract and escape values
    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');
    $contactinfo = mysqli_real_escape_string($conn, json_encode($data['contactinfo'] ?? []));
    $skills = mysqli_real_escape_string($conn, json_encode($data['skills'] ?? []));
    $projects = mysqli_real_escape_string($conn, json_encode($data['projects'] ?? []));
    $education = mysqli_real_escape_string($conn, json_encode($data['education'] ?? []));
    $experience = mysqli_real_escape_string($conn, json_encode($data['experience'] ?? []));
    $atsscore = (int) ($data['ats'] ?? 0);

    // Check if user exists
    $query = "SELECT * FROM `extracted_information` WHERE `username` = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Update existing record
        $sql = "UPDATE `extracted_information` SET 
            name='$name', 
            skills='$skills', 
            projects='$projects', 
            education='$education', 
            experience='$experience', 
            ats='$atsscore', 
            contactinfo='$contactinfo'
            WHERE username='$username'";
    } else {
        // Insert new record
        $sql = "INSERT INTO `extracted_information` 
            (username, name, skills, projects, education, experience, ats, contactinfo) 
            VALUES 
            ('$username', '$name', '$skills', '$projects', '$education', '$experience', '$ats', '$contactinfo')";
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