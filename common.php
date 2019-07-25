<?php
    session_start();
    require_once "config.php";

     // Create connection to database
    $conn = new mysqli(SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME) or die($conn->connect_error);

    // For prevent sql injection
    function sqlInjection($data) {
        $data = strip_tags($data);
        $data = mysqli_real_escape_string($GLOBALS['$conn'], $data);
        return $data;
    }

    // Define translate function
    function trans($data) {
        $data = htmlspecialchars($data);
        return $data;
    }
