<?php
    session_start();
    require_once "common.php";

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        header("location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= trans("product") ?></title>
    </head>
    <body>
        <a href="login.php?logout"><?= trans("Logout") ?></a>

    </body>
</html>
