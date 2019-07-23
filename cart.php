<?php
    session_start();
    require_once "common.php";

    if (isset($_GET["remove"])) {
        unset($_SESSION["cartIds"][array_search($_GET["remove"], $_SESSION["cartIds"])]);
        header("location: cart.php");
    }

    if (!isset($_SESSION["cartIds"]) || count($_SESSION["cartIds"]) == 0) {
        header("location: index.php");
    } else {
        $query = "SELECT * FROM products WHERE id IN (".implode(",", $_SESSION["cartIds"]).")";
        $result = $conn->query($query) or die($conn->error);
    }

    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $comments = test_input($_POST["comments"]);
    $message_products = "";

    while ($row = $result->fetch_assoc()) {
        $message_products.="<h4>".$row['title']."</h4>";
        $message_products.="<h4>".$row["description"]."</h4>";
        $message_products.="<h4>".$row["price"]." $</h4>";
    }

    $_SESSION["message"] = $message_products."<h4>".$name."</h4>"."<h4>".$email."</h4>"."<h4>".$comments."</h4>";
    $to = ADMIN_EMAIL;
    $sub = "order";
    $msg = $_SESSION["message"];
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "From:".$name."<".$email.">"."\r\n";
    $headers .= $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    echo "E-mail sent succesfully (i guess)";
    mail($to, $sub, $msg, $headers);
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
                    <h4><?= $row["price"] ?> $</h4>
                    <a href="cart.php?remove=<?= test_input($row['id']) ?>">Remove</a>
                </div>
            <?php endwhile; ?>
        </div>
        <form action="<?= test_input("cart.php") ?>" method="POST">
            <input type="text" name="name" placeholder="<?= trans("Name") ?>" required>
            <br>
            <br>
            <input type="email" name="email" placeholder="<?= trans("E-mail") ?>" required>
            <br>
            <br>
            <input type="text" name="comments" placeholder="<?= trans("Comments") ?>">
            <br>
            <br>
            <input type="submit" name="checkout" placeholder="<?= trans("Checkout") ?>">
        </form>
        <a href="<?= trans('index.php') ?>"><?= trans("Go to index") ?></a>
    </body>
</html>