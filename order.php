<?php
    require_once "common.php";

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        header("location: login.php");
        exit();
    }

    if (!isset($_GET["id"])) {
        header("location: login.php");
        exit();
    }

    $rows = [];
    $id = strip_tags($_GET["id"]);
    $query = "SELECT products.title, orders.name, orders.email, orders.comments FROM order_product RIGHT 
        JOIN products ON order_product.productID=products.id RIGHT JOIN orders ON order_product.orderID=orders.Oid 
        WHERE order_product.orderID=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute() or die($conn->error);
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="style.css">
        <meta charset="UTF-8">
        <title><?= trans("order") ?></title>
    </head>
    <body>
        <ul>
            <li><a href="login.php?logout"><?= trans("Logout") ?></a></li>
            <li><a href="products.php"><?= trans("products.php") ?></a></li>
            <li><a href="index.php"><?= trans("index.php") ?></a></li>
            <li><a href="orders.php"><?= trans("orders.php") ?></a></li>
            <?php if (isset($_SESSION["cartIds"]) && $_SESSION["cartIds"]): ?>
                <li><a href="cart.php"><?= trans("Go to cart") ?></a></li>
            <?php else: ?>
                <li><?= trans("Cart is empty"); ?></li>
            <?php endif; ?>
        </ul>
        <div class="order">
            <h4><?= trans("Name: ").$rows[0]["name"]?></h4>
            <h4><?= trans("E-mail: ").$rows[0]["email"]?></h4>
            <h4><?= trans("Comments: ").$rows[0]["comments"] ?></h4>
            <?php foreach ($rows as $row): ?>
                <h4><?= $row["title"] ?></h4>
            <?php endforeach; ?>
        </div>
    </body>
</html>