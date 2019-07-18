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
                <img alt="<?= $row['title']?>" src="img/<?= $row['id'] ?>.jpg" width="150" height="150">
                <h4><?= $row["title"] ?></h4>
                <p><?= $row["description"] ?></p>
                <h4><?= $row["price"] ?></h4>
            </div>
        <?php endwhile; ?>
    </div>
    </body>
</html>
