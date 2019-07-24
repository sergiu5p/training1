<?php
    session_start();
    require_once "common.php";

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        header("location: login.php");
        exit();
    }

    if (isset($_GET["edit"])) {
        $id = $_GET["edit"];
        $action = "edit";
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    } elseif (isset($_GET["add"])) {
        $action = "add";
    }

    if (isset($_POST["save"])) {
        $title = test_input($_POST["title"]);
        $description = test_input($_POST["description"]);
        $price = test_input($_POST["price"]);
        $image = $_POST["id"];
        //$query = "INSERT INTO products ('title', 'description', 'price') VALUES (?, ?, ?)";
        //$stmt = $conn->prepare($query);
        //$stmt->bind_param("ssi", $title, $description, $price);
        // $stmt->execute();
        if (isset($_FILES["image"])) {

        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= trans("product") ?></title>
    </head>
    <body>
        <a href="login.php?logout"><?= trans("Logout") ?></a>
        <div>
            <?php if ($action == "edit"): ?>
                <?php $row = $result->fetch_assoc() ?>
                <form action="product.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="text" name="title" value="<?= $row["title"] ?>">
                    <input type="text" name="description" value="<?= $row["description"] ?>">
                    <input type="number" name="price" value="<?= $row['price'] ?>">
                    <input type="file" name="image" placeholder="<?= trans("Image") ?>">
                    <input type="submit" name="save" value="<?= trans("Save") ?>">
                </form>
            <?php endif; ?>
        </div>
    </body>
</html>