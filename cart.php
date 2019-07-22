<?php
    session_start();
    require_once "common.php";

    if (isset($_GET["remove"])) {
        unset($_SESSION["cartIds"][array_search($_GET["remove"], $_SESSION["cartIds"])]);
        header("location: cart.php");
    }

    if (!isset($_SESSION["cartIds"]) || count($_SESSION["cartIds"]) == 0) {
        header("location: index.php");
    } else {
        $query = "SELECT * FROM products WHERE id IN (".implode(",", $_SESSION["cartIds"]).")";
        $result = $conn->query($query) or die($conn->error);
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= trans("Cart") ?></title>
    </head>
    <body>
        <div>
            <?php while ( $row = $result->fetch_assoc() ):  ?>
                <div>
                    <img alt="<?= $row['title']?>" src="img/<?= $row['id'] ?>.jpg" width="150" height="150">
                    <h4><?= $row["title"] ?></h4>
                    <p><?= $row["description"] ?></p>
                    <h4><?= $row["price"] ?></h4>
                    <a href="cart.php?remove=<?= test_input($row['id']) ?>">Remove</a>
                </div>
            <?php endwhile; ?>
        </div>
        <form action="<?= test_input("login.php") ?>" method="POST">
            <input type="text" name="name" placeholder="<?= trans("Name") ?>" required>
            <br>
            <br>
            <input type="email" name="email" placeholder="<?= trans("E-mail") ?>" required>
            <br>
            <br>
            <input type="text" name="comments" placeholder="<?= trans("Comments") ?>">
            <br>
            <br>
            <button name="checkout"><?= trans("Checkout") ?></button>
        </form>
        <a href="<?= trans('index.php') ?>"><?= trans("Go to index") ?></a>
    </body>
</html>