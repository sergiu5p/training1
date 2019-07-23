<?php
    session_start();
    require_once "common.php";

    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] == false) {
        header("location: login.php");
        exit();
    }

    if (isset($_GET["delete"])) {
        $id = test_input($_GET["delete"]);
        $query = "DELETE FROM products WHERE id = $id";
        $conn->query($query) or die($conn->error);
        header("location: products.php  ");
    }

    // select all the products
    $query = "SELECT * FROM products";
    $result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= trans("Products") ?></title>
    </head>
    <body>
        <?php if (mysqli_num_rows($result)): ?>
            <?php while ( $row = $result->fetch_assoc() ):  ?>
                <div>
                    <img alt="<?= test_input($row['title'])?>" src="img/<?= test_input($row['id']) ?>.jpg" width="150" height="150">
                    <h4><?= $row["title"] ?></h4>
                    <p><?= $row["description"] ?></p>
                    <h4><?= $row["price"] ?> $</h4>
                    <a href="product.php"><?= trans("Edit") ?></a>
                    <a href="products.php?delete=<?= test_input($row['id']) ?>"><?= trans("Delete") ?></a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <?= "No product" ?>
        <?php endif;?>
    </body>
</html>


