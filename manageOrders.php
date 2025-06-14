<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: customerLogin.php");
    exit();
}

$query = "
    SELECT orders.id AS order_id, customerlogin.name, customerlogin.email, orders.order_total, orders.order_date 
    FROM orders 
    JOIN customerlogin ON orders.customer_id = customerlogin.id 
    ORDER BY orders.order_date DESC
";
$result = $con->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>EPPSIDY Admin - Manage Orders</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Order Management</h1>
    <a href="adminDashboard.php">← Back to Dashboard</a>
    <table border="1" cellpadding="10">
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Total</th>
            <th>Order Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['order_id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td>R <?= number_format($row['order_total'], 2) ?></td>
            <td><?= $row['order_date'] ?></td>
            <td>
                <a href="vieworder.php?id=<?= $row['order_id'] ?>">View</a> |
                <a href="deleteorder.php?id=<?= $row['order_id'] ?>" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
