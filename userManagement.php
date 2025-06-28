<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customerId = $_SESSION['customer_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Management</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="navbar">
    <a href="index_loggedin.php">Home</a>
    <a href="logout.php" style="float:right;">Logout</a>
</div>

<div class="container">
    <h2>My Products</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php
        $query = "SELECT * FROM tblproduct WHERE owner_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td data-label="Name"><?= $row['name']; ?></td>
            <td data-label="Code"><?= $row['code']; ?></td>
            <td data-label="Price">R<?= $row['price']; ?></td>
            <td data-label="Actions" class="action-buttons">
                <a class="edit" href="productedit.php?id=<?= $row['id']; ?>">Edit</a>
                <a class="delete" href="productdelete.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>My Orders</h2>
    <table>
        <tr>
            <th>Order #</th>
            <th>Total</th>
            <th>Date</th>
        </tr>
        <?php
        $orderQuery = "SELECT * FROM orders WHERE customer_id = ?";
        $stmt = $con->prepare($orderQuery);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $orderResult = $stmt->get_result();

        while ($order = $orderResult->fetch_assoc()):
        ?>
        <tr>
            <td data-label="Order #">#<?= $order['id']; ?></td>
            <td data-label="Total">R<?= $order['order_total']; ?></td>
            <td data-label="Date"><?= $order['order_date']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
