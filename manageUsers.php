<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: customerLogin.php");
    exit();
}

$query = "SELECT id, name, surname, email FROM customerlogin ORDER BY id DESC";
$result = $con->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>EPPSIDY Admin - Manage Users</title>
    <link rel="stylesheet" href="admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Manage Users</h1>
    <a href="adminDashboard.php">← Back to Dashboard</a>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['name'] . ' ' . $user['surname'] ?></td>
                <td><?= $user['email'] ?></td>
                <td>
                    <a href="deleteuser.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
