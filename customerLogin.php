<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>EPPIDY - LOGIN</title>
<link rel="stylesheet" href="Login.css">
</head>
<body>

<div class="navbar">
    <a href="index.php" class="logo">EPPSIDY</a>
</div>

<form action="customerLogin.php" method="POST">
  
  <header class="sectionheader">
	<h1 class="sectionheadertitle">Login</h1>
  </header>

  <br class="container">
    <label for="uname"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="uname" id="email" required>

    <label for="psw"><b>Password</b></label>

    <input type="password" placeholder="Enter Password" name="psw" id="password" required>

	<a href="#"><div class="Recover">forgot password?</div></a>

    <button type="submit" name="submit">Sign In</button>
    <label>

	<a href="registrationEppsidy.php"><div class="CreateAcc">Create account</div></a></label></br>
      <input type="checkbox" checked="checked" name="remember"> Remember me
    </label>
  </div>
 <?php
include 'connect.php';

if (isset($_POST["submit"])) {
    $email = $_POST["uname"];
    $password = $_POST["psw"];

    $query = "SELECT id, name, role, password FROM customerlogin WHERE email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $user = $result->fetch_assoc()) {
        if ($user['password'] === $password) {
            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['userName'] = $email;
            $_SESSION['role'] = $user['role'];
            $_SESSION['time'] = time();

            if ($user['role'] === 'admin') {
                header("Location: adminDashboard.php");
            } else {
                header("Location: index_loggedin.php");
            }
            exit();
        } else {
            echo "<div style='text-align: center;'>Incorrect password</div>";
        }
    } else {
        echo "<div style='text-align: center;'>User not found</div>";
    }
}
?>

  <div class="container" style="background-color:#f1f1f1">
   
  </div>
</form>
</body>
</html>