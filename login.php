<?php
    session_start();
    require_once "config.php";
    require_once "common.php";
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
                <input type="submit" name="login" placeholder="<?= trans("Login") ?>">
            </form>
        </div>
    </body>
</html>