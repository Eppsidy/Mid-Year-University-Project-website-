<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: customerLogin.php");
    exit();
}

$orderId = $_GET['id'] ?? 0;


$con->query("DELETE FROM order_items WHERE order_id = $orderId");

$con->query("DELETE FROM orders WHERE id = $orderId");

header("Location: manageOrders.php");
exit();
