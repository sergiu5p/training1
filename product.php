<?php
    require_once "common.php";

    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        header("location: login.php");
        exit();
    }

    $id = 0;
    $errors = [];
    $row = [
            'id' => "",
            'title' => "",
            'description' => ""
    ];

    if (isset($_GET["id"])) {
        $id = strip_tags($_GET["id"]);
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

    }

    if (isset($_POST["title"]) || isset($_POST["description"]) || isset($_POST["price"]) ||
    isset($_POST["image"]) || isset($_POST["save"])) {
        $id = strip_tags($_POST["id"]);
        $ids_array = [];
        $query = "SELECT id FROM products";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $ids_array[] = $row["id"];
        }

        if (in_array($id, $ids_array)) {
            $query = "UPDATE products SET title=?, description=?, price=? WHERE id=?";
            $stmt = $conn->prepare($query) or die($conn->error);
            $stmt->bind_param("ssdi", $title, $description, $price, $id);
            $title = sqlInjection($_POST["title"]);
            $description = sqlInjection($_POST["description"]);
            $price = sqlInjection($_POST["price"]);
            $id = strip_tags($_POST["id"]);

            if (isset($_FILES["image"])) {
                $target_dir = "img/";
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
                    header("location: product.php?id=".$id);
                    // if everything is ok, try to upload file
                } else {
                    $tmp_name = $_FILES["image"]["tmp_name"];
                    $new_name = "img/".$id.".".$imageFileType;

                    @unlink($new_name);

                    copy($tmp_name, $new_name);
                }
            }

            $stmt->execute();
        }
    }

/*    if (isset($_POST["save"])) {
        $title = strip_tags($_POST["title"]);
        $description = strip_tags($_POST["description"]);
        $price = strip_tags($_POST["price"]);
        $image = strip_tags($_POST["id"]);
        $query = "INSERT INTO products ('title', 'description', 'price') VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $title, $description, $price);
         $stmt->execute();


    }*/
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
            <form action="product.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <br>
                <input type="text" name="title" value="<?= $row["title"] ?>">
                <br>
                <input type="text" name="description" value="<?= $row["description"] ?>">
                <br>
                <input type="number" name="price" value="<?= $row['price'] ?>">
                <br>
                <input type="file" name="image" placeholder="<?= trans("Image") ?>">
                <br>
                <input type="submit" name="save" value="<?= trans("Save") ?>">
            </form>
        </div>
    </body>
</html>