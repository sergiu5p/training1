<?php
    session_start();
    require_once "common.php";

    if (isset($_GET['add'])) {
        $_SESSION['cartIds'][] = $_GET['add'];
        print_r($_SESSION['cartIds']);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= trans("store") ?></title>
    </head>
    <body>
 <!--       <table>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <img src="img/<?= $row['id'] ?>.jpg" alt="<?= $row['title'] ?>" width="150" height="150">
                </td>
                <td>
                    <a href="/?add=<?= $row['id'] ?>">Add</a>
                </td>
            </tr>
            <tr>
                <td>
                    <?= $row['title']?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= $row['description'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= $row['price'], "$"?>
                </td>
            </tr>
            <?php endwhile; ?>
            <tr>
                <td>
                    <a href=""><?= trans("Go to cart")?></a>
                </td>
            </tr>
        </table>
-->
    </body>
</html>
