<?php
    session_start();
    require_once "config.php";
    require_once "common.php";

    if (isset($_POST["login"])) {
        if ($_POST["username"] == USER_NAME && $_POST["password"] == USER_PASSWORD) {
            $_SESSION["logged_in"] = true;
        } else {
            echo "Wrong user name or password!";
        }
    }

    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] && isset($_POST["checkout"])) {
        // send email to the admin
        $name = test_input($_POST["name"]);
        $email = test_input($_POST["email"]);
        $comments = test_input($_POST["comments"]);
        $message_products = "";
        $query = "SELECT * FROM products WHERE id IN (".implode(",", $_SESSION["cartIds"]).")";
        $result = $conn->query($query) or die($conn->error);
        while ($row = $result->fetch_assoc()) {
            $message_products.="<h4>".$row['title']."</h4>";
            $message_products.="<h4>".$row["description"]."</h4>";
            $message_products.="<h4>".$row["price"]." $</h4>";
        }
        $_SESSION["message"] = $message_products."<h4>".$name."</h4>"."<h4>".$email."</h4>"."<h4>".$comments."</h4>";
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
                <button name="login"><?= trans("Log in") ?></button>
            </form>
        </div>
    </body>
</html>