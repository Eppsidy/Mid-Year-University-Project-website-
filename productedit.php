<?php
include 'connect.php';
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    $stmt = $con->prepare("UPDATE tblproduct SET name=?, code=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("ssdsi", $name, $code, $price, $image, $id);
    $stmt->execute();

    header("Location: management.php");
    exit();
}

$product = $con->query("SELECT * FROM tblproduct WHERE id=$id")->fetch_assoc();
?>

<form method="post">
    <h2>Edit Product</h2>
    Name: <input type="text" name="name" value="<?= $product['name'] ?>" required><br>
    Code: <input type="text" name="code" value="<?= $product['code'] ?>" required><br>
    Price: <input type="text" name="price" value="<?= $product['price'] ?>" required><br>
    Image URL: <input type="text" name="image" value="<?= $product['image'] ?>"><br>
    <input type="submit" value="Update Product">
</form>
