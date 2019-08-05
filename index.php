<?php
    require_once "common.php";

    if (isset($_GET["id"])) {
        $_SESSION["cartIds"][] = strip_tags($_GET["id"]);
    }

    // select products that are not in the cart
    if (isset($_SESSION["cartIds"]) && $_SESSION["cartIds"]) {
        $in = join(',', array_fill(0, count($_SESSION["cartIds"]), "?"));
        $query = "SELECT * FROM products WHERE id NOT IN ($in) ORDER BY id";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat("i", count($_SESSION["cartIds"])), ...$_SESSION["cartIds"]);
        $stmt->execute() or die($conn->error);
        $result = $stmt->get_result();
    } else {
        $result = $conn->query("SELECT * FROM products") or die($conn->error);
    }

    $rows = [];
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
        <title><?= trans("store") ?></title>
    </head>
    <body>
        <ul>
            <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]): ?>
                <li><a href="login.php?logout"><?= trans("Logout") ?></a></li>
                <li><a href="products.php"><?= trans("products.php") ?></a></li>
                <li><a href="orders.php"><?= trans("orders.php") ?></a></li>
            <?php else: ?>
                <li><a href="login.php"><?= trans("Login") ?></a></li>
            <?php endif; ?>
            <?php if (isset($_SESSION["cartIds"]) && $_SESSION["cartIds"]): ?>
                <li><a href="cart.php"><?= trans("Go to cart") ?></a></li>
            <?php else: ?>
                <li><?= trans("Cart is empty"); ?></li>
            <?php endif; ?>
        </ul>
        <div>
            <?php foreach ($rows as $row): ?>
                <div>
                    <img alt="<?= htmlspecialchars($row["title"]) ?>" src="img/<?= $row["id"] ?>" width="150" height="150">
                    <h4><?= htmlspecialchars($row["title"]) ?></h4>
                    <p><?= htmlspecialchars($row["description"]) ?></p>
                    <h4><?= htmlspecialchars($row["price"]) ?> $</h4>
                    <a href="index.php?id=<?= $row["id"] ?>"><?= trans("Add") ?></a>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>