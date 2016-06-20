<?php
    //Enable error reporting
    ini_set('error_reporting', E_ALL);
    
    // DATABASE CONNECTION
    $servername = "localhost";
    $username   = "root";
    $password   = "mindfire";
    $database   = "registration";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    
    // Check connection and redirect to registration page if there's error in conn.
    if ($conn->connect_error) {
        header("Location:registration_form.php?dbErr=1");
        exit();
    }
?>