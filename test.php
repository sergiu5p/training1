<?php
    function imageValidation($image)
    {
        $target_dir = "img/";
        $target_file = $target_dir . basename($image["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if (isset($_POST["image"])) {
            $check = getimagesize($image["tmp_name"]);
            if ($check) {
                $uploadOk = 1;
            } else {
                $_SESSION['errors'][] =  "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check file size
        if ($image["size"] > 500000) {
            $_SESSION['errors'][] =  "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $_SESSION['errors'][] =  "Sorry, only JPG, JPEG & PNG files are allowed.";
            $uploadOk = 0;
        }
        return boolval($uploadOk);
    }
    // Check if $uploadOk is set to 0 by an error
/*    if ($uploadOk) {

        $tmp_name = $_FILES["image"]["tmp_name"];
        $new_name = "img/".$id.".".$imageFileType;

        @unlink($new_name);

        copy($tmp_name, $new_name);
        $stmt->execute();
        $_SESSION['errors'] = [];
        header("location: products.php");
    } else {
        header("location: product.php?id=$id");
    } */
    if (isset($_POST["submit"])) {
        print_r($_POST);
    }
?>
<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <form action="test.php" method="POST">
            <input type="file" name="image">
            <input type="submit" name="submit">
        </form>
    </body>
</html>
