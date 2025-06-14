<?php
include 'connect.php';
$id = $_GET['id'];
$con->query("DELETE FROM tblproduct WHERE id=$id");
header("Location: management.php");
