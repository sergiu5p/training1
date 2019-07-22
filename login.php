<?php
    require_once "common.php";
    require_once "config.php";
?>
<!DOCTYPE html>
<html lane="en">
    <head>
        <meta charset="UTF-8">
        <title><?= trans("login") ?></title>
    </head>
    <body>
        <div>
            <form action="">
                <input type="text" name="userName" placeholder="<?= trans("Username") ?>">
                <input type="password" name="<?= trans("Password") ?>">
            </form>
        </div>
    </body>
</html>