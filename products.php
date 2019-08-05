<?php
    require_once "common.php";

    unset($_SESSION["errors"]);

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        header("location: login.php");
        exit();
    }

    // delete product
    if (isset($_GET["id"])) {
        $id = strip_tags($_GET["id"]);
        $fileType = ["jpg", "jpeg", "png"];
        // Sorry, only JPG, JPEG & PNG files are allowed

        foreach ($fileType as $e) {
            @unlink("img/$id.$e");
        }

        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute() or die($conn->error);
        $result = $stmt->get_result();
    }

    // select all the products
    $query = "SELECT * FROM products";
    $result = $conn->query($query) or die($conn->error);

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
        <title><?= trans("Products") ?></title>
    </head>
    <body>
        <ul>
            <li><a href="login.php?logout"><?= trans("Logout") ?></a></li>
            <li><a href="index.php"><?= trans("index.php") ?></a></li>
            <li><a href="orders.php"><?= trans("orders.php") ?></a></li>
            <?php if (isset($_SESSION["cartIds"]) && $_SESSION["cartIds"]): ?>
                <li><a href="cart.php"><?= trans("Go to cart") ?></a></li>
            <?php else: ?>
                <li><?= trans("Cart is empty"); ?></li>
            <?php endif; ?>
        </ul>
            <?php foreach ($rows as $row): ?>
                <div>
                    <img alt="<?= htmlspecialchars($row["title"])?>" src="img/<?= $row["id"] ?>" width="150" height="150">
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