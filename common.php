<?php

    require_once "config.php";

    // define translate function
    function trans($data)
    {
        return $data;
    }

    // create the cart array and add it in Session
    $cartArray = [];
    $ids = join("','", $cartArray);
    $_SESSION["cartIds"] = $ids;

    $serverName = constant("server");
    $userName = constant("dbUsername");
    $password = constant("dbPassword");
    $dbname = constant("dbName");

    // Create connection to database
    $conn = new mysqli($serverName, $userName, $password, $dbname) or die($conn->connect_error);

    // Select all the products not in cart Array
    $statement = $conn->prepare("SELECT * FROM products WHERE id NOT IN (?)");
    $statement->bind_param("s", $_SESSION["cartIds"]);
    $statement->execute();
    $result = $statement->get_result();

