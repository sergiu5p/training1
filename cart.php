<?php
    require_once "common.php";
    require_once "config.php";

    if (isset($_GET["id"])) {
        $index = array_search($_GET["id"], $_SESSION["cartIds"]);

        if ($index !== false){
            unset($_SESSION["cartIds"][$index]);
        }
    }

    if (!isset($_SESSION["cartIds"]) || !count($_SESSION["cartIds"])) {
        header("location: index.php");
        exit();
    }

    $in = join(",", array_fill(0, count($_SESSION["cartIds"]), "?"));
    $query = "SELECT * FROM products WHERE id IN ($in) ORDER BY id";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat("i", count($_SESSION["cartIds"])), ...$_SESSION["cartIds"]);
    $stmt->execute() or die($conn->error);
    $result = $stmt->get_result();
    $rows = [];

    $protocol = "http";

    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $protocol .= "s";
    }

    if (isset($_POST["checkout"])) {
        // MAX 120 characters in one line
        $query = "INSERT INTO orders (name, email, comments, creation_date) 
            VALUES 
            (?, ?, ?, CURDATE())";
        $name = strip_tags($_POST["name"]);
        $email = strip_tags($_POST["email"]);
        $comments = strip_tags($_POST["comments"]);
        $message_products = "";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $name, $email, $comments);
        $stmt->execute() or die($conn->error);

        // select the last order id
        $lastID = $conn->query("SELECT LAST_INSERT_ID()")->fetch_assoc()["LAST_INSERT_ID()"];

        while ($row = $result->fetch_assoc()) {
            $conn->query("INSERT INTO order_product (order_id, product_id) VALUES ($lastID, {$row['id']})") or die($conn->error);
            $image = "<img src=".'"'.$protocol."://".$_SERVER['HTTP_HOST']."/img/".$row['id'].'.'.$row["image_extension"].'"'.">";
            $message_products .= $image;
            $message_products.="<h4>".$row["title"]."</h4>";
            $message_products.="<h4>".$row["description"]."</h4>";
            $message_products.="<h4>".$row["price"]." $</h4>";
        }

        $_SESSION["message"] = "<h4>".$message_products."<h4>";
        $_SESSION["message"] .= "<h4>".$name."</h4>";
        $_SESSION["message"] .= "<h4>".$email."</h4>";
        $_SESSION["message"] .= "<h4>".$comments."</h4>";
        $to = ADMIN_EMAIL;
        $sub = trans("New order");
        $msg = $_SESSION["message"];
        $headers = "From: ".$email."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail($to, $sub, $msg, $headers);
        unset($_SESSION["cartIds"]);
        header("location: index.php");
        exit();
    }

    if (mysqli_num_rows($result)) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
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
        <ul>
            <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]): ?>
                <li><a href="login.php?logout"><?= trans("Logout") ?></a></li>
                <li><a href="products.php">products.php</a></li>
                <li><a href="orders.php">orders.php</a></li>
            <?php else: ?>
                    <li><a href="login.php"><?= trans("Login") ?></a></li>
            <?php endif; ?>
            <li>
                <a href="index.php">index.php</a>
            </li>
        </ul>
        <div>
            <?php foreach ($rows as $row): ?>
                <div>
                    <img alt="<?= htmlspecialchars($row["title"]) ?>" src="img/<?= $row["id"].".".$row["image_extension"] ?>" width="150" height="150">
                    <h4><?= htmlspecialchars($row["title"]) ?></h4>
                    <p><?= htmlspecialchars($row["description"]) ?></p>
                    <h4><?= htmlspecialchars($row["price"]) ?> $</h4>
                    <a href="cart.php?id=<?= $row["id"] ?>"><?= trans("Remove") ?></a>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="POST">
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