<?php

    require_once "config.php";

    $serverName = constant("server");
    $userName = constant("dbUsername");
    $password = constant("dbPassword");
    $dbname = constant("dbName");

    // Create connection to database
    $conn = new mysqli($serverName, $userName, $password, $dbname) or die($conn->connect_error);
