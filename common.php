<?php
    session_start();
    require_once "config.php";

     // Create connection to database
    $conn = new mysqli(SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME) or die($conn->connect_error);

    // For prevent sql injection
    function sqlInjection($data) {
        $data = strip_tags($data);
        $data = mysqli_real_escape_string($GLOBALS['conn'], $data);
        return $data;
    }

    // Define translate function
    function trans($data) {
        $data = htmlspecialchars($data);
        return $data;
    }

    // unstable function. Check his functionality
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
