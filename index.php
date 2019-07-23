<?php
    session_start();
    require_once "common.php";

    if ( isset($_GET["add"]) ) {
        $_SESSION["cartIds"][] = $_GET["add"];
        header("location: index.php");
    }

    // select products that are not in the cart
    if (isset($_SESSION["cartIds"]) && $_SESSION["cartIds"]) {
        $in = join(',', array_fill(0, count($_SESSION["cartIds"]), '?'));
        $query = "SELECT * FROM products WHERE id NOT IN ($in)";
        $statement = $conn->prepare($query);
        $statement->bind_param(str_repeat('i', count($_SESSION["cartIds"])), ...$_SESSION["cartIds"]);
        $statement->execute();
        $result = $statement->get_result();
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
        <div>
            <?php if (mysqli_num_rows($result)): ?>
                <?php while ( $row = $result->fetch_assoc() ):  ?>
                    <div>
                        <img alt="<?= test_input($row['title'])?>" src="img/<?= test_input($row['id']) ?>.jpg" width="150" height="150">
                        <h4><?= $row["title"] ?></h4>
                        <p><?= $row["description"] ?></p>
                        <h4><?= $row["price"] ?> $</h4>
                        <a href="index.php?add=<?= test_input($row['id']) ?>"><?= trans("Add") ?></a>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
            <?php if (isset($_SESSION["cartIds"]) && $_SESSION["cartIds"]): ?>
                <a href="<?= test_input("cart.php") ?>"><?= trans("Go to cart") ?></a>
            <?php else: ?>
                <?= trans("Cart is empty"); ?>
            <?php endif; ?>
        </div>
    </body>
</html>
<?php //session_destroy() ?>