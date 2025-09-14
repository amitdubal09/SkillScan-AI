<?php
    $conn=mysqli_connect("localhost","root", "","skillscan");

    if(mysqli_connect_error()){
        echo"<script>alert('cannot connect with database')</script>";
        exit();
    }
?>