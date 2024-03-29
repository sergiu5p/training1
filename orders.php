<?php
    require_once "common.php";

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        header("location: login.php");
        exit();
    }

    $rows = [];

    // MAX 120 characters in one line
    $result = $conn->query("SELECT orders.*, SUM(price) AS summed_price FROM order_product JOIN orders ON 
        order_product.order_id=orders.id JOIN products ON order_product.product_id=products.id GROUP BY 
        order_product.order_id") or die($conn->error);

    if (mysqli_num_rows($result)) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="style.css">
        <meta charset="UTF-8">
        <title><?= trans("orders") ?></title>
    </head>
    <body>
        <ul>
            <li><a href="login.php?logout"><?= trans("Logout") ?></a></li>
            <li><a href="products.php">products.php</a></li>
            <li><a href="index.php">index.php</a></li>
            <?php if (isset($_SESSION["cartIds"]) && $_SESSION["cartIds"]): ?>
                <li><a href="cart.php"><?= trans("Go to cart") ?></a></li>
            <?php else: ?>
                <li><?= trans("Cart is empty"); ?></li>
            <?php endif; ?>
        </ul>
        <?php foreach ($rows as $row): ?>
            <div class="order">
                <h4><?= trans("Name: ").$row["name"] ?></h4>
                <h4><?= trans("E-mail: ").$row["email"] ?></h4>
                <h4><?= trans("Comments: ").$row["comments"] ?></h4>
                <h4><?= trans("Summed price: ").strval($row["summed_price"]) ?> $</h4>
                <h4><?= trans("Creation date: ").$row["creation_date"] ?></h4>
                <a href="order.php?id=<?= $row["id"] ?>"><?= trans("View") ?></a>
            </div>
        <?php endforeach; ?>
    </body>
</html>