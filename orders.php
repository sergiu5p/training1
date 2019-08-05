<?php
    require_once "common.php";

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        header("location: login.php");
        exit();
    }

    $rows = [];

    $result = $conn->query("SELECT * FROM orders");
    if (mysqli_num_rows($result)) {
        while ($row = $result->fetch_assoc() ) {
            $rows[] = $row;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="style.css">
        <meta charset="UTF-8">
        <title><?= trans("orders") ?></title>
    </head>
    <body>
    <?php foreach ($rows as $row): ?>
        <div class="order">
            <h4><?= $row["name"] ?></h4>
            <h4><?= $row["email"] ?></h4>
            <h4><?= $row["comments"] ?></h4>
            <h4><?= strval($row["summed_price"]) + " $" ?></h4>
            <h4><?= $row["creation_date"] ?></h4>
        </div>
    <?php endforeach; ?>
    </body>
</html>