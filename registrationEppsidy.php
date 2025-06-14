<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>EPPSIDY - Register</title>
<link rel="stylesheet" href="Login.css">
<script src="LoginValidation.js" defer></script>
</head>
<body>

<div class="navbar">
    <a href="index.php" class="logo">EPPSIDY</a>
</div>

<form action="registrationEppsidy.php" method="POST" onsubmit="return validateLogin()">
  
  <div id="formcontainer">
    <header class="sectionheader">
	    <h1 class="sectionheadertitle">Register</h1>
    </header>

    <label for="name"><b>Name</b></label>
    <input type="text" placeholder="Enter Name" name="name" required>

    <label for="surname"><b>Surname</b></label>
    <input type="text" placeholder="Enter Surname" name="surname" required>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" id="email" required>

    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" id="password" required>

    <button type="submit" name="submit">Create</button>

    <a href="customerLogin.php"><div class="CreateAcc">Already have an account? Login</div></a>

    <label>
      <input type="checkbox" checked="checked" name="remember"> Remember me
    </label>
  </div>

<?php
include 'connect.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $insert = "INSERT INTO customerlogin (name, surname, email, password) 
               VALUES ('$name', '$surname', '$email', '$password')";

    if (mysqli_query($con, $insert)) {
        header("Location: customerLogin.php");
    } else {
        echo "<div style='text-align:center;'>Registration failed. Try again.</div>";
    }
}
?>

</form>

</body>
</html>
