<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: customerLogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>EPPSIDY - Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="navbar">
     <h1>Welcome Admin</h1>
    <a href="management.php">Manage Productss</a>
    <a href="manageOrders.php">Manage Orders</a>
    <a href="manageUsers.php">Manage Users</a>
    <a href="Logout.php" style="float:right;">Logout</a>

</div>

</body>
</html>