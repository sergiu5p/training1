<?php
    require_once "common.php";

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        header("location: login.php");
        exit();
    }

    if (isset($_GET["delete"])) {
        $id = strip_tags($_GET["delete"]);
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
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
    <a href="login.php?logout"><?= trans("Logout") ?></a>
    <?php if (mysqli_num_rows($result)): ?>
            <?php while ( $row = $result->fetch_assoc() ):  ?>
                <div>
                    <img alt="<?= htmlspecialchars($row['title'])?>" src="img/<?= htmlspecialchars($row['id']) ?>.jpg" width="150" height="150">
                    <h4><?= htmlspecialchars($row["title"]) ?></h4>
                    <p><?= htmlspecialchars($row["description"]) ?></p>
                    <h4><?= htmlspecialchars($row["price"]) ?> $</h4>
                    <a href="product.php?id=<?= htmlspecialchars($row['id'])?>"><?= trans("Edit") ?></a>
                    <a href="products.php?id=<?= htmlspecialchars($row['id']) ?>"><?= trans("Delete") ?></a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <?= "No product" ?>
        <?php endif;?>
        <br>
        <a href="<?= htmlspecialchars('product.php') ?>"><?= trans("Add") ?></a>
    </body>
</html>