<?php
session_start();
$admin_user = 'admin';
$admin_pass = 'password123';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
    }
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo '<form method="POST"><input name="username" placeholder="Username"><input name="password" type="password"><button>Login</button></form>';
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Admin Dashboard</h1>
        <nav>
            <a href="orders.php">Manage Orders</a>
            <a href="products.php">Manage Products</a>
            <a href="inventory.php">Inventory Logs</a>
            <a href="?logout=true">Logout</a>
        </nav>
        <p>Welcome to the Fashion Must Have admin panel.</p>
    </div>
</body>
</html>