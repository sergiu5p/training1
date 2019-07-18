<?php
    // Keep the cart as an array of product ids (or ids => quantities) in the Session.
    require_once "config.php";

    // Define translate function
    function trans($data)
    {
        return $data;
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
     }

     // Create connection to database
    $conn = new mysqli(SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME) or die($conn->connect_error);
