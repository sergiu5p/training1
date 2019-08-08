<?php
    require_once "common.php";
    require_once "config.php";

    if (isset($_GET["logout"])) {
        unset($_SESSION["logged_in"]);
        header("location: login.php");
        exit();
    }

    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
        header("location: products.php");
        exit();
    }

    if (isset($_POST["login"])) {
        $username = strip_tags($_POST["username"]);
        $password = strip_tags($_POST["password"]);

        if ($username == ADMIN_USERNAME && $password == ADMIN_PASSWORD) {
            $_SESSION["logged_in"] = true;
            header("location: products.php");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="UTF-8">
        <title><?= trans("login") ?></title>
    </head>
    <body>
        <ul>
            <li><a href="index.php"><?= trans("index.php") ?></a></li>
            <?php if (isset($_SESSION["cartIds"]) && $_SESSION["cartIds"]): ?>
                <li><a href="cart.php"><?= trans("Go to cart") ?></a></li>
            <?php else: ?>
                <li><?= trans("Cart is empty"); ?></li>
            <?php endif; ?>
        </ul>
        <div>
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="<?= trans("Username") ?>" required>
                <input type="password" name="password" placeholder="<?= trans("Password") ?>" required>
                <button name="login"><?= trans("Login") ?></button>
            </form>
        </div>
    </body>
</html>