<?php
session_start();
include 'connect.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $code = 'PRD' . rand(100, 999);
    $price = $_POST['price'];
    $owner_id = $_SESSION['customer_id'];

    $targetDir = "uploads/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png'];
    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
           
            $query = "INSERT INTO tblproduct (name, code, price, image, owner_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ssdsi", $name, $code, $price, $fileName, $owner_id);
            $stmt->execute();

                   echo "<script>alert('Product added successfully.');</script>";
    } else {
        echo "<script>alert('Database error: " . $stmt->error . "');</script>";
    }
}
}
?>


<!DOCTYPE html>
<html>
<head>
<title>EPPSIDY - Add Product</title>
<link rel="stylesheet" href="Login.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="navbar">
    <a href="index_loggedin.php" class="logo">EPPSIDY</a>
</div>

<form action="addProduct.php" method="POST" enctype="multipart/form-data">
  <div id="formcontainer">
    <header class="sectionheader">
	    <h1 class="sectionheadertitle">Add Product</h1>
    </header>

    <label for="name"><b>Product Name</b></label>
    <input type="text" placeholder="Enter Product Name" name="name" required>

    <label for="price"><b>Price</b> </label>
    <input type="number" placeholder="Enter Price" name="price" required>

    <label for="image"><b>Image URL</b></label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit" name="submit">Add Product</button>
    <a href="index_loggedin.php"><div class="CreateAcc">Back to Cart</div></a>
  </div>
</form>

</body>
</html>
