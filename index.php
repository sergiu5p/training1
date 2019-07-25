<?php
    require_once "common.php";

    if ( isset($_GET["id"]) ) {
        $_SESSION["cartIds"][] = strip_tags($_GET["id"]);
        header("location: index.php");
    }

    // select products that are not in the cart
    if (isset($_SESSION["cartIds"]) && $_SESSION["cartIds"]) {
        $in = join(',', array_fill(0, count($_SESSION["cartIds"]), '?'));
        $query = "SELECT * FROM products WHERE id NOT IN ($in)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat('i', count($_SESSION["cartIds"])), ...$_SESSION["cartIds"]);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $query = "SELECT * FROM products";
        $result = $conn->query($query);
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= trans("store") ?></title>
    </head>
    <body>
        <a href="login.php"><?= trans("Login") ?></a>
        <div>
            <?php if (mysqli_num_rows($result)): ?>
                <?php while ( $row = $result->fetch_assoc() ):  ?>
                    <div>
                        <img alt="<?= htmlspecialchars($row["title"]) ?>" src="img/<?= htmlspecialchars($row["id"]) ?>" width="150" height="150">
                        <h4><?= htmlspecialchars($row["title"]) ?></h4>
                        <p><?= htmlspecialchars($row["description"]) ?></p>
                        <h4><?= htmlspecialchars($row["price"]) ?> $</h4>
                        <a href="index.php?id=<?= htmlspecialchars($row['id']) ?>"><?= trans("Add") ?></a>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
            <?php if (isset($_SESSION["cartIds"]) && $_SESSION["cartIds"]): ?>
                <a href="<?= htmlspecialchars("cart.php") ?>"><?= trans("Go to cart") ?></a>
            <?php else: ?>
                <?= trans("Cart is empty"); ?>
            <?php endif; ?>
        </div>
    </body>
</html>