<?php
session_start();
$orderId = $_GET['order_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Successful</title>
    <link rel="stylesheet" href="ordersuccessful.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="success-container">
    <h1>🎉 Thank You for Your Purchase!</h1>
    
    <?php if ($orderId): ?>
        <p>Your order has been placed successfully.</p>
        <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($orderId); ?></p>
    <?php else: ?>
        <p>We couldn’t find your order details, but thank you for shopping with us!</p>
    <?php endif; ?>

    <a href="index_loggedin.php">Continue Shopping</a>
</div>

</body>
</html>