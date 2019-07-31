<?php
    session_start();
    require_once "config.php";

     // Create connection to database
    $conn = new mysqli(SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME) or die($conn->connect_error);


    // Define translate function
    function trans($data)
    {
        return $data;
    }
