<?php
    session_start();
    require_once "common.php";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= trans("store") ?></title>
    </head>
    <body>
        <table>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                <img src="img/<?= $row['id'] ?>.jpg" alt="<?= $row['title'] ?>" width="250" height="250">
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
        </table>
    </body>
</html>
