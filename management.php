<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: customerLogin.php");
    exit();
}

$result = $con->query("SELECT * FROM tblproduct");
?>
<!DOCTYPE html>
<html>
<head>
    <title>EPPSIDY - Manage Products</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Product Management</h1>
    <a href="adminDashboard.php">← Back to Dashboard</a>
    <a href="addproduct Admin.php">Add New Product</a>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Name</th><th>Code</th><th>Price</th><th>Image</th><th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['code'] ?></td>
            <td><?= $row['price'] ?></td>
            <td><img src="uploads/<?= $row['image'] ?>" width="50"></td>
            <td>
                <a href="productedit.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="productdelete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
