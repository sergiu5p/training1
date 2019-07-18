<?php
    session_start();
    require_once "common.php";

    if ( isset($_GET["id"]) ) {
        $_SESSION["cartIds"][] = $_GET["id"];
    }

    // select products that are not in the cart
    if ( isset($_SESSION["cartIds"]) ) {
        $query = "SELECT * FROM products WHERE id NOT IN (".implode(',', $_SESSION["cartIds"]).")";
    } else {
        $query = "SELECT * FROM products";
    }
    $result = $conn->query($query);

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
                <?php while ( $row = $result->fetch_assoc() ) :  ?>
                    <div>
                        <img alt="<?= test_input($row['title'])?>" src="img/<?= test_input($row['id']) ?>.jpg" width="150" height="150">
                        <h4><?= test_input($row["title"]) ?></h4>
                        <p><?= test_input($row["description"]) ?></p>
                        <h4><?= test_input($row["price"]) ?></h4>
                        <a href="index.php?&id=<?= test_input($row['id']) ?>">Add</a>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
            <a href="cart.php"><?= trans("Go to cart") ?></a>
        </div>
    </body>
</html>
