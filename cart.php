<?php
    session_start();
    require_once "common.php";
    if( !isset($_SESSION["cartIds"]) ) {
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
                <img alt="<?= test_input($row['title'])?>" src="img/<?= test_input($row['id']) ?>.jpg" width="150" height="150">
                <h4><?= test_input($row["title"]) ?></h4>
                <p><?= test_input($row["description"]) ?></p>
                <h4><?= test_input($row["price"]) ?></h4>
            </div>
        <?php endwhile; ?>
    </div>
    </body>
</html>
