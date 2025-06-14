<?php
session_start();
include 'connect.php';

if(!empty($_GET["action"])) {
    switch($_GET["action"]) {
        case "add":
            if(!empty($_POST["quantity"])) {
                $code = mysqli_real_escape_string($con, $_GET["code"]);
                $query = "SELECT * FROM tblproduct WHERE code='$code'";
                $result = mysqli_query($con, $query);
                $productByCode = mysqli_fetch_all($result, MYSQLI_ASSOC);

                if (!empty($productByCode)) {
                    $itemArray = array($productByCode[0]["code"] => array(
                        'id' => $productByCode[0]["id"],
                        'name' => $productByCode[0]["name"],
                        'code' => $productByCode[0]["code"],
                        'quantity' => $_POST["quantity"],
                        'price' => $productByCode[0]["price"],
                        'image' => $productByCode[0]["image"]
                    ));

                    if(!empty($_SESSION["cart_item"])) {
                        if(array_key_exists($productByCode[0]["code"], $_SESSION["cart_item"])) {
                            $_SESSION["cart_item"][$productByCode[0]["code"]]["quantity"] += $_POST["quantity"];
                        } else {
                            $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                        }
                    } else {
                        $_SESSION["cart_item"] = $itemArray;
                    }
                }
            }
        break;

        case "remove":
            if(!empty($_SESSION["cart_item"])) {
                foreach($_SESSION["cart_item"] as $k => $v) {
                    if($_GET["code"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                }
                if(empty($_SESSION["cart_item"]))
                    unset($_SESSION["cart_item"]);
            }
        break;

        case "empty":
            unset($_SESSION["cart_item"]);
        break;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>EPPSIDY - SELL AND BUY</title>
<link rel="stylesheet" href="index-style.css">
</head>
<body>

<div class="navbar">
    <a href="index_loggedin.php" class="logo">EPPSIDY</a>
</div>

<div class="container">

<div style="display: flex; justify-content: space-between;">
    <a href="Logout.php"><button class="cancelbtn" id="logout">Logout</button></a>
    <div style="display: flex; align-items: center; gap: 15px;">
        <span style="color: black; font-weight: bold;">
            <?php echo isset($_SESSION['userName']) ? $_SESSION['userName'] : 'Guest'; ?>
        </span>
        </div>
    <div>
        <a href="index_loggedin.php?action=empty"><button class="cancelbtn">Empty Cart</button></a>
        <a href="addproduct.php"><button class="cancelbtn">Add Product</button></a>
        <a href="userManagement.php"><button class="cancelbtn">Management</button></a>
    </div>
</div>

    <h1 class="sectionheadertitle">Shopping Cart</h1>

<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
?>	
    <table class="tbl-cart" cellpadding="10" cellspacing="1" style="width:100%; background-color: white; border-radius: 15px;">
        <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
            <th>Remove</th>
        </tr>
<?php		
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"] * $item["price"];
?>
        <tr>
            <td><img src="uploads/<?php echo $item["image"]; ?>" class="cart-item-image" style="height:50px;"/> <?php echo $item["name"]; ?></td>
            <td><?php echo $item["code"]; ?></td>
            <td style="text-align:center;"><?php echo $item["quantity"]; ?></td>
            <td style="text-align:center;"><?php echo "R".$item["price"]; ?></td>
            <td style="text-align:center;"><?php echo "R".number_format($item_price,2); ?></td>
            <td style="text-align:center;"><a href="index.php?action=remove&code=<?php echo $item["code"]; ?>"><button class="cancelbtn">Remove</button></a></td>
        </tr>
<?php
        $total_quantity += $item["quantity"];
        $total_price += $item_price;
    }
?>
        <tr>
            <td colspan="2" align="right">Total:</td>
            <td align="center"><?php echo $total_quantity; ?></td>
            <td colspan="2" align="center"><strong><?php echo "R".number_format($total_price, 2); ?></strong></td>
            <td></td>
        </tr>
        <tr><td colspan="6" align="right"><a href="checkout.php"><button>Checkout</button></a></td></tr>
    </table>
<?php
} else {
    echo "<div style='text-align:center;'>Your cart is empty.</div>";
}
?>
</div>

<div id="product-grid">
	<div class="txt-heading">Products</div>
	<?php
	$query = "SELECT * FROM tblproduct ORDER BY id ASC";
	$result = mysqli_query($con, $query);
	while($row = mysqli_fetch_assoc($result)) {
	?>
		<div class="product-item">
			<form method="post" action="index_loggedin.php?action=add&code=<?php echo $row["code"]; ?>">
			<div class="product-image"><img src="uploads/<?php echo $row["image"]; ?>"></div>
			<div class="product-tile-footer">
			<div class="product-title"><?php echo $row["name"]; ?></div>
			<div class="product-price"><?php echo "R".$row["price"]; ?></div>
			<div class="cart-action">
				<input type="text" class="product-quantity" name="quantity" value="1" size="2" />
				<input type="submit" value="Add to Cart" class="btnAddAction" />
			</div>
			</div>
			</form>
		</div>
	<?php
	}
	?>
</div>

</body>
</html>
