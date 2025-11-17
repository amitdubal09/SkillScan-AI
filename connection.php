<?php
$conn = mysqli_connect("localhost", "root", "", "skill_scan");

if (mysqli_connect_error()) {
    echo "<script>alert('cannot connect with database')</script>";
    exit();
}
?>