<?php
    require_once "common.php";
    require_once "config.php";

    if (isset($_GET["id"])) {
        unset($_SESSION["cartIds"][array_search($_GET["id"], $_SESSION["cartIds"])]);
    }

    if (!isset($_SESSION["cartIds"]) || !count($_SESSION["cartIds"])) {
        header("location: index.php");
        exit();
    } else {
        $in = join(',', array_fill(0, count($_SESSION["cartIds"]), '?'));
        $query = "SELECT * FROM products WHERE id IN ($in) ORDER BY id";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat('i', count($_SESSION["cartIds"])), ...$_SESSION["cartIds"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        if (mysqli_num_rows($result)) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
    }

    if (isset($_POST["checkout"])) {
        $name = strip_tags($_POST["name"]);
        $email = strip_tags($_POST["email"]);
        $comments = strip_tags($_POST["comments"]);
        $message_products = "";

        while ($row = $result->fetch_assoc()) {
            $message_products.="<h4>".$row['title']."</h4>";
            $message_products.="<h4>".$row["description"]."</h4>";
            $message_products.="<h4>".$row["price"]." $</h4>";
        }

        $_SESSION["message"] = $message_products."<h4>".$name."</h4>"."<h4>".$email."</h4>"."<h4>".$comments."</h4>";
        $to = ADMIN_EMAIL;
        $sub = trans("New order");
        $msg = $_SESSION["message"];
        $headers = "From: ".$email."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail($to, $sub, $msg, $headers);
        session_destroy();
        header("location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="UTF-8">
        <title><?= trans("Cart") ?></title>
    </head>
    <body>
        <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]): ?>
            <ul>
                <li><a href="login.php?logout"><?= trans("Logout") ?></a></li>
                <li><a href="products.php">products.php</a></li>
            </ul>
        <?php else: ?>
            <ul>
                <li>
                    <a href="login.php"><?= trans("Login") ?></a>
                </li>
                <li>
                    <a href="<?= htmlspecialchars('index.php') ?>"><?= trans("index.php") ?></a>
                </li>
            </ul>
        <?php endif; ?>
        <div>
            <?php foreach ($rows as $row): ?>
                <div>
                    <img alt="<?= htmlspecialchars($row['title']) ?>" src="img/<?= htmlspecialchars($row['id']) ?>.jpg" width="150" height="150">
                    <h4><?= htmlspecialchars($row["title"]) ?></h4>
                    <p><?= htmlspecialchars($row["description"]) ?></p>
                    <h4><?= htmlspecialchars($row["price"]) ?> $</h4>
                    <a href="cart.php?id=<?= htmlspecialchars($row['id']) ?>">Remove</a>
                </div>
            <?php endforeach; ?>
        </div>
        <form action="cart.php" method="POST">
            <input type="text" name="name" placeholder="<?= trans("Name") ?>" required>
            <br>
            <br>
            <input type="email" name="email" placeholder="<?= trans("E-mail") ?>" required>
            <br>
            <br>
            <input type="text" name="comments" placeholder="<?= trans("Comments") ?>">
            <br>
            <br>
            <button name="checkout"><?= trans("Checkout") ?></button>
        </form>
    </body>
</html>