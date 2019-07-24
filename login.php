<?php
    session_start();
    require_once "config.php";
    require_once "common.php";

    if (isset($_GET["logout"])) {
        unset($_SESSION["logged_in"]);
        header("location: login.php");
    }

    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
        header("location: products.php");

    }

    if (isset($_POST["login"])) {
        $username = test_input($_POST["username"]);
        $password = test_input($_POST["password"]);

        if ($username == ADMIN_USERNAME && $password == ADMIN_PASSWORD) {
            $_SESSION["logged_in"] = true;
            header("location: products.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= trans("login") ?></title>
    </head>
    <body>
        <div>
            <form action="<?= test_input("login.php") ?>" method="POST">
                <input type="text" name="username" placeholder="<?= trans("Username") ?>" required>
                <input type="password" name="password" placeholder="<?= trans("Password") ?>" required>
                <button name="login"><?= trans("Login") ?></button>
            </form>
        </div>
    </body>
</html>