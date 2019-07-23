<?php
    session_start();
    require_once "common.php";

    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] == false) {
        header("location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    </head>
    <body>

    </body>
</html>
