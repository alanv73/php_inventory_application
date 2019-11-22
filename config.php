<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // database connection variables
    $dbservername = "localhost";
    $dbusername = "avanart58";
    $dbpassword = "southhills#";
    $dbname = "avanart58";

    // Create connection
    $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!$conn) {
        echo "The database is temporarily unavailable";
    }
?>
