<?php
        require_once "common.php";

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        header("location: login.php");
        exit();
    }

    $id = 0;
    $row = [
            'id' => "",
            'title' => "",
            'description' => ""
    ];

    if (isset($_GET["id"])) {

        $id = sqlInjection($_GET["id"]);
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (isset($_POST["title"]) || isset($_POST["description"]) || isset($_POST["price"]) ||
            isset($_POST["image"]) || isset($_POST["save"])) {

            $_SESSION['errors'] = [];
            $id = sqlInjection($_POST["id"]);

            $query = "UPDATE products SET title=?, description=?, price=? WHERE id=?";
            $stmt = $conn->prepare($query) or die($conn->error);
            $title = sqlInjection($_POST["title"]);
            $description = sqlInjection($_POST["description"]);
            $price = sqlInjection($_POST["price"]);
            $id = sqlInjection($_POST["id"]);
            $stmt->bind_param("ssdi", $title, $description, $price, $id);

            if (isset($_FILES["image"])) {

                // Check the image validation
                if (imageValidation($_FILES["image"])) {

                    $tmp_name = $_FILES["image"]["tmp_name"];
                    $new_name = "img/".$id.".".$GLOBALS['imageFileType'];

                    @unlink($new_name);

                    copy($tmp_name, $new_name);
                    $stmt->execute();
                    $_SESSION['errors'] = [];
                    header("location: products.php");
                } else {
                    header("location: product.php?id=$id");
                }
            }
            $stmt->execute();
            header("location: products.php");
        }
    } else {
        if (isset($_POST["title"]) || isset($_POST["description"]) || isset($_POST["price"]) ||
            isset($_POST["image"]) || isset($_POST["save"])) {

            $_SESSION['errors'] = [];

            // insert the product into table
            $query = "INSERT INTO products (title, description, price) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $title = sqlInjection($_POST["title"]);
            $description = sqlInjection($_POST["description"]);
            $price = sqlInjection($_POST["price"]);
            $stmt->bind_param("ssd", $title, $description, $price);

            // check the image validation
            if (imageValidation($_FILES["image"])) {
                $stmt->execute();
                // extract the id of product
                $query = "SELECT id FROM products ORDER BY id DESC LIMIT 1";
                $result = $conn->query($query);
                $id = $result->fetch_assoc()["id"];

                $tmp_name = $_FILES["image"]["tmp_name"];
                $new_name = "img/".$id.".".$GLOBALS['imageFileType'];

                copy($tmp_name, $new_name);
                $_SESSION["errors"] = [];
                header("location: products.php");
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="UTF-8">
        <title><?= trans("product") ?></title>
    </head>
    <body>
        <ul>
            <li><a href="login.php?logout"><?= trans("Logout") ?></a></li>
            <li><a href="products.php">products.php</a></li>
            <li><a href="index.php"><?= trans("index.php") ?></a></li>
        </ul>
        <?php if (isset($_SESSION['errors'])): ?>
            <?php foreach ($_SESSION['errors'] as $e): ?>
                <?= trans($e); ?>
            <?php endforeach;?>
        <?php endif; ?>
        <div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <br>
                <br>
                Title: <input type="text" name="title" value="<?= $row["title"] ?>" required>
                <br>
                <br>
                Description: <input type="text" name="description" value="<?= $row["description"] ?>" required>
                <br>
                <br>
                Price: <input type="number" step="0.01" name="price" value="<?= $row['price'] ?>" required>
                <br>
                <br>
                <input type="file" name="image" placeholder="<?= trans("Image") ?>" <?= !isset($_REQUEST['id']) ? 'required' : '' ?> >
                <br>
                <br>
                <input type="submit" name="save" value="<?= trans("Save") ?>">
            </form>
        </div>
    </body>
</html>