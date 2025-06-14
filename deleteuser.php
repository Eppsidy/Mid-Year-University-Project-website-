<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: customerLogin.php");
    exit();
}

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    $orderQuery = $con->query("SELECT id FROM orders WHERE customer_id = $userId");
    while ($order = $orderQuery->fetch_assoc()) {
        $con->query("DELETE FROM order_items WHERE order_id = " . $order['id']);
    }
    $con->query("DELETE FROM orders WHERE customer_id = $userId");

    $con->query("DELETE FROM customerlogin WHERE id = $userId");
}

header("Location: manageUsers.php");
exit();
