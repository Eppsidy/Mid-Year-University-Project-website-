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
            // Redirect after adding to cart
            $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
            // Remove any existing ?added=1 from the URL
            $redirect = preg_replace('/(\?|&)added=1/', '', $redirect);
            header("Location: " . $redirect . (strpos($redirect, '?') === false ? '?' : '&') . "added=1");
            exit();

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
    <title>EPPSIDY - Cart</title>
    <link rel="stylesheet" href="index-style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    /* Extra cart-specific responsive tweaks */
    .cart-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 15px;
    }
    @media (max-width: 600px) {
        .cart-actions {
            flex-direction: column;
            align-items: stretch;
        }
        .tbl-cart th, .tbl-cart td {
            font-size: 14px;
        }
    }
    </style>
</head>
<body>

<div class="navbar">
    <a href="<?php echo isset($_SESSION['userName']) ? 'index_loggedin.php' : 'index.php'; ?>" class="logo">EPPSIDY</a>
    <?php if(isset($_SESSION['userName'])): ?>
    <div class="profile-dropdown">
        <div class="profile-icon" onclick="toggleDropdown(event)">
            <?php echo htmlspecialchars(strtoupper($_SESSION['userName'])); ?>
        </div>
        <div class="dropdown-menu" id="dropdownMenu">
            <span class="dropdown-user"><?php echo htmlspecialchars($_SESSION['userName']); ?></span>
            <a href="addproduct.php">➕ Add Product</a>
            <a href="userManagement.php">👤 User Management</a>
            <a href="checkout.php">💳 Checkout</a>
            <a href="Logout.php">🚪 Logout</a>
        </div>
    </div>
    <?php else: ?>
    <div class="profile-dropdown">
        <div class="profile-icon" onclick="toggleDropdown(event)">G</div>
        <div class="dropdown-menu" id="dropdownMenu">
            <a href="cart.php">🛒 Cart</a>
            <a href="customerLogin.php">🔐 Login</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function toggleDropdown(event) {
  event.stopPropagation();
  document.getElementById('dropdownMenu').classList.toggle('show');
}
document.querySelector('.profile-icon').onclick = toggleDropdown;
document.addEventListener('click', function(event) {
  if (!event.target.closest('.profile-dropdown')) {
    document.getElementById('dropdownMenu').classList.remove('show');
  }
});
</script>

<div class="container">
    <h1 class="sectionheadertitle">Shopping Cart</h1>
<?php
if (isset($_SESSION["cart_item"]) && count($_SESSION["cart_item"]) > 0) {
    $total_quantity = 0;
    $total_price = 0;
?>
    <div style="overflow-x:auto;">
    <table class="tbl-cart">
        <thead>
        <tr>
            <th>Product</th>
            <th>Code</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
            <th>Remove</th>
        </tr>
        </thead>
        <tbody>
<?php
    foreach ($_SESSION["cart_item"] as $item) {
        $item_price = $item["quantity"] * $item["price"];
?>
        <tr>
            <td>
                <img src="uploads/<?php echo $item["image"]; ?>" class="cart-item-image" style="height:40px;"/>
                <span><?php echo $item["name"]; ?></span>
            </td>
            <td><?php echo $item["code"]; ?></td>
            <td style="text-align:center;"><?php echo $item["quantity"]; ?></td>
            <td style="text-align:center;"><?php echo "R".$item["price"]; ?></td>
            <td style="text-align:center;"><?php echo "R".number_format($item_price,2); ?></td>
            <td style="text-align:center;">
                <a href="cart.php?action=remove&code=<?php echo $item["code"]; ?>">
                    <button class="cancelbtn" type="button">Remove</button>
                </a>
            </td>
        </tr>
<?php
        $total_quantity += $item["quantity"];
        $total_price += $item_price;
    }
?>
        <tr>
            <td colspan="2" align="right"><strong>Total:</strong></td>
            <td align="center"><strong><?php echo $total_quantity; ?></strong></td>
            <td colspan="2" align="center"><strong><?php echo "R".number_format($total_price, 2); ?></strong></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    </div>
    <div class="cart-actions">
        <?php if(isset($_SESSION['userName'])): ?>
            <a href="index_loggedin.php"><button class="btnAddAction" type="button">Continue Shopping</button></a>
        <?php else: ?>
            <a href="index.php"><button class="btnAddAction" type="button">Continue Shopping</button></a>
        <?php endif; ?>
        <?php if(isset($_SESSION['userName'])): ?>
            <a href="checkout.php"><button class="btnAddAction" type="button">Checkout</button></a>
        <?php endif; ?>
        <a href="cart.php?action=empty"><button class="cancelbtn" type="button">Empty Cart</button></a>
    </div>
<?php } else {
    echo "<div style='text-align:center;'>Your cart is empty.</div>";
} ?>
</div>

<script>
document.getElementById('hamburger').onclick = function() {
    document.getElementById('navActions').classList.toggle('show');
};
</script>
</body>
</html>