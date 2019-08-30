<?php
    require_once "common.php";

    unset($_SESSION["errors"]);

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        header("location: login.php");
        exit();
    }

    $rows = [];

    // delete product
    if (isset($_GET["id"])) {
        $id = strip_tags($_GET["id"]);

        // Get image extension
        $query = "SELECT image_extension FROM products WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute() or die($conn->error);

        // Delete the image associated with that product
        unlink("img/".$id.".".$stmt->get_result()->fetch_assoc()["image_extension"]);

        // Delete all foreign keys associated with that product
        $query = "DELETE FROM order_product WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute() or die($conn->error);

        // Delete the product
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute() or die($conn->error);

        // redirect user to the same page but without id URL variable
        header("location: products.php");
        exit();
    }

    // select all the products
    $query = "SELECT * FROM products ORDER BY id";
    $result = $conn->query($query) or die($conn->error);

    if (mysqli_num_rows($result)){
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
        <title><?= trans("Products") ?></title>
    </head>
    <body>
        <ul>
            <li><a href="login.php?logout"><?= trans("Logout") ?></a></li>
            <li><a href="index.php">index.php</a></li>
            <li><a href="orders.php">orders.php</a></li>
            <?php if (isset($_SESSION["cartIds"]) && $_SESSION["cartIds"]): ?>
                <li><a href="cart.php"><?= trans("Go to cart") ?></a></li>
            <?php else: ?>
                <li><?= trans("Cart is empty"); ?></li>
            <?php endif; ?>
        </ul>
            <?php foreach ($rows as $row): ?>
                <div>
                    <img alt="<?= htmlspecialchars($row["title"])?>" src="img/<?= $row["id"].".".$row["image_extension"] ?>" width="150" height="150">
                    <h4><?= htmlspecialchars($row["title"]) ?></h4>
                    <p><?= htmlspecialchars($row["description"]) ?></p>
                    <h4><?= htmlspecialchars($row["price"]) ?> $</h4>
                    <a href="product.php?id=<?= $row["id"] ?>"><?= trans("Edit") ?></a>
                    <a href="products.php?id=<?= $row["id"] ?>"><?= trans("Delete") ?></a>
                </div>
            <?php endforeach; ?>
        <br>
        <a href="product.php"><?= trans("Add") ?></a>
    </body>
</html>