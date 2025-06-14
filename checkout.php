<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);

if (!isset($_SESSION["cart_item"]) || empty($_SESSION["cart_item"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>EPPSIDY - Checkout</title>
    <link rel="stylesheet" href="checkout-style.css">
</head>
<body>

<div class="navbar">
    <a href="index_loggedin.php" class="logo">EPPSIDY</a>
</div>

<div class="container">
    <h1>Checkout</h1>

    <?php
    $total = 0;
    echo "<table>";
    echo "<tr><th>Image</th><th>Product Name</th><th>Quantity</th><th>Unit Price (R)</th><th>Total (R)</th></tr>";
    foreach ($_SESSION["cart_item"] as $item) {
        $item_price = $item["quantity"] * $item["price"];
        echo "<tr>
                <td><img src='uploads/" . $item["image"] . "' alt='" . $item["name"] . "' height='50'></td>
                <td>".$item["name"]."</td>
                <td>".$item["quantity"]."</td>
                <td>".number_format($item["price"], 2)."</td>
                <td>".number_format($item_price, 2)."</td>
              </tr>";
        $total += $item_price;
    }
    echo "</table>";
    echo "<div class='checkout-total'><h2>Total: R ".number_format($total, 2)."</h2></div>";
    ?>

    <form action="payment.php" method="POST">
        <input type="submit" class="btn-checkout" name="proceed_payment" value="Proceed to Payment">
    </form>
</div>

</body>
</html>
