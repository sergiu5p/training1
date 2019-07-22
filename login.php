<?php
    session_start();
    require_once "config.php";
    require_once "common.php";

    if (isset($_POST["checkout"])) {
        $name = test_input($_POST["name"]);
        $email = test_input($_POST["email"]);
        $comments = test_input($_POST["comments"]);
    }

    if (isset($_POST["login"])) {
        if ($_POST["username"] == USER_NAME && $_POST["password"] == USER_PASSWORD) {
            $_SESSION["logged_in"] = true;
        } else {
            echo "Wrong user name or password!";
        }
    }

    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
        // send email to the admin
    }
?>
<!DOCTYPE html>
<html lane="en">
    <head>
        <meta charset="UTF-8">
        <title><?= trans("login") ?></title>
    </head>
    <body>
        <div>
            <form action="<?= test_input("login.php") ?>" method="POST">
                <input type="text" name="username" placeholder="<?= trans("Username") ?>" required>
                <input type="password" name="password" placeholder="<?= trans("Password") ?>" required>
                <button name="login"><?= trans("Log in") ?></button>
            </form>
        </div>
    </body>
</html>