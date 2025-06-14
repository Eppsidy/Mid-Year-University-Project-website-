<?php
session_start();
session_destroy();
header("Location: index.php");
echo "Redirecting... If you see this, the header failed.";
exit();
?>