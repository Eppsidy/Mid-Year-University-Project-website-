<?php
session_start();
include 'connect.php';

// Cart actions
if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
        case "add":
            if (!empty($_POST["quantity"])) {
                $code = $_GET["code"];
                $stmt = $con->prepare("SELECT * FROM tblproduct WHERE code = ?");
                $stmt->bind_param("s", $code);
                $stmt->execute();
                $result = $stmt->get_result();
                $productByCode = $result->fetch_assoc();

                $itemArray = array(
                    $productByCode["code"] => array(
                        'name' => $productByCode["name"],
                        'code' => $productByCode["code"],
                        'quantity' => $_POST["quantity"],
                        'price' => $productByCode["price"],
                        'image' => $productByCode["image"]
                    )
                );

                if (!empty($_SESSION["cart_item"])) {
                    if (array_key_exists($productByCode["code"], $_SESSION["cart_item"])) {
                        $_SESSION["cart_item"][$productByCode["code"]]["quantity"] += $_POST["quantity"];
                    } else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
            break;

        case "remove":
            if (!empty($_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($_GET["code"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                }
                if (empty($_SESSION["cart_item"]))
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
    <title>EPPSIDY (Guest)</title>
    <link rel="stylesheet" href="index-style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php" class="logo">EPPSIDY</a>
</div>

<div class="container">
    <div style="text-align:right;">
        <a href="index.php?action=empty"><button class="cancelbtn">Empty Cart</button></a>
        <a href="customerLogin.php"><button class="cancelbtn">Login</button></a>
    </div>

    <h1 class="sectionheadertitle">Shopping Cart</h1>

<?php
if (isset($_SESSION["cart_item"])) {
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
    foreach ($_SESSION["cart_item"] as $item) {
        $item_price = $item["quantity"] * $item["price"];
?>
        <tr>
            <td><img src="uploads/<?php echo $item["image"]; ?>" class="cart-item-image" style="height:50px;"/> <?php echo $item["name"]; ?></td>
            <td><?php echo $item["code"]; ?></td>
            <td style="text-align:center;"><?php echo $item["quantity"]; ?></td>
            <td style="text-align:center;"><?php echo "R".$item["price"]; ?></td>
            <td style="text-align:center;"><?php echo "R".number_format($item_price,2); ?></td>
            <td style="text-align:center;"><a href="index_guest.php?action=remove&code=<?php echo $item["code"]; ?>"><button class="cancelbtn">Remove</button></a></td>
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
<?php } else {
    echo "<div style='text-align:center;'>Your cart is empty.</div>";
} ?>
</table>
</div>

<div id="product-grid">
    <div class="txt-heading">Products</div>
    <?php
    $product_query = "SELECT * FROM tblproduct ORDER BY id ASC";
    $product_result = mysqli_query($con, $product_query);

    while ($row = mysqli_fetch_assoc($product_result)) {
    ?>
        <div class="product-item">
            <form method="post" action="index.php?action=add&code=<?php echo $row["code"]; ?>">
                <div class="product-image"><img src="uploads/<?php echo $row["image"]; ?>"></div>
                <div class="product-tile-footer">
                    <div class="product-title"><?php echo $row["name"]; ?></div>
                    <div class="product-price">R<?php echo $row["price"]; ?></div>
                    <div class="cart-action">
                        <input type="text" class="product-quantity" name="quantity" value="1" size="2" />
                        <input type="submit" value="Add to Cart" class="btnAddAction" />
                    </div>
                </div>
            </form>
        </div>
    <?php } ?>
</div>

</body>
</html>
