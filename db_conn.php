<?php
    
    // DATABASE CONNECTION
    $servername = "localhost";
    $username   = "root";
    $password   = "mindfire";
    $database   = "registration";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    
    // Check connection and redirect to registration page if there's error in conn.
    if ($conn->connect_error) {
        header("Location:registration_form.php?Message=" . " " . $conn->connect_error);
        exit();
    }
?>