<?php
session_start();
include 'connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EPPSIDY - Store</title>
    <link rel="stylesheet" href="index-style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="navbar">
    <a href="index_loggedin.php" class="logo">EPPSIDY</a>
    <div class="profile-dropdown">
        <div class="profile-icon" onclick="toggleDropdown(event)">
            <img src="uploads/default.png" alt="Profile Picture" style="width:40px;height:40px;border-radius:50%;object-fit:cover;vertical-align:middle;">
        </div>
        <div class="dropdown-menu" id="dropdownMenu">
            <span class="dropdown-user">
                <img src="uploads/default.png" alt="Profile Picture" style="width:40px;height:40px;border-radius:50%;object-fit:cover;vertical-align:middle;">
                <br>
                <?php echo htmlspecialchars($_SESSION['userName']); ?>
            </span>
            <a href="cart.php">🛒 Cart</a>
            <a href="addproduct.php">➕ Add Product</a>
            <a href="userManagement.php">👤 User Management</a>
            <a href="Logout.php">🚪 Logout</a>
        </div>
    </div>
</div>

<div class="container">

    <?php if (isset($_GET['added']) && $_GET['added'] == '1'): ?>
    <div class="cart-message" style="background:#d4edda;color:#155724;padding:10px 15px;border-radius:5px;margin:15px 0;">
        Product added to cart!
    </div>
    <?php endif; ?>

    <h1 class="sectionheadertitle">Products</h1>

    <div class="product-grid">
        <?php
        $query = "SELECT * FROM tblproduct ORDER BY id ASC";
        $result = mysqli_query($con, $query);
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="product-item">
                <form method="post" action="cart.php?action=add&code=<?php echo $row["code"]; ?>&added=1">
                    <div class="product-image"><img src="uploads/<?php echo $row["image"]; ?>" alt="<?php echo $row["name"]; ?>"></div>
                    <div class="product-title"><?php echo $row["name"]; ?></div>
                    <div class="product-price">R<?php echo $row["price"]; ?></div>
                    <input type="text" class="product-quantity" name="quantity" value="1" size="2" />
                    <input type="submit" value="Add to Cart" class="btnAddAction" />
                </form>
            </div>
        <?php } ?>
    </div>
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
</body>
</html>
