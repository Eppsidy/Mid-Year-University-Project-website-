<?php
session_start();
include 'connect.php';
error_reporting(E_ALL & ~E_NOTICE);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btnPayment"])) {
    if (!isset($_SESSION["cart_item"]) || empty($_SESSION["cart_item"])) {
        echo "Your cart is empty.";
        exit;
    }

    if (!isset($_SESSION['customer_id'])) {
        echo "You must be logged in to place an order.";
        exit;
    }

    $customerId = $_SESSION['customer_id'];
    $orderTotal = 0;
    $orderDate = date("Y-m-d H:i:s");

    foreach ($_SESSION["cart_item"] as $item) {
        $orderTotal += $item["price"] * $item["quantity"];
    }

    $insertOrder = "INSERT INTO orders (customer_id, order_total, order_date) VALUES (?, ?, ?)";
    $stmt = $con->prepare($insertOrder);
    $stmt->bind_param("ids", $customerId, $orderTotal, $orderDate);
    $stmt->execute();

    $orderId = $stmt->insert_id;

    $insertItem = $con->prepare("INSERT INTO order_items (order_id, product_id, product_code, product_name, quantity, price) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($_SESSION["cart_item"] as $item) {
        $product_id = $item["id"];
        $productCode = $item["code"];
        $productName = $item["name"];
        $quantity = $item["quantity"];
        $price = $item["price"];

        $insertItem->bind_param("iissid", $orderId, $product_id, $productCode, $productName, $quantity, $price);
        $insertItem->execute();
    }

    unset($_SESSION["cart_item"]);

    header("Location: ordersuccessful.php?order_id=$orderId");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>EPPSIDY - Payment</title>
    <link rel="stylesheet" href="payment.css">
</head>
<body>

<div class="container">
    <h1>Enter Payment Details</h1>

    <form action="payment.php" method="POST">
        <label>Select your bank:</label>
        <select name="bank" required>
            <option value="Capitec">Capitec</option>
            <option value="FNB">FNB</option>
            <option value="Nedbank">Nedbank</option>
            <option value="Standard Bank">Standard Bank</option>
            <option value="ABSA">ABSA</option>
        </select><br><br>

        <label>Card Number:</label>
        <input type="text" name="card_number" required><br><br>

        <label>Card Holder:</label>
        <input type="text" name="card_holder" required><br><br>

        <label>Expiry Date:</label>
        <input type="month" name="expiry" required><br><br>

        <label>CCV:</label>
        <input type="text" name="ccv" required><br><br>

        <input type="submit" name="btnPayment" value="Confirm Payment">
    </form>
</div>

</body>
</html>                   