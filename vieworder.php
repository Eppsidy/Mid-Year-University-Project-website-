<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: customerLogin.php");
    exit();
}

$orderId = $_GET['id'];

$orderQuery = $con->query("SELECT * FROM orders WHERE id = $orderId");
$order = $orderQuery->fetch_assoc();

$itemQuery = $con->query("SELECT * FROM order_items WHERE order_id = $orderId");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Details - EPPSIDY Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Order #<?= $orderId ?></h1>
    <p><strong>Total:</strong> R <?= number_format($order['order_total'], 2) ?></p>
    <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>

    <table border="1" cellpadding="10">
        <tr>
            <th>Product Name</th>
            <th>Code</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($item = $itemQuery->fetch_assoc()): ?>
        <tr>
            <td><?= $item['product_name'] ?></td>
            <td><?= $item['product_code'] ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>R <?= number_format($item['price'], 2) ?></td>
            <td>R <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="manageOrders.php">← Back to Orders</a>
</body>
</html>
