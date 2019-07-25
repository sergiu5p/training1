<?php
    require_once "common.php";
    require_once "config.php";

    if (isset($_GET["remove"])) {
        $remove = strip_tags($_GET["remove"]);
        unset($_SESSION["cartIds"][array_search($remove, $_SESSION["cartIds"])]);
        header("location: cart.php");
    }

    if (!isset($_SESSION["cartIds"]) || !count($_SESSION["cartIds"])) {
        header("location: index.php");
    } else {
        $in = join(',', array_fill(0, count($_SESSION["cartIds"]), '?'));
        $query = "SELECT * FROM products WHERE id IN ($in)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat('i', count($_SESSION["cartIds"])), ...$_SESSION["cartIds"]);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    if (isset($_POST["checkout"])) {
        $name = strip_tags($_POST["name"]);
        $email = strip_tags($_POST["email"]);
        $comments = strip_tags($_POST["comments"]);
        $message_products = "";

        while ($row = $result->fetch_assoc()) {
            $message_products.="<h4>".$row['title']."</h4>";
            $message_products.="<h4>".$row["description"]."</h4>";
            $message_products.="<h4>".$row["price"]." $</h4>";
        }

        $_SESSION["message"] = $message_products."<h4>".$name."</h4>"."<h4>".$email."</h4>"."<h4>".$comments."</h4>";
        $to = ADMIN_EMAIL;
        $sub = "New order";
        $msg = $_SESSION["message"];
        $headers = "From: ".$email."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail($to, $sub, $msg, $headers);
        session_destroy();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= trans("Cart") ?></title>
    </head>
    <body>
        <a href="login.php"><?= trans("Login") ?></a>
        <div>
            <?php while ( $row = $result->fetch_assoc() ):  ?>
                <div>
                    <img alt="<?= htmlspecialchars($row['title']) ?>" src="img/<?= htmlspecialchars($row['id']) ?>.jpg" width="150" height="150">
                    <h4><?= htmlspecialchars($row["title"]) ?></h4>
                    <p><?= htmlspecialchars($row["description"]) ?></p>
                    <h4><?= htmlspecialchars($row["price"]) ?> $</h4>
                    <a href="cart.php?remove=<?= htmlspecialchars($row['id']) ?>">Remove</a>
                </div>
            <?php endwhile; ?>
        </div>
        <form action="<?= htmlspecialchars("cart.php") ?>" method="POST">
            <input type="text" name="name" placeholder="<?= trans("Name") ?>" required>
            <br>
            <br>
            <input type="email" name="email" placeholder="<?= trans("E-mail") ?>" required>
            <br>
            <br>
            <input type="text" name="comments" placeholder="<?= trans("Comments") ?>">
            <br>
            <br>
            <button name="checkout"><?= trans("Checkout") ?></button>
        </form>
        <a href="<?= htmlspecialchars("index.php")?><?= trans("Go to index") ?></a>
    </body>
</html>