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
            $id = test_input($_POST["id"]);
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            if(isset($_POST["image"])) {
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
            // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                header("location: product.php?edit=".$id);
            // if everything is ok, try to upload file
            } else {
                $tmp_name = $_FILES["image"]["tmp_name"];
                $new_name = "img/".$id.".".$imageFileType;
                if (move_uploaded_file($tmp_name, $new_name)) {
                    if (isset($_SESSION["message"])) {
                        unset($_SESSION["message"]);
                    }
                    header("location: products.php");
                } else {
                    $_SESSION["message"] = "Sorry, there was an error uploading your image.";
                    header("location: product.php?edit=".$id);
                }
            }
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
        <?php if (isset($_SESSION["message"])): ?>
            <h1><?= $_SESSION["message"] ?><h1>
        <?php endif; ?>
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