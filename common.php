<?php
    // Keep the cart as an array of product ids (or ids => quantities) in the Session.
    require_once "config.php";

    // Define translate function
    function trans($data)
    {
        return $data;
    }

    $serverName = constant("server");
    $userName = constant("dbUsername");
    $password = constant("dbPassword");
    $dbname = constant("dbName");

    // Read the file containing ids products
    $file = "ids.txt";
    //$fileContent = file_get_contents($file);

    // Create connection to database
    $conn = new mysqli($serverName, $userName, $password, $dbname) or die($conn->connect_error);

    // Select all the products
    $selectAll = $conn->query("SELECT * FROM products");

    // Fill the session cartIds array with ids
    while ($row = $selectAll->fetch_assoc()) {
        file_put_contents($file, $row['id']);
    }

    echo file_get_contents[$file];
